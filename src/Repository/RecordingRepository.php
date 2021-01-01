<?php

namespace App\Repository;

use DateTime;
use App\Entity\GroupEntity;
use App\Entity\RecordingEntity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class RecordingRepository extends EntityRepository
{
    /**
     * Find recordings for all sources of a group that are closest to date
     * @param DateTime $date
     * @param int $group
     * @return RecordingEntity[]
     */
    public function findClosestByGroup(DateTime $date, int $group): array
    {
        $recordings = [];

        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->getEntityManager()->getRepository(GroupEntity::class);
        /** @var GroupEntity|null $group */
        $group = $groupRepository->find($group);

        if (!$group) {
            return $recordings;
        }

        foreach ($group->getRecordingSources() as $source) {
            $recording = $this->findClosestBySource($date, $source->getId());
            if ($recording !== null) {
                $recordings[$source->getId()] = $recording;
            }
        }
        return $recordings;
    }

    /**
     * Find recording for source that is closest to date
     * @param DateTime $date
     * @param int $source
     * @return RecordingEntity|null
     */
    public function findClosestBySource(DateTime $date, int $source): ?RecordingEntity
    {
        $queryBuilder = $this->createQueryBuilder('recording')
            ->setParameter('date', $date)
            ->andWhere('recording.createdAt > :date')
            ->setParameter('source', $source)
            ->andWhere('recording.source = :source')
            ->orderBy('recording.createdAt', 'asc')
            ->setMaxResults(1)
        ;
        try {
            /** @var RecordingEntity $laterRecording */
            $laterRecording = $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {}

        $queryBuilder = $this->createQueryBuilder('recording')
            ->setParameter('date', $date)
            ->andWhere('recording.createdAt < :date')
            ->setParameter('source', $source)
            ->andWhere('recording.source = :source')
            ->orderBy('recording.createdAt', 'desc')
            ->setMaxResults(1)
        ;
        try {
            /** @var RecordingEntity $previousRecording */
            $previousRecording = $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {}

        if (!isset($previousRecording) && !isset($laterRecording)) {
            return null;
        } else if (!isset($previousRecording) && isset($laterRecording)) {
            return $laterRecording;
        } else if (isset($previousRecording) && !isset($laterRecording)) {
            return $previousRecording;
        }

        $laterDifference = $laterRecording->getCreatedAt()->getTimestamp() - $date->getTimestamp();
        $previousDifference = $date->getTimestamp() - $previousRecording->getCreatedAt()->getTimestamp();

        if ($laterDifference < $previousDifference) {
            return $laterRecording;
        } else {
            return $previousRecording;
        }
    }
}
