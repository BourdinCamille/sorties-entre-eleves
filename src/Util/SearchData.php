<?php


namespace App\Util;

use App\Entity\Campus;
use DateTime;


class SearchData
{
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Campus
     */
    public $campusSelectionne;

    /**
     * @var null|DateTime
     */
    public $dateMin;

    /**
     * @var null|DateTime
     */
    public $dateMax;

    /**
     * @var bool
     */
    public $isInscrit = false;

    /**
     * @var bool
     */
    public $isNotInscrit = false;

    /**
     * @var bool
     */
    public $isOrganisateur = false;

    /**
     * @var bool
     */
    public $isPassee = false;

    /**
     * @return string
     */
    public function getQ(): string
    {
        return $this->q;
    }

    /**
     * @param string $q
     */
    public function setQ(string $q): void
    {
        $this->q = $q;
    }

    /**
     * @return DateTime|null
     */
    public function getDateMin(): ?DateTime
    {
        return $this->dateMin;
    }

    /**
     * @param DateTime|null $dateMin
     */
    public function setDateMin(?DateTime $dateMin): void
    {
        $this->dateMin = $dateMin;
    }

    /**
     * @return DateTime|null
     */
    public function getDateMax(): ?DateTime
    {
        return $this->dateMax;
    }

    /**
     * @param DateTime|null $dateMax
     */
    public function setDateMax(?DateTime $dateMax): void
    {
        $this->dateMax = $dateMax;
    }

    /**
     * @return bool
     */
    public function isInscrit(): bool
    {
        return $this->isInscrit;
    }

    /**
     * @param bool $isInscrit
     */
    public function setIsInscrit(bool $isInscrit): void
    {
        $this->isInscrit = $isInscrit;
    }

    /**
     * @return bool
     */
    public function isNotInscrit(): bool
    {
        return $this->isNotInscrit;
    }

    /**
     * @param bool $isNotInscrit
     */
    public function setIsNotInscrit(bool $isNotInscrit): void
    {
        $this->isNotInscrit = $isNotInscrit;
    }

    /**
     * @return bool
     */
    public function isOrganisateur(): bool
    {
        return $this->isOrganisateur;
    }

    /**
     * @param bool $isOrganisateur
     */
    public function setIsOrganisateur(bool $isOrganisateur): void
    {
        $this->isOrganisateur = $isOrganisateur;
    }

    /**
     * @return bool
     */
    public function isPassee(): bool
    {
        return $this->isPassee;
    }

    /**
     * @param bool $isPassee
     */
    public function setIsPassee(bool $isPassee): void
    {
        $this->isPassee = $isPassee;
    }

    public function __toString()
    {
        return $this->q;
    }

}