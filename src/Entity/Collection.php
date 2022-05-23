<?php

namespace App\Entity;

use App\Repository\CollectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CollectionRepository::class)
 */
class Collection
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
    private $nom_collection;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCollection(): ?string
    {
        return $this->nom_collection;
    }

    public function setNomCollection(string $nom_collection): self
    {
        $this->nom_collection = $nom_collection;

        return $this;
    }
}
