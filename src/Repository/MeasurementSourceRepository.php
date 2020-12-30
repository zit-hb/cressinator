<?php

namespace App\Repository;

use App\Entity\GroupEntity;
use Doctrine\ORM\EntityRepository;

class MeasurementSourceRepository extends EntityRepository
{
    /**
     * @param string $group
     * @return GroupEntity[]
     */
    public function findByGroup(string $group): array
    {
        $queryBuilder = $this->createQueryBuilder('source')
            ->setParameter('group', $group)
            ->where('source.group = :group')
            ->orderBy('source.id')
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
