<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
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
            'tittle' => 'Последние новости'
        ]);
    }

    /**
     * @Route("/post/{post}", name="page_post_show")
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function postShow(Post $post, PostRepository $repository)
    {
        return $this->render('page/action/post/show.html.twig', [
            'post' => $repository->findOneById($post),
            'tittle' => $post->getTittle()
        ]);
    }

    /**
     * @Route("/get-posts", name="page_get_posts")
     */
    public function getPosts(EntityManagerInterface $em)
    {
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=> "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36\r\n"
            )
        );

        $context = stream_context_create($opts);
        $html = HtmlDomParser::str_get_html(file_get_contents('https://www.rbc.ru', false, $context));

        foreach($html->find('a.main__feed__link') as $link) {
            $urlAttribute = 'data-vr-contentbox-url';
            $htmlNews = HtmlDomParser::str_get_html(file_get_contents($link->$urlAttribute, false, $context));

            foreach($htmlNews->find('div.l-col-main') as $article) {
                $post = new Post();
                $post->setPostsData($article);
                $em->persist($post);
                $em->flush();
            }
        }

        $this->addFlash('success', 'Новости успешно добавлены!');
        return $this->redirectToRoute('page_index');
    }

}