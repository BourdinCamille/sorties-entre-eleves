<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\NotBlank(message="Veuillez choisir un nom pour la sortie")
     * @Assert\Length(max=50, maxMessage="Ce champ ne peut pas excéder 50 caractères")
     * @ORM\Column(type="string", length=50)
     */
    private string $nom;

    /**
     * @Assert\NotBlank(message="Veuillez définir la date et l'heure de début")
     * @Assert\GreaterThan("today UTC", message="Oups ! La machine à remonter le temps n'existe pas")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @Assert\NotBlank(message="Veuillez définir une durée pour la sortie")
     * @Assert\GreaterThanOrEqual(30, message="La durée de la sortie ne peut pas être inférieure à 30 minutes")
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $duree;

    /**
     * @Assert\NotBlank(message="Veuillez choisir une date limite d'inscription")
     * @Assert\LessThan(propertyPath="dateHeureDebut", message="La date limite d'inscription doit être antérieure à la date de la sortie")
     * @Assert\GreaterThan("today UTC", message="Oups ! La machine à remonter le temps n'existe pas")
     * @ORM\Column(type="datetime")
     */
    private $dateLimiteInscription;

    /**
     * @Assert\NotBlank(message="Veuillez choisir un nombre maximum de participants")
     * @Assert\Positive(message="Le nombre saisi doit être positif !")
     * @ORM\Column(type="integer")
     */
    private int $nbInscriptionsMax;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $infosSortie = null;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="App\Entity\Etat", inversedBy="sorties")
     */
    private Etat $etat;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieu", inversedBy="sorties")
     */
    private Lieu $lieu;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="sorties")
     */
    private Campus $siteOrganisateur;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="App\Entity\Participant")
     */
    private Participant $organisateur;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, inversedBy="sorties")
     */
    private $participants;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $motifAnnulation = null;


    public function __construct()
    {
        $this->participants = new ArrayCollection();
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

    public function getDateHeureDebut()
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut($dateHeureDebut): void
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function getDateLimiteInscription()
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription($dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    public function getNbInscriptionsMax(): int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): void
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(string $infosSortie): void
    {
        if($infosSortie != null)
        {
            $this->infosSortie = $infosSortie;
        }
        else
        {
            $this->infosSortie = null;
        }
    }

    public function getEtat(): Etat
    {
        return $this->etat;
    }

    public function setEtat(Etat $etat): void
    {
        $this->etat = $etat;
    }

    public function getLieu(): Lieu
    {
        return $this->lieu;
    }

    public function setLieu(Lieu $lieu): void
    {
        $this->lieu = $lieu;
    }

    public function getSiteOrganisateur(): Campus
    {
        return $this->siteOrganisateur;
    }

    public function setSiteOrganisateur(Campus $siteOrganisateur): void
    {
        $this->siteOrganisateur = $siteOrganisateur;
    }

    public function getOrganisateur(): Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(Participant $organisateur): void
    {
        $this->organisateur = $organisateur;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if(!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }
        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        $this->participants->removeElement($participant);
        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    public function setMotifAnnulation(string $motifAnnulation): void
    {
        if($motifAnnulation != null)
        {
            $this->motifAnnulation = $motifAnnulation;
        }
        else
        {
            $this->motifAnnulation = null;
        }
    }

    public function __toString(): string
    {
        return $this->nom;
    }

}
