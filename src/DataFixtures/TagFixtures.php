<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class TagFixtures extends Fixture 
{
    /**
     * Loads Tag fixtures
     * 
     * @param ObjectManager $manager
     * 
     * @return void
     * 
     */
    public function load (ObjectManager $manager) : void
    {
        $tag1 = new Tag();
        $tag1->setName('news');
      

        $tag2 = new Tag();
        $tag2->setName('cool');
        
        $tag3 = new Tag();
        $tag3->setName('lorem');
       

        $tag4 = new Tag();
        $tag4->setName('amet');

        $tag5 = new Tag();
        $tag5->setName('dolor');

        $tag6 = new Tag();
        $tag6->setName('gars');

        $tag7 = new Tag();
        $tag7->setName('ipsum');
        
        $tag8 = new Tag();
        $tag8->setName('magna');

        $tag9 = new Tag();
        $tag9->setName('porta');

        $tag10 = new Tag();
        $tag10->setName('sit');

        $tag11 = new Tag();
        $tag11->setName('story');

        $manager->persist($tag1);
        $manager->persist($tag2);
        $manager->persist($tag3);
        $manager->persist($tag4);
        $manager->persist($tag5);
        $manager->persist($tag6);
        $manager->persist($tag7);
        $manager->persist($tag8);
        $manager->persist($tag9);
        $manager->persist($tag10);
        $manager->persist($tag11);

        $manager->flush();

    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            PostFixtures::class,
            
        ];
    }
}