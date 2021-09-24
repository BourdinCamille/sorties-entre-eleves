<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Length(max=50, maxMessage="Ce champ ne peut pas excéder 50 caractères")
     * @ORM\Column(type="string", length=50)
     */
    private string $nom;

    /**
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Length(min=5, max=5, maxMessage="Le code postal doit comporter 5 chiffres sans espace")
     * @ORM\Column(type="string", length=5)
     */
    private string $codePostal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lieu", mappedBy="ville", cascade={"remove"})
     */
    private $lieux;

    public function __construct()
    {
        $this->lieux = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getCodePostal(): string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): void
    {
        $this->codePostal = $codePostal;
    }

    public function getLieux(): ArrayCollection
    {
        return $this->lieux;
    }

    public function setLieux(ArrayCollection $lieux): void
    {
        $this->lieux = $lieux;
    }

    public function __toString(): string
    {
        return $this->nom;
    }

}
