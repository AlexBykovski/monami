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
}