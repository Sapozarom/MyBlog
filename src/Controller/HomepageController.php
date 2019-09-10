<?php

// src/Controller/HomepageController.php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostRepository;


class HomepageController extends AbstractController
{
   
    /**
     * Lists all published articles and displays homepage
     * 
     * @Route("/{page}", 
     * name="homepage",
     * methods="GET",
     * defaults={"page"= 1},
     * requirements={"page" = "\d+"}
     * )
     * 
     * @param PaginatorInterface $paginator
     * @param int $page
     * @param PostRepository $pr
     * 
     * @return Response
     */
    public function list(PaginatorInterface $paginator, int $page, PostRepository $pr) {   
        
        //list articles
        $posts = $paginator->paginate(
            $pr->findAllVisibleDesc(),
            $page,
            $this->getParameter('max_posts_on_homepage')
        );
        //paginator config
        $posts->setCustomParameters([
            'position' => 'centered',
            'size' => 'small',
        ]);
        
        return $this->render('homepage/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * Displays "About Us" section
     * 
     * @Route("/about-us", 
     * name="aboutUs",
     * methods="GET",
     * )
     * 
     * @return Response
     * 
     */
    public function aboutUs() {   

        return $this->render('homepage/aboutUs.html.twig');
    }

    /**
     * Displays "Contact" section
     * 
     * @Route("/contact", 
     * name="contact",
     * methods="GET",
     * )
     * 
     * @return Response
     */
    public function contact(){

        return $this->render('homepage/contact.html.twig');
    }

}
