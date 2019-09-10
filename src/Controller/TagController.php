<?php

// src/Controller/TagController.php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TagRepository;
use App\Repository\PostRepository;

class TagController extends AbstractController
{
    /**
     * Finds all active tags for tag cloud
     * 
     * @Route("/tags", name="tags")
     * 
     * @param TagRepository $tr
     * 
     * @return Response
     */
    public function list(TagRepository $tr) {   

        $tags = $tr->findActive();

        return $this->render('homepage/layout/tagList.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * Lists all articles under this tag.
     * 
     * @Route("/tag/{id}/articles", 
     * name="tag.articles",
     * methods={"POST", "GET"},
     * )
     * 
     * @param Tag $tag
     * 
     * @return Response
     * 
     */
    public function listPostsUnderTag(Tag $tag) {
        
        $posts = $tag->getPosts();

        return $this->render('homepage/tags.html.twig', [
            'posts' => $posts,
            'tag' => $tag,
        ]);

    }
}
