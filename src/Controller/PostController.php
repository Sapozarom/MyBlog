<?php

// src/Controller/PostController.php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\FileUploader;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class PostController extends AbstractController
{
   
    /**
     * Dispalys specific article by slug and allows to add comment in article view
     * 
     * @Route("/article/{slug}", 
     * name="article.view",
     * methods={"POST", "GET"},
     * )
     * 
     * @param EntityManagerInterface $em
     * @param Post $post
     * @param Request $request
     * 
     * @return Response
     */
    public function view(EntityManagerInterface $em , Post $post, Request $request) {

        //if article is not published only author of this article can see it
        if( !$post->getPublished() && 
            //check if not authenticated
            (!$this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) ||
            //or check if authenticated and if user is not author of this post
            (($this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY') && 
            $this->container->get('security.token_storage')->getToken()->getUser()->getId() != $post->getAuthor()->getId()))))    
        {  
            $this->addFlash('fail', "This post wasn't published yet");
            return $this->redirectToRoute('homepage');    
        }

        //process comment
        $comment = new Comment();

        if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
            $comment->setAuthor($currentUser);
        }
        
        $form = $this->createForm(CommentType::class, $comment);        
        $form->handleRequest($request);
        
        $comment->setPost($post);
        
        //process comment form
        if ( $form->isSubmitted() && $form->isValid() ) 
        {         
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('article.view', [
                'slug' => $post->getSlug(),
            ]);
        }

        return $this->render('postView/index.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
        


    /**
     * Create new article. Action available only for ROLE_ADMIN.
     * 
     * @Route("/post/create", 
     * name="article.create",
     * methods={"POST", "GET"},
     * )
     * 
     * @param EntityManagerInterface $em
     * @param Request $request
     * 
     * @return Response
     */
    public function create(EntityManagerInterface $em , Request $request, FileUploader $fileUploader) {
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $post = new Post();
        
        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(PostType::class, $post);
        
        //add 2 diferent working submit buttons, first only saves post and second allow to publish it instantly
        $form->add('save', SubmitType::class, [
                'label' => 'Save'
                ])
            ->add('saveAndPublish', SubmitType::class, [
                'label' => 'Save & Publish'
                ]);

        $form->handleRequest($request);        
        
        //process form
        if ( $form->isSubmitted() && $form->isValid() ) {
            
            $coverPhoto = $form->get('picture')->getData();

            if ( $coverPhoto instanceof UploadedFile ) 
            {
                $fileName = $fileUploader->uploadPostCover($coverPhoto);
                
                $post->setPicture($fileName);
            }

            $post->setAuthor($currentUser);

            //publish status depends on used button
            if( $form->get('save')->isClicked() ) 
            {
                $post->setPublished(false);
            } 
            elseif ($form->get('saveAndPublish')->isClicked()) 
            {
                $post->setPublished(true);
            }

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('article.view', [
                'slug' => $post->getSlug(),
            ]);
        }

        return $this->render('user/createPost.html.twig', [
            'form' => $form->createView(),
        ]);            
        
    }

    /**
     * Edit article. Available only for ROLE_ADMIN
     * 
     * @Route("/article/{slug}/edit", 
     * name="article.edit",
     * methods={"POST", "GET"},
     * )
     * 
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param Post $post
     * 
     * @return Response
     */
    public function edit(EntityManagerInterface $em , Request $request, Post $post, FileUploader $fileUploader) {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        //chceck if user is not an author of this article
        if( $post->getAuthor()->getId() != $currentUser->getId() )
        {   
            //redirect to homepage with message
            $this->addFlash('fail', "You can't edit other authors posts");
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        //process form
        if ($form->isSubmitted() && $form->isValid()) {
            
            $coverPhoto = $form->get('picture')->getData();

            if ( $coverPhoto instanceof UploadedFile) 
            {
                $fileName = $fileUploader->uploadPostCover($coverPhoto);
                
                $post->setPicture($fileName);
            }

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', "All changes were successfully saved");
            return $this->redirectToRoute('article.view', [
                'slug' => $post->getSlug(),
            ]);
        }

        return $this->render('user/editPost.html.twig', [
            'form' => $form->createView(),
            'file' => $post->getPicture()
        ]);

    }

    /**
     * Changes published value to true.
     * 
     * @Route("/article/{slug}/publish", 
     * name="article.publish",
     * methods={"POST", "GET"},
     * )
     * 
     * @param EntityManagerInterface $em
     * @param Post $post
     * 
     * @return Response
     */

    public function publish(EntityManagerInterface $em, Post $post) {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        //check if user is not an author of this article
        if($post->getAuthor()->getId() != $currentUser->getId())
        {
            //redirect to homepage with message if not author
            $this->addFlash('fail', "You can't edit other authors posts");
            return $this->redirectToRoute('homepage');
        } 

        //required for tag bundle
        $tempTags = $post->getTagsText();

        $post->setPublished(true);
        $post->setCreatedAt(new \DateTime());
        $post->setTagsText($tempTags);

        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('article.view', [
            'slug' => $post->getSlug(),
        ]);
    }

    /**
     * Changes archived value to true.
     * 
     * @Route("/article/{slug}/archive", 
     * name="article.archive",
     * methods={"POST", "GET"},
     * )
     * 
     * @param EntityManagerInterface $em
     * @param Post $post
     * 
     * @return Response
     * 
     */
    public function archive(EntityManagerInterface $em, Post $post) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        //check if user is not an author of this article
        if($post->getAuthor()->getId() != $currentUser->getId())
        {
            //redirect to homepage with message if not an author
            $this->addFlash('fail', "You can't edit other authors posts");
            return $this->redirectToRoute('homepage');
        }   

        //required for tag bundle
        $tempTags = $post->getTagsText();

        $post->setArchived(true);
        $post->setTagsText($tempTags);

        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('article.view', [
            'slug' => $post->getSlug()
        ]);  
    }

    /**
     * Changes archived value to false.
     * 
     * @Route("/article/{slug}/unarchive", 
     * name="article.unarchive",
     * methods={"POST", "GET"},
     * )
     * 
     * @param EntityManagerInterface $em
     * @param Post $post
     * 
     * @return Response
     */
    public function unarchive(EntityManagerInterface $em, Post $post) {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        //check if user is not an author of this article
        if($post->getAuthor()->getId() != $currentUser->getId())
        {
            //redirect to homepage with message if not an author
            $this->addFlash('fail', "You can't edit other authors articles");
            return $this->redirectToRoute('homepage');
        } 

        //required for tag bundle
        $tempTags = $post->getTagsText();
        
        $post->setArchived(false);
        $post->setTagsText($tempTags);

        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('article.view', [
            'slug' => $post->getSlug()
        ]);

    }

    /**
     * Delete post and all associated comments. Clears tag collection.
     * Only available for author of the post.
     * 
     * @Route("/article/{slug}/delete", 
     * name="article.delete",
     * methods={"DELETE"},
     * )
     * 
     * @param EntityManagerInterface $em
     * @param Post $post
     * 
     * @return Response
     */
    public function delete(EntityManagerInterface $em, Post $post) {
    
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        //check if user is an author of this article
        if($post->getAuthor()->getId() != $currentUser->getId()) 
        {
            //redirect to homepage with message if not an author
            $this->addFlash('fail', "You can't edit other authors posts");
            return $this->redirectToRoute('homepage');
        }

        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Article was deleted');
        return $this->redirectToRoute('homepage');
    }

     /**
     * Creates archive list.
     * 
     * @Route("/article/archive-list", 
     * name="article.archiveList",
     * methods={"POST", "GET"},
     * )
     * 
     * @param PostRepository $pr
     * 
     * @return Response
     */
    public function listArchived(PostRepository $pr) {

        //find all published articles
        $archivedArray = $pr->archivedList();   

        return $this->render('homepage/layout/archiveSection.html.twig', [
            'archivedArray' => $archivedArray,
        ]); 
    }

    /**
     * Finds all articles created in date MONTH/YEAR
     * 
     * @Route("/archive/articles-under-date/{year}/{month}", 
     * name="article.archive.articlesUnderDate",
     * methods={"POST", "GET"},
     * )
     * 
     * @param PostRepository $pr
     * @param string $year
     * @param string $month
     * 
     * @return Response
     * 
     */
    public function articlesInMonth(PostRepository $pr, $year, $month) {
        
        //find all published articles
        $archivedArray = $pr->archivedList();
        
        $postArray = array();

        //pick articles form specific month/year
        if (!isset($archivedArray[$year][$month])) 
        {
            //redirect to homepage if there are no posts under specific moth/year
            $this->addFlash('fail', 'There are no articles archived under this date');
            return $this->redirectToRoute('homepage');
        }
        $postArray = $archivedArray[$year][$month];

        return $this->render('homepage/archive.html.twig', [
            'posts' => $postArray,
            'year' => $year,
            'month' => $month,
        ]);
    }

    /**
     * Finds all articles of specific author.
     * 
     * @Route("/article/by-author/{id}", 
     * name="article.byAuthor",
     * methods={"POST", "GET"},
     * )
     * 
     * @param User $user
     * @param PostRepository $pr
     * 
     * @return Response
     */
    public function byAuthor(User $user, PostRepository $pr) {
        
        $posts = $pr->findAuthorsPosts($user->getId());

        return $this->render('homepage/authors.html.twig', [
            'posts' => $posts,
            'user' => $user,
        ]);
    }

}
