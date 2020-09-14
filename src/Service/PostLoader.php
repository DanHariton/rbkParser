<?php


namespace App\Service;


use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use KubAT\PhpSimple\HtmlDomParser;

class PostLoader
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load()
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
                $this->em->persist($this->createPostFromArticle($article));
                $this->em->flush();
            }
        }
    }

    private function createPostFromArticle($article)
    {
        $post = new Post();

        if (isset($article->find('.article__header__title-in, .article__header__title')[0]->plaintext)) {
            $post->setTittle(trim($article->find('.article__header__title-in, .article__header__title')[0]->plaintext));
        }
        if (isset($article->find('div.article__text__overview')[0]->plaintext)) {
            $post->setOverview(trim($article->find('div.article__text__overview')[0]->plaintext));
        }
        if (isset($article->find('img.article__main-image__image')[0]->src)) {
            $post->setImage(trim($article->find('img.article__main-image__image')[0]->src));
        }
        $post->setContent(array_map(function ($text) {
            return preg_replace('| +|', ' ', trim($text->plaintext));
        }, $article->find('div.article__text p, ul, div.gallery_vertical')));
        if (isset($article->find('div.article__authors')[0]->plaintext)) {
            $post->setAuthor(preg_replace('| +|', ' ', trim($article->find('div.article__authors')[0]->plaintext)));
        }
        $post->setCategories(array_map(function ($category) {
            return str_replace(', ', '', $category->plaintext);
        }, $article->find('a.article__tags__link')));

        return $post;
    }
}