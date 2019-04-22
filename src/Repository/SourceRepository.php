<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class SourceRepository extends EntityRepository
{
    /**
     * @param string $group
     * @return mixed
     */
    public function findByGroup(string $group)
    {
        $queryBuilder = $this->createQueryBuilder('source')
            ->setParameter('group', $group)
            ->where('source.group = :group')
            ->orderBy('source.id')
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
