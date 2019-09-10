<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PostRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Finds all articles visible on homepage and sort them descending by date
     * 
     * @return Post[]
     * 
     */
    public function findAllVisibleDesc() {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.published = true')
            ->andWhere('p.archived = false')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return $qb;
    }

    /**
     * Finds all types of articles of specific User
     * 
     * @param User $user
     * 
     * @return Post[]
     * 
     */
    public function findAllAuthorsPosts (User $user){

        $qb = $this->createQueryBuilder('p')
        ->where('p.author = :user')
        ->setParameter('user', $user)
        ->orderBy('p.createdAt', 'DESC')
        ->getQuery()
        ->getResult();

        return $qb;
    }

    /**
     * Finds all published and not archived articles of specific User
     * 
     * @param User $user
     * 
     * @return Post[]
     * 
     */
    public function findPublished (User $user){

        $qb = $this->createQueryBuilder('p')
        ->where('p.author = :user')
        ->andWhere('p.published = true')
        ->andWhere('p.archived = false')
        ->setParameter('user', $user)
        ->orderBy('p.createdAt', 'DESC')
        ->getQuery()
        ->getResult();

        return $qb;
    }

    /**
     * Finds all unpublished and unarchived articles of specific User
     * 
     * @param User $user
     * 
     * @return Post[]
     * 
     */
    public function findUnpublished (User $user){

        $qb = $this->createQueryBuilder('p')
        ->where('p.author = :user')
        ->andWhere('p.published = false')
        ->andWhere('p.archived = false')
        ->setParameter('user', $user)
        ->orderBy('p.createdAt', 'DESC')
        ->getQuery()
        ->getResult();

        return $qb;
    }

    /**
     * Finds all archived articles of specific User
     * 
     * @param User $user
     * 
     * @return Post[]
     * 
     */
    public function findArchived(User $user) {
        $qb = $this->createQueryBuilder('p')
        ->where('p.author = :user')
        ->andWhere('p.archived = true')
        ->setParameter('user', $user)
        ->orderBy('p.createdAt', 'DESC')
        ->getQuery()
        ->getResult();

        return $qb;
    }

    /**
     * Finds all published articles of specific User by ID
     * 
     * @param int $id
     * 
     * @return Post[]
     * 
     */
    public function findAuthorsPosts($id){

        $qb = $this->createQueryBuilder('p')
        ->where('p.author = :id') 
        ->setParameter('id', $id)
        ->andWhere('p.published = true')
        ->orderBy('p.createdAt', 'desc')
        ->getQuery()
        ->getResult();

        return $qb;
    }

    /**
     * Finds all published articles and sort them by date into associative array used in archived section
     * 
     * 
     * @param int $id
     * 
     * @return Post[] | returns associative array Post[year][month]
     * 
     */
    public function archivedList(){
        $qb = $this->createQueryBuilder('p')
            ->where('p.published = true')
            ->orderBy('p.createdAt', 'desc')
            ->getQuery()
            ->getResult();

        $archive = array();
        $i = 0;
        
        foreach($qb as $post){
            $created = $post->getCreatedAt();
            
            $year = $created->format('Y');
            $month = $created->format('m');
            $title = $post->getTitle();
            $id = $post->getId();

            if(!isset($archive[$year][$month])){
                $i=0;
                $archive[$year][$month][$i] = $post;
            } else {
                $archive[$year][$month][$i] = $post;
            }

            $i++;

        }

        return $archive;
    }
    


    
    
}