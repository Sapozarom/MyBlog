<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Finds all comments of specific users
     * 
     * @param User $user
     * 
     * @return Comment[]
     */
    public function findAllUserComments (User $user) {
        $qb = $this->createQueryBuilder('c')
            ->where('c.author = :val')
            ->setParameter('val', $user)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $qb;
    }

}
