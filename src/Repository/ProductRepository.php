<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findByText($text)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.name LIKE :text")
            ->setParameter("text", "%" . $text . "%")
            ->getQuery()
            ->getResult();
    }

    public function findByTextAndParams($count, $sort, $text)
    {
        $orderType = $sort === "createdAt" ? "DESC" : "ASC";

        return $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.name LIKE :text")
            ->setParameter("text", "%" . $text . "%")
            ->setMaxResults($count)
            ->orderBy("p." . $sort, $orderType)
            ->getQuery()
            ->getResult();
    }
}
