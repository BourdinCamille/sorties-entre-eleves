<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"nom"}, message="Il existe déjà un campus à ce nom")
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\NotBlank(message="Merci de renseigner le nom du campus")
     * @Assert\Length(max=50, maxMessage="50 caractères maximum")
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private string $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participant", mappedBy="campus", cascade={"remove"})
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="siteOrganisateur")
     */
    private $sorties;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->sorties = new ArrayCollection();
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

    public function getParticipants(): ArrayCollection
    {
        return $this->participants;
    }

    public function setParticipants(ArrayCollection $participants): void
    {
        $this->participants = $participants;
    }

    public function getSorties(): ArrayCollection
    {
        return $this->sorties;
    }

    public function setSorties(ArrayCollection $sorties): void
    {
        $this->sorties = $sorties;
    }

    public function __toString(): string
    {
        return $this->nom;
    }

}
