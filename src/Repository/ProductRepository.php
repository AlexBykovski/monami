<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

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

	public function findNew($limit = 100, $offset = 0, $sort = ['p.id', 'DESC'])
	{
		$date = (new \DateTime())->modify('-1 month')->format('Y-m-d H:i:s');

		$result = $this->createQueryBuilder('p')
			->select('p')
			->where('p.createdAt > :date_to')
            ->andWhere('p.leftCount > 0')
			->setParameter("date_to", "" . $date . "")
			->orderBy('p.id', 'DESC')
			->getQuery()
			->getResult();
            
		return $result;
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

    public function findByDisc($group, $sort, $orderBy)
    {
        $orderType = $sort === "createdAt" ? "DESC" : "ASC";
        //var_dump($productGroup);
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where("p.leftCount > 0 AND (p.productGroup = " . $group . ')')
            ->orderBy('p.' . $sort, $orderBy)
            ->getQuery()
            ->getResult();
    }
}
