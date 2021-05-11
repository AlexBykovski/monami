<?php

namespace App\Repository;

use App\Entity\ProductGroup;
use Doctrine\ORM\EntityRepository;

class ProductGroupRepository extends EntityRepository
{
    public function deleteNotIds($ids)
    {
        return $this->createQueryBuilder('pgr')
            ->delete(ProductGroup::class, "pgr")
            ->where("pgr.id NOT IN(:ids)")
            ->setParameter("ids", $ids)
            ->getQuery()
            ->getResult();
    }

    public function findByField($field, $value)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->where($qb->expr()->eq('a.'.$field, '?1'));
        $qb->setParameter(1, $value);

        return $qb->getQuery()
            ->getResult();
    }

    public function findByNot($field, $value)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->where($qb->expr()->not($qb->expr()->eq('a.'.$field, '?1')));
        $qb->setParameter(1, $value);

        return $qb->getQuery()
            ->getResult();
    }
}