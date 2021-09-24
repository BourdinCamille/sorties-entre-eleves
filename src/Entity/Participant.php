<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(
 *     fields={"username"}, message="Ce pseudo n'est pas disponible")
 * @UniqueEntity(
 *     fields={"email"}, message="Il existe déjà un compte associé à cette adresse email")
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\NotBlank(message="Merci de renseigner un nom")
     * @Assert\Length(max=80, maxMessage="Ce champ ne peut pas excéder 80 caractères")
     * @ORM\Column(type="string", length=80)
     */
    private string $nom;

    /**
     * @Assert\NotBlank(message="Merci de renseigner un prénom")
     * @Assert\Length(max=80, maxMessage="Ce champ ne peut pas excéder 80 caractères")
     * @ORM\Column(type="string", length=80)
     */
    private string $prenom;

    /**
     * @Assert\NotBlank(message="Merci de renseigner un pseudo")
     * @Assert\Length(max=80, maxMessage="Ce champ ne peut pas excéder 80 caractères")
     * @ORM\Column(type="string", length=80, unique=true)
     */
    private string $username;

    /**
     * @Assert\Length(min=10, minMessage="Ce champ doit être composé d'au moins 10 caractères", max=14, maxMessage="Ce champ doit être composé au maximum de 14 caractères")
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private ?string $telephone = null;

    /**
     * @Assert\NotBlank(message="Merci de renseigner un email")
     * @Assert\Length(max=80, maxMessage="Ce champ ne peut pas excéder 80 caractères")
     * @Assert\Email(message="{{ value }} n'est pas un email valide")
     * @ORM\Column(type="string", length=80, unique=true)
     */
    private string $email;

    /**
     * @Assert\Regex(
     *     pattern="#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$#",
     *     match=true,
     *     message="Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial")
     * @Assert\NotBlank(message="Merci de choisir un mot de passe")
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isAdmin = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActive = true;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $photo = null;

    /**
     * @ORM\JoinColumn(nullable= false)
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="participants")
     */
    private Campus $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="participants")
     */
    private $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone(string $telephone): void
    {
        if($telephone != null)
        {
            $this->telephone = $telephone;
        }
        else
        {
            $this->telephone = null;
        }
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        if($password == null)
        {
            dump('Same as the old password');
        }
        else
        {
            $this->password = $password;
        }
    }

    /**
     * @return bool
     */
    public function getIsAdmin():bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string|null $photo
     */
    public function setPhoto(string $photo = null): void
    {
        if($photo != null)
        {
            $this->photo = $photo;
        }
        else
        {
            $this->photo = null;
        }
    }

    /**
     * @return Campus
     */
    public function getCampus(): Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus $campus
     */
    public function setCampus(Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSortie(Sortie $sortie): self
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties[] = $sortie;
            $sortie->addParticipant($this);
        }
        return $this;
    }

    public function removeSortie(Sortie $sortie): self
    {
        if ($this->sorties->removeElement($sortie)) {
            $sortie->removeParticipant($this);
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->username;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(){}

}
