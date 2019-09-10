<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

    class UserFixtures extends Fixture implements DependentFixtureInterface
    {

        private $userManager;

        public function __construct(UserManagerInterface $userManager)
        {
            $this->userManager = $userManager;
        }

        /**
         * Loads User fixtures
         * 
         * @param ObjectManager $manager
         * 
         * @return void
         * 
         */
        public function load(ObjectManager $manager)
        {
            //USER 1
            $user1 = $this->userManager->createUser();
            $user1->setUsername('user1');
            $user1->setEmail('user1@domain.com');
            $user1->setPlainPassword('user1');
            $user1->setName('Kasia z Podlasia');
            $user1->setPhoto('User1.png');
            $user1->setAbout('Vivamus justo quam, posuere sit amet elementum eget, sodales sed leo. Aenean lacinia urna sed massa ornare accumsan. Fusce non mattis est. Aenean viverra volutpat orci id pharetra. Nunc aliquam imperdiet lobortis. Nam metus lacus, malesuada quis sapien a, placerat tempor eros. Suspendisse eleifend diam a egestas elementum. Fusce sed augue magna. Donec eleifend tellus massa, in hendrerit magna ultricies eu. Praesent rhoncus elit metus, non bibendum metus faucibus in.');
            $user1->setEnabled(true);
            $user1->addComment($manager->merge($this->getReference('comm1')));
            $user1->addComment($manager->merge($this->getReference('comm3')));
            $user1->setRoles(array('ROLE_ADMIN'));
            $user1->setUpdatedAt = new \DateTime();
            $this->userManager->updateUser($user1);

            // USER2
            $user2 = $this->userManager->createUser();
            $user2->setUsername('user2');
            $user2->setEmail('user2@domain.com');
            $user2->setPlainPassword('user2');
            $user2->setName('Karol Parol');
            $user2->setPhoto('User2.png');
            $user2->setAbout('Etiam id finibus leo. Curabitur laoreet pretium tincidunt. Fusce consectetur ipsum at purus tempus ornare. Nullam consequat, lorem et sollicitudin vestibulum, turpis ipsum malesuada velit, eu condimentum nisl mi vel quam. Curabitur pulvinar id elit eget elementum. Nunc sed dapibus nunc. Proin fringilla leo at nisl suscipit pulvinar. Pellentesque vitae odio sit amet leo iaculis dignissim vitae semper diam. ');
            $user2->setEnabled(true);
            $user2->addComment($manager->merge($this->getReference('comm2')));
            $user2->addComment($manager->merge($this->getReference('comm4')));
            $user2->setRoles(array('ROLE_SUPER_ADMIN'));
            $user2->setUpdatedAt = new \DateTime();
            $this->userManager->updateUser($user2);

            // USER3
            $user3 = $this->userManager->createUser();
            $user3->setUsername('user3');
            $user3->setEmail('user3@domain.com');
            $user3->setPlainPassword('user3');
            $user3->setName('Rafał');
            $user3->setPhoto('User3.png');
            $user3->setEnabled(true);
            $user3->addComment($manager->merge($this->getReference('comm5')));
            $user3->setRoles(array('ROLE_USER'));
            $user3->setUpdatedAt = new \DateTime();
            $this->userManager->updateUser($user3);
            
            $manager->flush();

            $this->addReference('user1', $user1);
            $this->addReference('user2', $user2);
            $this->addReference('user3', $user3);
        }

        /**
         * @return array
         */
        public function getDependencies(): array
        {
            return [
                CommentFixtures::class,
                
            ];
        }
    }