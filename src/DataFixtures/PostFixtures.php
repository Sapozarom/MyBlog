<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Loads Post fixtures
     * 
     * @param ObjectManager $manager
     * 
     * @return void
     * 
     */
    public function load (ObjectManager $manager) : void
    {   //CREATE POST 1
        $post1 = new Post();
        $post1->setTitle('Cool title  broooo');
        
        $post1->setContent("
        <p>
            Cras ullamcorper diam ut congue condimentum. In massa velit, pharetra vitae orci vel, volutpat aliquet est. Duis in volutpat felis, efficitur mattis massa. Donec tincidunt, leo a ultricies accumsan, ex risus placerat est, at bibendum nisi turpis eu enim. Nulla facilisi. Nunc sit amet enim interdum, egestas urna ut, vehicula lectus. Aliquam vel egestas metus. Nulla rhoncus, felis eu maximus convallis, tortor nisl egestas lacus, ac congue augue lorem at nulla. Cras maximus blandit sagittis.
        </p>
        <p>
            Praesent rutrum accumsan sapien, sit amet gravida velit pellentesque vel. Donec ultricies venenatis turpis sed gravida. Nam suscipit felis at sem fermentum interdum sit amet nec turpis. Nunc fringilla faucibus mauris ut mattis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras tellus quam, efficitur mollis dolor et, consectetur fermentum lorem. Aenean efficitur, lorem sit amet tempor varius, tortor mi ultrices ligula, eget sollicitudin ligula elit et justo. Proin vel maximus lacus, at gravida mi. Quisque urna metus, lacinia sit amet tortor ac, varius tempus tellus. Nunc euismod sem sed lacus finibus blandit.
        </p>  
        <p>
            Vestibulum morbi blandit cursus risus at ultrices mi tempus. Eget duis at tellus at urna condimentum mattis. Dui faucibus in ornare quam viverra orci. Aenean pharetra magna ac placerat. Leo duis ut diam quam nulla porttitor massa id neque. Augue lacus viverra vitae congue eu. Nulla pellentesque dignissim enim sit amet. Facilisi cras fermentum odio eu feugiat pretium nibh ipsum. Lorem sed risus ultricies tristique nulla aliquet. Lobortis elementum nibh tellus molestie nunc non blandit massa enim. Euismod elementum nisi quis eleifend quam adipiscing vitae proin. Nunc lobortis mattis aliquam faucibus purus in massa tempor nec. Dolor magna eget est lorem ipsum dolor. Sapien eget mi proin sed. Blandit turpis cursus in hac habitasse platea. Turpis cursus in hac habitasse.
        </p>
        ");
        $post1->setAuthor($manager->merge($this->getReference('user1')));
        $post1->setPicture('img1.jpg');
        $post1->setPublished(true);
        
        //CREATE POST 2
        $post2 = new Post();
        $post2->setTitle('Not cool title this time');
        $post2->addComment($manager->merge($this->getReference('comm4')));
        $post2->addComment($manager->merge($this->getReference('comm5')));

        $post2->setContent("
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed laoreet ligula enim, at vestibulum eros consequat in. Phasellus hendrerit orci eu felis tincidunt gravida. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu luctus augue. Morbi facilisis risus vel libero vehicula tempus. Quisque tempor maximus ipsum eu viverra. Sed nulla enim, posuere vel finibus posuere, bibendum eget nisl.
        </p>
        <p>
            Phasellus tincidunt tincidunt consequat. In porta magna et velit posuere, in consectetur mauris vestibulum. Nulla sapien velit, volutpat ut tortor eget, dictum condimentum lorem. Nullam et mi vitae risus maximus efficitur eget ut leo. Praesent venenatis elementum ligula at finibus. Duis sagittis, diam ut sodales sollicitudin, lorem felis semper erat, et condimentum erat turpis ut mauris. Pellentesque a leo sodales, ullamcorper arcu ac, suscipit risus. Donec ultricies libero nunc, non laoreet ante ullamcorper at. Fusce molestie elit ac ipsum vestibulum, non lacinia nunc malesuada. Aliquam vitae sapien vel eros finibus vehicula ut eget nisi. Curabitur bibendum porttitor ante et facilisis. Integer eu maximus ante. Cras rhoncus vulputate massa.
        </p>  
        <p>
            Cras ullamcorper diam ut congue condimentum. In massa velit, pharetra vitae orci vel, volutpat aliquet est. Duis in volutpat felis, efficitur mattis massa. Donec tincidunt, leo a ultricies accumsan, ex risus placerat est, at bibendum nisi turpis eu enim. Nulla facilisi. Nunc sit amet enim interdum, egestas urna ut, vehicula lectus. Aliquam vel egestas metus. Nulla rhoncus, felis eu maximus convallis, tortor nisl egestas lacus, ac congue augue lorem at nulla. Cras maximus blandit sagittis.
        </p>
        ");
        $post2->setAuthor($manager->merge($this->getReference('user1')));
        $post2->setPicture('img2.jpg');
        $post2->setPublished(true);

        //CREATE POST 3
        $post3 = new Post();
        $post3->setTitle('Cras ullamcorper diam ut congue');
        $post3->setTagsText('sit, magna, porta, dolor');
        $post3->setContent("
        <p>
            Phasellus tincidunt tincidunt consequat. In porta magna et velit posuere, in consectetur mauris vestibulum. Nulla sapien velit, volutpat ut tortor eget, dictum condimentum lorem. Nullam et mi vitae risus maximus efficitur eget ut leo. Praesent venenatis elementum ligula at finibus. Duis sagittis, diam ut sodales sollicitudin, lorem felis semper erat, et condimentum erat turpis ut mauris. Pellentesque a leo sodales, ullamcorper arcu ac, suscipit risus. Donec ultricies libero nunc, non laoreet ante ullamcorper at. Fusce molestie elit ac ipsum vestibulum, non lacinia nunc malesuada. Aliquam vitae sapien vel eros finibus vehicula ut eget nisi. Curabitur bibendum porttitor ante et facilisis. Integer eu maximus ante. Cras rhoncus vulputate massa.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed laoreet ligula enim, at vestibulum eros consequat in. Phasellus hendrerit orci eu felis tincidunt gravida. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu luctus augue. Morbi facilisis risus vel libero vehicula tempus. Quisque tempor maximus ipsum eu viverra. Sed nulla enim, posuere vel finibus posuere, bibendum eget nisl.
        </p>
        <p>
            Cras ullamcorper diam ut congue condimentum. In massa velit, pharetra vitae orci vel, volutpat aliquet est. Duis in volutpat felis, efficitur mattis massa. Donec tincidunt, leo a ultricies accumsan, ex risus placerat est, at bibendum nisi turpis eu enim. Nulla facilisi. Nunc sit amet enim interdum, egestas urna ut, vehicula lectus. Aliquam vel egestas metus. Nulla rhoncus, felis eu maximus convallis, tortor nisl egestas lacus, ac congue augue lorem at nulla. Cras maximus blandit sagittis.
        </p>
        ");
        $post3->setAuthor($manager->merge($this->getReference('user1')));
        $post3->setPicture('img3.jpg');
        $post3->setPublished(true);

        //CREATE POST 4
        $post4 = new Post();
        $post4->setTitle('Sed at elit ac ante');

        $post4->addComment($manager->merge($this->getReference('comm1')));
        $post4->addComment($manager->merge($this->getReference('comm2')));
        $post4->addComment($manager->merge($this->getReference('comm3')));

        $post4->setContent("
        <p>
            Interdum et malesuada fames ac ante ipsum primis in faucibus. Nunc quis metus facilisis enim lobortis venenatis. Nunc cursus cursus dui in gravida. Aenean malesuada, erat vitae dignissim maximus, nisl eros convallis erat, sit amet sagittis odio turpis quis nulla. Nulla nunc ipsum, varius vel finibus nec, facilisis in erat. Suspendisse sodales imperdiet risus vitae molestie. Nunc sit amet ipsum id eros ornare congue nec sed arcu.        </p>
        </p>
        <p>
            Fusce fermentum accumsan dui, quis pulvinar tortor sagittis a. Etiam vel aliquam dui. Maecenas congue iaculis ante quis porttitor. Proin vel augue quis dui consequat tincidunt. Mauris elementum leo mi, vitae suscipit neque egestas nec. Cras ultrices quis elit a pellentesque. Suspendisse quis fermentum eros. Nulla ultrices lacinia neque, sed gravida velit fringilla sed. Praesent non semper tellus. In nec nunc turpis. Quisque maximus id massa in condimentum. Sed luctus enim ac turpis ornare vehicula. Sed mattis a arcu non euismod. Phasellus quis nisl in nulla auctor dictum nec quis ante. Ut rhoncus lacus id erat fringilla varius. In sit amet libero tincidunt, viverra nulla eget, faucibus dolor.
        </p>
        <p>
            Cras ullamcorper diam ut congue condimentum. In massa velit, pharetra vitae orci vel, volutpat aliquet est. Duis in volutpat felis, efficitur mattis massa. Donec tincidunt, leo a ultricies accumsan, ex risus placerat est, at bibendum nisi turpis eu enim. Nulla facilisi. Nunc sit amet enim interdum, egestas urna ut, vehicula lectus. Aliquam vel egestas metus. Nulla rhoncus, felis eu maximus convallis, tortor nisl egestas lacus, ac congue augue lorem at nulla. Cras maximus blandit sagittis.
        </p>
        ");
        $post4->setAuthor($manager->merge($this->getReference('user2')));
        $post4->setPicture('img4.jpg');
        $post4->setPublished(true);

        //CREATE POST 5
        $post5 = new Post();
        $post5->setTitle('Donec ut leo a est vestibulum');
        
        $post5->setContent("
        <p>
            Proin congue ante in tortor condimentum fringilla. Curabitur sit amet varius est. Sed massa quam, dictum sed consequat suscipit, malesuada eget lacus. Maecenas lacinia nisi varius diam eleifend, ac ullamcorper sem dignissim. Phasellus quis lorem dui. Aenean bibendum nisi iaculis arcu ultricies, ut iaculis purus consectetur. Morbi laoreet arcu quis tempor volutpat. Nullam eu turpis auctor, blandit dui eget, sagittis ex. Maecenas erat lectus, laoreet quis tellus ut, pharetra tempor mi. In varius risus a tristique ornare. Cras sit amet quam justo. Aliquam hendrerit non libero eu porttitor.        </p>
        </p>
        <p>
            Fusce fermentum accumsan dui, quis pulvinar tortor sagittis a. Etiam vel aliquam dui. Maecenas congue iaculis ante quis porttitor. Proin vel augue quis dui consequat tincidunt. Mauris elementum leo mi, vitae suscipit neque egestas nec. Cras ultrices quis elit a pellentesque. Suspendisse quis fermentum eros. Nulla ultrices lacinia neque, sed gravida velit fringilla sed. Praesent non semper tellus. In nec nunc turpis. Quisque maximus id massa in condimentum. Sed luctus enim ac turpis ornare vehicula. Sed mattis a arcu non euismod. Phasellus quis nisl in nulla auctor dictum nec quis ante. Ut rhoncus lacus id erat fringilla varius. In sit amet libero tincidunt, viverra nulla eget, faucibus dolor.
        </p>
        <p>
            Cras ullamcorper diam ut congue condimentum. In massa velit, pharetra vitae orci vel, volutpat aliquet est. Duis in volutpat felis, efficitur mattis massa. Donec tincidunt, leo a ultricies accumsan, ex risus placerat est, at bibendum nisi turpis eu enim. Nulla facilisi. Nunc sit amet enim interdum, egestas urna ut, vehicula lectus. Aliquam vel egestas metus. Nulla rhoncus, felis eu maximus convallis, tortor nisl egestas lacus, ac congue augue lorem at nulla. Cras maximus blandit sagittis.
        </p>
        ");
        $post5->setAuthor($manager->merge($this->getReference('user1')));
        $post5->setPicture('img5.jpg');
        $post5->setPublished(false);
        
        //CREATE POST 6
        $post6 = new Post();
        $post6->setTitle('Mauris diam metus');
        $post6->setContent("
        <p>
            Nam vitae libero condimentum tellus faucibus lobortis ut ac risus. Fusce nibh mi, semper vel luctus ac, lacinia et nisl. Integer maximus cursus massa, blandit ullamcorper diam varius vitae. Suspendisse tortor nulla, semper nec aliquam a, efficitur vel dui. Donec tincidunt molestie dui. Quisque aliquet nisi non lectus porttitor, vitae finibus lorem accumsan. Nulla aliquam justo eu dui commodo, in pharetra turpis volutpat. Donec suscipit nisl in est pretium, eu euismod nisl pretium. Donec sodales tellus at nisl egestas, non imperdiet risus ullamcorper. Sed non finibus ligula, eget hendrerit nulla. Cras at vestibulum risus. Donec nec magna maximus, feugiat orci ut, fermentum neque. Suspendisse pretium metus quis ipsum gravida, at euismod augue luctus.    
        </p>
        <p> 
            Fusce fermentum accumsan dui, quis pulvinar tortor sagittis a. Etiam vel aliquam dui. Maecenas congue iaculis ante quis porttitor. Proin vel augue quis dui consequat tincidunt. Mauris elementum leo mi, vitae suscipit neque egestas nec. Cras ultrices quis elit a pellentesque. Suspendisse quis fermentum eros. Nulla ultrices lacinia neque, sed gravida velit fringilla sed. Praesent non semper tellus. In nec nunc turpis. Quisque maximus id massa in condimentum. Sed luctus enim ac turpis ornare vehicula. Sed mattis a arcu non euismod. Phasellus quis nisl in nulla auctor dictum nec quis ante. Ut rhoncus lacus id erat fringilla varius. In sit amet libero tincidunt, viverra nulla eget, faucibus dolor.
        </p>
        <p>
            Cras ullamcorper diam ut congue condimentum. In massa velit, pharetra vitae orci vel, volutpat aliquet est. Duis in volutpat felis, efficitur mattis massa. Donec tincidunt, leo a ultricies accumsan, ex risus placerat est, at bibendum nisi turpis eu enim. Nulla facilisi. Nunc sit amet enim interdum, egestas urna ut, vehicula lectus. Aliquam vel egestas metus. Nulla rhoncus, felis eu maximus convallis, tortor nisl egestas lacus, ac congue augue lorem at nulla. Cras maximus blandit sagittis.
        </p>
        ");
        $post6->setAuthor($manager->merge($this->getReference('user1')));
        $post6->setPicture('img6.jpg');
        $post6->setPublished(true);

        $manager->persist($post1);
        $manager->persist($post2);
        $manager->persist($post3);
        $manager->persist($post4);
        $manager->persist($post5);
        $manager->persist($post6);

        $manager->flush();

        //UPDATE PART
        $date1 = new \DateTime();

        //post1 update
        $date1->modify('-32 day');
        $post1->setTagsText('cool, story, news, dolor');
        $post1->setCreatedAt($date1);
        
        //post2 update
        $post2->setTagsText('lorem, ipsum, news, dolor');

        //post3 update
        $date3 = new \DateTime();
        $date3->modify('-300 day');
        $post3->setTagsText('sit, magna, porta, lorem, dolor, gars');
        $post3->setCreatedAt($date3);

        //post4 update
        $date4 = new \DateTime();
        $date4->modify('-12 day');
        $post4->setTagsText('magna, porta, dolor, amet');
        $post4->setCreatedAt($date4); 
        
        //post5 update
        $post5->setTagsText('magna, porta, lorem, cool');
        $date5 = new \DateTime();
        $date5->modify('-64 day');
        $post5->setCreatedAt($date5);
        
        //post6 update
        $post6->setTagsText('news, lorem');
        $post6->setArchived(true);
        $date6 = new \DateTime();
        $date6->modify('-33 day');
        $post6->setCreatedAt($date6);

        $manager->persist($post1);
        $manager->persist($post2);
        $manager->persist($post3);
        $manager->persist($post4);
        $manager->persist($post5);
        $manager->persist($post6);
        
        $manager->flush();

        $this->addReference('post1', $post1);
        $this->addReference('post2', $post2);
        $this->addReference('post3', $post3);
        $this->addReference('post4', $post4);

    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TagFixtures::class,
            CommentFixtures::class,
        ];
    }
}