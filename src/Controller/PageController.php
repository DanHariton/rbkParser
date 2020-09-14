<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\PostLoader;
use Doctrine\ORM\EntityManagerInterface;
use KubAT\PhpSimple\HtmlDomParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="page_index")
     */
    public function indexAction()
    {
        return $this->render('page/action/index.html.twig');
    }

    /**
     * @Route("/posts-list", name="page_posts_list")
     */
    public function postsList(PostRepository $repository)
    {
        return $this->render('page/action/post/list.html.twig', [
            'posts' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/post/{post}", name="page_post_show")
     */
    public function postShow(Post $post)
    {
        return $this->render('page/action/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/get-posts", name="page_get_posts")
     */
    public function getPosts(PostLoader $postLoader)
    {
        $postLoader->load();

        $this->addFlash('success', 'Новости успешно добавлены!');
        return $this->redirectToRoute('page_index');
    }

}