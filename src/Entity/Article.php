<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $denomination;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $slug;

    /**
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $taille;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $nouveau;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tendance;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=CollectionArticle::class, inversedBy="articles")
     */
    private $collectionArticle;

    /**
     * @ORM\ManyToMany(targetEntity=Taille::class, mappedBy="articles")
     */
    private $tailles;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="articles")
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Stocker::class, mappedBy="articles")
     */
    private $stockers;

    public function __construct()
    {
        $this->tailles = new ArrayCollection();
        $this->stockers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDenomination(): ?string
    {
        return $this->denomination;
    }

    public function setDenomination(string $denomination): self
    {
        $this->denomination = $denomination;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function isNouveau(): ?bool
    {
        return $this->nouveau;
    }

    public function setNouveau(?bool $nouveau): self
    {
        $this->nouveau = $nouveau;

        return $this;
    }

    public function isTendance(): ?bool
    {
        return $this->tendance;
    }

    public function setTendance(?bool $tendance): self
    {
        $this->tendance = $tendance;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCollectionArticle(): ?CollectionArticle
    {
        return $this->collectionArticle;
    }

    public function setCollectionArticle(?CollectionArticle $collectionArticle): self
    {
        $this->collectionArticle = $collectionArticle;

        return $this;
    }

    /**
     * @return Collection<int, Taille>
     */
    public function getTailles(): Collection
    {
        return $this->tailles;
    }

    public function addTaille(Taille $taille): self
    {
        if (!$this->tailles->contains($taille)) {
            $this->tailles[] = $taille;
            $taille->addArticle($this);
        }

        return $this;
    }

    public function removeTaille(Taille $taille): self
    {
        if ($this->tailles->removeElement($taille)) {
            $taille->removeArticle($this);
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Stocker>
     */
    public function getStockers(): Collection
    {
        return $this->stockers;
    }

    public function addStocker(Stocker $stocker): self
    {
        if (!$this->stockers->contains($stocker)) {
            $this->stockers[] = $stocker;
            $stocker->setArticles($this);
        }

        return $this;
    }

    public function removeStocker(Stocker $stocker): self
    {
        if ($this->stockers->removeElement($stocker)) {
            // set the owning side to null (unless already changed)
            if ($stocker->getArticles() === $this) {
                $stocker->setArticles(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->denomination;
    }


}
