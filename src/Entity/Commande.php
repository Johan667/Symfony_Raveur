<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    const DEVISE = 'euros';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_total;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombre_etoile;

    /**
     * @ORM\Column(type="date")
     */
    private $date_commande;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $adresse_livraison;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private $cp_livraison;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ville_livraison;

    /**
     * @ORM\Column(type="string", length=42)
     */
    private $pays_livraison;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="Commandes")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=Stocker::class, mappedBy="commande")
     */
    private $stockers;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $devise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripe_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brand_stripe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last4_stripe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $id_charge_stripe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status_stripe;

    public function __construct()
    {
        $this->stockers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prix_total;
    }

    public function setPrixTotal(float $prix_total): self
    {
        $this->prix_total = $prix_total;

        return $this;
    }

    public function getNombreEtoile(): ?int
    {
        return $this->nombre_etoile;
    }

    public function setNombreEtoile(?int $nombre_etoile): self
    {
        $this->nombre_etoile = $nombre_etoile;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $date_commande): self
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresse_livraison;
    }

    public function setAdresseLivraison(string $adresse_livraison): self
    {
        $this->adresse_livraison = $adresse_livraison;

        return $this;
    }

    public function getCpLivraison(): ?string
    {
        return $this->cp_livraison;
    }

    public function setCpLivraison(string $cp_livraison): self
    {
        $this->cp_livraison = $cp_livraison;

        return $this;
    }

    public function getVilleLivraison(): ?string
    {
        return $this->ville_livraison;
    }

    public function setVilleLivraison(string $ville_livraison): self
    {
        $this->ville_livraison = $ville_livraison;

        return $this;
    }

    public function getPaysLivraison(): ?string
    {
        return $this->pays_livraison;
    }

    public function setPaysLivraison(string $pays_livraison): self
    {
        $this->pays_livraison = $pays_livraison;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
            $stocker->setCommande($this);
        }

        return $this;
    }

    public function removeStocker(Stocker $stocker): self
    {
        if ($this->stockers->removeElement($stocker)) {
            // set the owning side to null (unless already changed)
            if ($stocker->getCommande() === $this) {
                $stocker->setCommande(null);
            }
        }

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): self
    {
        $this->devise = $devise;

        return $this;
    }

    public function getStripeToken(): ?string
    {
        return $this->stripe_token;
    }

    public function setStripeToken(?string $stripe_token): self
    {
        $this->stripe_token = $stripe_token;

        return $this;
    }

    public function getBrandStripe(): ?string
    {
        return $this->brand_stripe;
    }

    public function setBrandStripe(?string $brand_stripe): self
    {
        $this->brand_stripe = $brand_stripe;

        return $this;
    }

    public function getLast4Stripe(): ?string
    {
        return $this->last4_stripe;
    }

    public function setLast4Stripe(?string $last4_stripe): self
    {
        $this->last4_stripe = $last4_stripe;

        return $this;
    }

    public function getIdChargeStripe(): ?string
    {
        return $this->id_charge_stripe;
    }

    public function setIdChargeStripe(?string $id_charge_stripe): self
    {
        $this->id_charge_stripe = $id_charge_stripe;

        return $this;
    }

    public function getStatusStripe(): ?string
    {
        return $this->status_stripe;
    }

    public function setStatusStripe(?string $status_stripe): self
    {
        $this->status_stripe = $status_stripe;

        return $this;
    }
}
