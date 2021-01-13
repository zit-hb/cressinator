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
     * @param int $groupId
     * @return RecordingEntity[]
     */
    public function findClosestByGroup(DateTime $date, int $groupId): array
    {
        $recordings = [];

        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->getEntityManager()->getRepository(GroupEntity::class);
        /** @var GroupEntity|null $group */
        $group = $groupRepository->find($groupId);

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
     * @param int $sourceId
     * @return RecordingEntity|null
     */
    public function findClosestBySource(DateTime $date, int $sourceId): ?RecordingEntity
    {
        $laterRecording = $this->findNextBySource($date, $sourceId);
        $previousRecording = $this->findPreviousBySource($date, $sourceId);

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

    /**
     * Find recording for source that comes after date
     * @param DateTime $date
     * @param int $sourceId
     * @return RecordingEntity|null
     */
    public function findNextBySource(DateTime $date, int $sourceId): ?RecordingEntity
    {
        $queryBuilder = $this->createQueryBuilder('recording')
            ->setParameter('date', $date)
            ->andWhere('recording.createdAt > :date')
            ->setParameter('source', $sourceId)
            ->andWhere('recording.source = :source')
            ->orderBy('recording.createdAt', 'asc')
            ->setMaxResults(1)
        ;
        try {
            return $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {
            return null;
        }
    }

    /**
     * Find recording for source that comes before date
     * @param DateTime $date
     * @param int $sourceId
     * @return RecordingEntity|null
     */
    public function findPreviousBySource(DateTime $date, int $sourceId): ?RecordingEntity
    {
        $queryBuilder = $this->createQueryBuilder('recording')
            ->setParameter('date', $date)
            ->andWhere('recording.createdAt < :date')
            ->setParameter('source', $sourceId)
            ->andWhere('recording.source = :source')
            ->orderBy('recording.createdAt', 'desc')
            ->setMaxResults(1)
        ;
        try {
            return $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {
            return null;
        }
    }

    /**
     * Find last recording for source
     * @param int $sourceId
     * @return RecordingEntity|null
     */
    public function findLastBySource(int $sourceId): ?RecordingEntity
    {
        $queryBuilder = $this->createQueryBuilder('recording')
            ->setParameter('source', $sourceId)
            ->andWhere('recording.source = :source')
            ->orderBy('recording.createdAt', 'desc')
            ->setMaxResults(1)
        ;
        try {
            return $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {
            return null;
        }
    }

    /**
     * Find first recording for source
     * @param int $sourceId
     * @return RecordingEntity|null
     */
    public function findFirstBySource(int $sourceId): ?RecordingEntity
    {
        $queryBuilder = $this->createQueryBuilder('recording')
            ->setParameter('source', $sourceId)
            ->andWhere('recording.source = :source')
            ->orderBy('recording.createdAt', 'asc')
            ->setMaxResults(1)
        ;
        try {
            return $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {
            return null;
        }
    }
}
