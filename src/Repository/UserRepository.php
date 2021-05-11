<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->delete(User::class, "u")
            ->where("u.username = :username")
            ->setParameter("username", $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
