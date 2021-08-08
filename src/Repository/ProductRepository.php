<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Purchase;
use Doctrine\ORM\EntityRepository;
use DateTime;
use Doctrine\ORM\Query\Expr\Join;

class ProductRepository extends EntityRepository
{
    public function updateNotIds($ids)
    {
        return $this->createQueryBuilder('p')
            ->update(Product::class, "p")
            ->set('p.leftCount', 0)
            ->where("p.id NOT IN(:ids)")
            ->setParameter("ids", $ids)
            ->getQuery()
            ->getResult();
    }

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

    public function findNew($sort = 'cost', $orderBy = 'DESC', $count = 16, $page = 1)
    {
        $date = (new \DateTime())->modify('-1 month')->format('Y-m-d H:i:s');

        $count = $count > 100 ? $count = 100 : $count;

        $result = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.createdAt > :date_to')
            ->andWhere('p.leftCount > 0')
            ->setParameter("date_to", "" . $date . "")
            ->orderBy('p.' . $sort, $orderBy)
            ->setMaxResults($count)
            ->setFirstResult($count * ($page - 1))
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function calcCountNew()
    {
        $date = (new \DateTime())->modify('-1 month')->format('Y-m-d H:i:s');

        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.createdAt > :date_to')
            ->andWhere('p.leftCount > 0')
            ->setParameter("date_to", "" . $date . "")
            ->getQuery()
            ->getResult();
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


    public function findByParams($ids, $sort, $count, $page = 1)
    {
        $page = $page > 0 ? $page : 1;
        $orderType = $sort === "createdAt" ? "DESC" : "ASC";

        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.id IN (" . implode(', ', $ids) . ")")
            ->setMaxResults($count)
            ->setFirstResult($count * ($page - 1))
            ->orderBy("p." . $sort, $orderType)
            ->getQuery()
            ->getSql();

        var_dump($query);
        die;
    }

    public function findByDisc($group, $sort, $orderBy = "ASC", $page = 1, $count = 16)
    {

        return $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.leftCount > 0 AND (p.productGroup = " . $group . ')')
            ->orderBy('p.' . $sort, $orderBy)
            ->setMaxResults($count)
            ->setFirstResult($count * ($page - 1))
            ->getQuery()
            ->getResult();
    }

    public function calcCount($group)
    {
        return $this->createQueryBuilder('p')
            ->select('count(p)')
            ->where("p.leftCount > 0 AND (p.productGroup = " . $group . ')')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByHits($date, $sort, $orderBy = "DESC", $count = 16, $page = 1)
    {
        $count = $count > 100 ? $count = 100 : $count;

        return $this->createQueryBuilder('pr')
            ->select("pr.id, COUNT(p.product) AS prodCount")
            ->join(Purchase::class, 'p', Join::WITH, 'pr = p.product')
            ->where('p.createdAt >= :date')
            ->andWhere("pr.leftCount > 0")
            ->setParameter("date", $date)
            ->groupBy('p.product')
            ->orderBy('pr.' . $sort, $orderBy)
            ->setMaxResults($count)
            ->setFirstResult($count * ($page - 1))
            ->getQuery()
            ->getResult();
    }

    public function calcCountHits($date)
    {
        return $this->createQueryBuilder('pr')
            ->select('count(pr)')
            ->join(Purchase::class, 'p', Join::WITH, 'pr = p.product')
            ->where('p.createdAt >= :date')
            ->andWhere("pr.leftCount > 0")
            ->setParameter("date", $date)
            ->getQuery()
            ->getResult();
    }
}