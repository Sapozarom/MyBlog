<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture 
{
    /**
     * Loads Comment fixtures
     * 
     * @param ObjectManager $manager
     * 
     * @return void
     * 
     */
    public function load (ObjectManager $manager) : void
    {   
        // post 2
        $comment1 = new Comment();
        $comment1->setContent("such a cool article");

        $comment2 = new Comment();
        $comment2->setContent("oy mate!");

        $comment3 = new Comment();
        $comment3->setContent("Naaaah, just joking. That was quite stupid IMO");
        
        // post 5
        $comment4 = new Comment();
        $comment4->setContent("Cras ullamcorper diam ut congue condimentum. In massa velit, pharetra vitae orci vel, volutpat aliquet est.");
        
        $comment5 = new Comment();
        $comment5->setContent("Vestibulum morbi blandit cursus risus at ultrices mi tempus. Eget duis at tellus at urna condimentum mattis.");

        $manager->persist($comment1);
        $manager->persist($comment2);
        $manager->persist($comment3);
        $manager->persist($comment4);
        $manager->persist($comment5);

        $manager->flush();

        $this->addReference('comm1', $comment1);
        $this->addReference('comm2', $comment2);
        $this->addReference('comm3', $comment3);
        $this->addReference('comm4', $comment4);
        $this->addReference('comm5', $comment5);

    }

}