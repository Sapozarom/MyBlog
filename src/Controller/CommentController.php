<?php

// src/Controller/CommentController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;

class CommentController extends AbstractController {
    
    /**
     * Allows to edit comment. Available only to author of the comment.
     * 
     * @Route("/comment/{id}/edit",
     * defaults={"_fragment" = "commentForm"},
     * name="comment.edit",
     * methods={"POST", "GET"},
     * )
     * 
     * 
     * @param EntityManagerInterface $em
     * @param Comment $comment
     * @param Request $request
     * 
     * @return Response
     */
    public function edit(Comment $comment, EntityManagerInterface $em, Request $request) {

        // chceck if not logged in
        if( !$this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            // display message and redirect to homepage
            $this->addFlash('fail', 'You have to be logged in to edit comments');
            return $this->redirectToRoute('homepage'); 
        } 
        //get current user
        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();

        // check if user is not an author of comment 
        if ( $comment->getAuthor()->getId() != $currentUser->getId() ) 
        {
            // redirect to homepage with message
            $this->addFlash('fail', "You can't edit comments of other users");
            return $this->redirectToRoute('homepage');
        } 

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        // process form
        if ( $form->isSubmitted() && $form->isValid() ) 
        {
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('article.view', [
                'slug' => $comment->getPost()->getSlug(),
                "_fragment" => $comment->getId()
            ]);
        }

        // render veiw with form
        return $this->render('postView/index.html.twig', [
            'post' => $comment->getPost(),
            'form' => $form->createView()   
        ]);

     }

    /**
     * deletes the content of comment and replaces it with "deleted" message.
     * allows to delete comment by it's author
     * 
     * @Route("/comment/{id}/delete",
     * name="comment.delete",
     * methods={"POST", "GET"},
     * )
     * 
     * @param EntityManagerInterface $em
     * @param Comment $comment
     * @param Request $request
     * 
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $em, Request $request) {

        // check if not logged in
        if( !$this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            // display message and redirect to homepage
            $this->addFlash('fail', 'You have to be logged in to delete comments');
            return $this->redirectToRoute('homepage');
        }

        //get current user
        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        
        // check if user is not an author of comment
        if ( $comment->getAuthor()->getId() != $currentUser->getId() ) 
        {    
            // redirect to homepage with message
            $this->addFlash('fail', "You can't delete comments of other users");
            return $this->redirectToRoute('homepage');
        } 
            $comment->authorRemove();
            
            $em->persist($comment);
            $em->flush();

            //redirect to comment
            return $this->redirectToRoute('article.view', [
                'slug' => $comment->getPost()->getSlug(),
                "_fragment" => $comment->getId()
            ]);    
  
    }

    /**
     * 
     * deletes the content of comment and replaces it with "deleted" message
     * allow to delete all comments. Available only for ADMIN & SUPER_ADMIN
     * 
     * @Route("comment/{id}/delete/admin",
     * name="comment_admin_delete",
     * methods={"POST", "GET"},
     * )
     * 
     * 
     * @param EntityManagerInterface $em
     * @param Comment $comment
     * @param Request $request
     * 
     * @return Response
     */
    public function deleteCommentAdmin(Comment $comment, EntityManagerInterface $em, Request $request) { 
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $comment->adminRemove();

        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('article.view', [
            'slug' => $comment->getPost()->getSlug(),
            "_fragment" => $comment->getId()  
        ]);
    }
}