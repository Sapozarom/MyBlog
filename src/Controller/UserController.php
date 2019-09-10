<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserPanel\NameType;
use App\Form\UserPanel\PhotoType;
use App\Form\UserPanel\MailType;
use App\Form\UserPanel\AboutType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Service\FileUploader;

class UserController extends AbstractController
{
    /**
     * Displays "User Panel" section
     * 
     * @Route("/user-panel", 
     * name="user.panel"
     * )
     * 
     * @return Response
     * 
     */
    public function index() {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        return $this->render('user/panel/index.html.twig', [
            'user' => $currentUser,
        ]);
        
    }

    /**
     * Allows to edit user info row by row with different forms
     * 
     * @Route("/user-panel/edit/{view}",
     * defaults={"view" = null},
     * methods={"GET", "POST"},
     * name="user.panel.edit")
     * 
     * @param string $view
     * @param Request $request
     * @param EntityManagerInterface $em
     * 
     * @return Response
     * 
     */
    public function editInfo($view, Request $request, EntityManagerInterface $em, FileUploader $fileUploader) {
        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        
        //pick form depends on view
        switch($view){
            case 'photo':
                $form = $this->createForm(PhotoType::class, $currentUser);
                break;
            case 'name':
                $form = $this->createForm(NameType::class, $currentUser);
                break;
            case 'email':
                $form = $this->createForm(MailType::class, $currentUser);
                break;
            case 'about':
                $this->denyAccessUnlessGranted('ROLE_ADMIN'); 
                $form = $this->createForm(AboutType::class, $currentUser); 
                break;
            default:
                $this->addFlash('fail', 'Error: pick the edit option one more time');
                return $this->redirectToRoute('user.panel');
        };

        $form->handleRequest($request);

        //process form
        if ($form->isSubmitted() && $form->isValid()) {

            if ($view == 'photo' && $form->get($view)->getData() != null) {
                
                $userPhoto = $form->get('photo')->getData();

                $fileName = $fileUploader->uploadUserPhoto($userPhoto);
                    
                $currentUser->setPhoto($fileName);

            } 

            $em->persist($currentUser);
            $em->flush();

            //success message
            $this->addFlash('success', 'Your settings were successfully updated');

            return $this->redirectToRoute('user.panel', [
                'user' => $currentUser,
            ]);
        }

        return $this->render('user/panel/editInfo.html.twig', [
            'edit' => $view,
            'user' => $currentUser,
            'form' => $form->createView(),
        ]); 
        
    }

    /**
     * Lists and displays not deleted comments of current User
     * 
     * @Route("/user-panel/comments",
     * methods={"GET", "POST"},
     * name="user.panel.comments")
     * 
     * @param CommentRepository $cr
     * 
     * @return Response
     */
    public function listComments(CommentRepository $cr) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        $commentList = $cr->findAllUserComments($currentUser);

        return $this->render('user/panel/comments.html.twig', [
            'list' => $commentList,
        ]); 
    }

    /**
     * Allow to change password.
     * 
     * @Route("/user-panel/password",
     * methods={"GET", "POST"},
     * name="user.panel.password")
     * 
     * @return Response
     */
    public function changePassword(){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        return $this->render('user/panel/password.html.twig'); 

    }
    /**
     * Lists all articles of current user in diferent views
     * 
     * @Route("/user-panel/posts/{view}",
     * methods={"GET", "POST"},
     * defaults={"view" = "published"},
     * name="user.panel.posts")
     * 
     * @param PostRepository $pr
     * @param string $view
     * 
     * @return Response
     */
    public function listPosts(PostRepository $pr, $view) {
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        
        //prepare article list depends on view
        switch($view)
        {
            case 'published':
                $posts = $pr->findPublished($currentUser);
                break;
            case 'unpublished':
                $posts = $pr->findUnpublished($currentUser);    
                break;
            case 'archived':
                $posts = $pr->findArchived($currentUser);
                break;
            case 'all':
                $posts = $pr->findAllAuthorsPosts($currentUser); 
                break;
            default:
                $posts = $pr->findPublished($currentUser);
                $view = 'published';
        };
        
        return $this->render('user/panel/posts.html.twig', [
            'list' => $posts,
            'view' => $view,
        ]); 
         
    }

    /**
     * Lists and displays all authors
     * 
     * @Route("/user/authors",
     * methods={"GET", "POST"},
     * defaults={"view" = "published"},
     * name="user.authors")
     * 
     * @param UserRepository $ur
     * 
     * @return Response
     */
    public function listAuthors(UserRepository $ur) {
        
        $users = $ur->findAuthors();
        
        return $this->render('homepage/authors.html.twig',[
            'authors' => $users,
        ]); 

    }

    /**
     * Suspend User. Only available fo SUPER_ADMIN
     * 
     * @Route("/user/suspend/{id}",
     * methods={"GET", "POST"},
     * defaults={"view" = "published"},
     * name="user_admin_suspend")
     * 
     * @param User $user
     * @param EntityManagerInterface $em
     * 
     * @return Response
     */
     public function suspend(User $user, EntityManagerInterface $em) {
        
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        
        $user->setEnabled(false);

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'User '. $user->getUsername() .' was suspended');

        return $this->redirectToRoute('easyadmin',[
            'entity' => 'User'
        ]); 
        
    }
    
}
