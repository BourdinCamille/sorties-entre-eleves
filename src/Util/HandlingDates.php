<?php


namespace App\Util;

use DateTime;

class HandlingDates
{
    // Prend un objet de type DateTime en argument et le retourne au format prédéfini
    public function formatDateToCompare(DateTime $date)
    {
        $format = 'Y-m-d H:i';
        $dateFormatee = $date;
        return $dateFormatee->format($format);
    }

    // Permet d'ajouter des minutes à un objet de type DateTime et retourne le résultat au format prédéfini
    public function addMinutesToDate(string $date, int $duree)
    {
        $dureeCalculee = '+'. $duree . 'minutes';
        $dateHeureFin = strtotime($date . $dureeCalculee);
        return date('Y-m-d H:i', $dateHeureFin);
    }

}