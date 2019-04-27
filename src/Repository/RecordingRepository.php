<?php

namespace App\Repository;

use DateTime;
use App\Entity\RecordingEntity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class RecordingRepository extends EntityRepository
{
    /**
     * Find recording for group that is closest to date
     * @param DateTime $date
     * @param string $group
     * @return RecordingEntity|null
     */
    public function findClosestByGroup(DateTime $date, string $group): ?RecordingEntity
    {
        $queryBuilder = $this->createQueryBuilder('recording')
            ->setParameter('date', $date)
            ->andWhere('recording.createdAt > :date')
            ->setParameter('group', $group)
            ->andWhere('recording.group = :group')
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
            ->setParameter('group', $group)
            ->andWhere('recording.group = :group')
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
