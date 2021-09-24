<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\NotBlank(message="Veuillez saisir un nom de lieu")
     * @Assert\Length(max=50, maxMessage="Ce champ ne peut pas excéder 50 caractères")
     * @ORM\Column(type="string", length=50)
     */
    private string $nom;

    /**
     * @Assert\Length(max=70, maxMessage="Ce champ ne peut pas excéder 70 caractères")
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private string $rue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $latitude = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $longitude = null;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="lieux")
     */
    private Ville $ville;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="lieu", cascade={"remove"})
     */
    private $sorties;

    public function __construct()
    {
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

    public function getRue(): string
    {
        return $this->rue;
    }

    public function setRue(string $rue): void
    {
        $this->rue = $rue;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        if($latitude != null)
        {
            $this->latitude = $latitude;
        }
        else
        {
            $this->latitude = null;
        }
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        if($longitude != null)
        {
            $this->longitude = $longitude;
        }
        else
        {
            $this->longitude = null;
        }
    }

    public function getVille(): Ville
    {
        return $this->ville;
    }

    public function setVille(Ville $ville): void
    {
        $this->ville = $ville;
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
