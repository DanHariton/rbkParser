<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $tittle;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $overview;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $content = [];

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $categories = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTittle(): ?string
    {
        return $this->tittle;
    }

    public function setTittle(string $tittle): self
    {
        $this->tittle = $tittle;

        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function setCategories(?array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function setPostsData($article)
    {
        if (isset($article->find('h1.article__header__title-in')[0]->plaintext)) {
            $this->setTittle(trim($article->find('h1.article__header__title-in')[0]->plaintext));
        }
        if (isset($article->find('div.article__text__overview')[0]->plaintext)) {
            $this->setOverview(trim($article->find('div.article__text__overview')[0]->plaintext));
        }
        if (isset($article->find('img.article__main-image__image')[0]->src)) {
            $this->setImage(trim($article->find('img.article__main-image__image')[0]->src));
        }
        $i = 0;
        foreach ($article->find('div.article__text p, ul, div.gallery_vertical') as $text) {
            $this->content[$i] = preg_replace('| +|', ' ', trim($text->plaintext));
            $i++;
        }
        if (isset($article->find('div.article__authors')[0]->plaintext)) {
            $this->author = preg_replace('| +|', ' ', trim($article->find('div.article__authors')[0]->plaintext));
        }
        $n = 0;
        foreach ($article->find('a.article__tags__link') as $category) {
            $this->categories[$n] = str_replace(', ', '', $category->plaintext);
            $n++;
        }
    }
}
