<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }


    /**
     * Finds all users (rewrite for authors only)
     * 
     * @return User[] 
     * 
     */
    public function findAuthors(){
        $qb = $this->createQueryBuilder('u')            
            ->getQuery()
            ->getResult();

        return $qb;

    }
}