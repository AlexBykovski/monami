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

    public function findCountByText($text)
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where("p.name LIKE :text")
            ->setParameter("text", "%" . $text . "%")
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByTextAndParams($count, $sort, $text, $page = 1)
    {
        $page = $page > 0 ? $page : 1;
        $orderType = $sort === "createdAt" ? "DESC" : "ASC";

        return $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.name LIKE :text")
            ->setParameter("text", "%" . $text . "%")
            ->setMaxResults($count)
            ->setFirstResult($count * ($page - 1))
            ->orderBy("p." . $sort, $orderType)
            ->getQuery()
            ->getResult();
    }
}
