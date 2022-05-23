<?php

namespace App\Entity;

use App\Repository\CollectionArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CollectionArticleRepository::class)
 */
class CollectionArticle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom_collection_article;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="collectionArticle")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCollectionArticle(): ?string
    {
        return $this->nom_collection_article;
    }

    public function setNomCollectionArticle(string $nom_collection_article): self
    {
        $this->nom_collection_article = $nom_collection_article;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCollectionArticle($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCollectionArticle() === $this) {
                $article->setCollectionArticle(null);
            }
        }

        return $this;
    }
}
