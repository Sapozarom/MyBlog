<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TagRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Finds all tags that are connected to at least one article and sort them by name.
     * 
     * @return Tag[] 
     * 
     */
    public function findActive(){
        $qb = $this->createQueryBuilder('t')
            ->orderBy('t.name')
            ->getQuery()
            ->getResult();

        $activetTags = array();
        foreach($qb as $tag){
            
            if (count($tag->getPosts())){
                $activetTags[] = $tag;
            }
        }
        return $activetTags;

    }
}