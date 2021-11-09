<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Util\SearchData;
use App\Util\HandlingDates;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route ("/accueil", name="home_home")
     * @param EntityManagerInterface $em
     * @param SortieRepository $sortieRepo
     * @param EtatRepository $etatRepo
     * @param HandlingDates $hd
     * @param Request $request
     * @return Response
     */
    public function home(EntityManagerInterface $em,
                         SortieRepository $sortieRepo,
                         EtatRepository $etatRepo,
                         HandlingDates $hd,
                         Request $request): Response

    {
        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        // On va faire des comparaisons pour changer l'état de la sortie en fonction de la date du jour
        $currentDate = new DateTime('now');
        $currentDate = $hd->formatDateToCompare($currentDate);

        // On récupère toutes les sorties jusqu'à il y a un mois et différents états
        $sorties = $sortieRepo->findAllUntilLastMonth();
        $creee = $etatRepo->findOneBy(['libelle' => 'Créée']);
        $cloture = $etatRepo->findOneBy(['libelle' => 'Clôturée']);
        $enCours = $etatRepo->findOneBy(['libelle' => 'Activité en cours']);
        $passe = $etatRepo->findOneBy(['libelle' => 'Passée']);
        $annule = $etatRepo->findOneBy(['libelle' => 'Annulée']);

        foreach ($sorties as $sortie)
        {
            $dateLimiteInscription = $hd->formatDateToCompare($sortie->getDateLimiteInscription());
            $dateHeureDebut = $hd->formatDateToCompare($sortie->getDateHeureDebut());
            $dateHeureFin = $hd->addMinutesToDate($dateHeureDebut, $sortie->getDuree());
            $etat = $sortie->getEtat();

            if($etat != $creee && $etat != $annule && $dateLimiteInscription <= $currentDate)
            {
                $sortie->setEtat($cloture);
                $em->persist($sortie);
            }
            if($etat != $creee && $etat != $annule && $dateHeureDebut <= $currentDate && $dateHeureFin > $currentDate)
            {
                $sortie->setEtat($enCours);
                $em->persist($sortie);
            }
            if($dateHeureFin <= $currentDate)
            {
                $sortie->setEtat($passe);
                $em->persist($sortie);
            }
            $em->flush();
        }

        // On prépare les filtres de recherche et on effectue des vérifications sur la cohérence de l'intervalle de dates
        $data = new SearchData();
        $searchForm = $this->createForm(SearchType::class, $data);
        $searchForm->handleRequest($request);

        if($searchForm->isSubmitted() && $searchForm->isValid())
        {
            $dateMin = $searchForm->get('dateMin')->getData();
            $dateMax = $searchForm->get('dateMax')->getData();

            if($dateMin == null && $dateMax == null)
            {
                $sorties = $sortieRepo->findSearch($data, $user);
                if(sizeof($sorties) == 0)
                {
                    $this->addFlash('warning', 'Aucun résultat ne correspond à votre recherche !');
                }
            }
            else if($dateMin == null && $dateMax != null ||
                $dateMin != null && $dateMax == null)
            {
                $this->addFlash('warning', 'Veuillez choisir un intervalle de dates');
            }
            else if($dateMax != null && $dateMin != null && $dateMax < $dateMin)
            {
                $this->addFlash('warning', 'La date de fin ne peut pas être antérieure à la date de début');
            }
            else
            {
                $sorties = $sortieRepo->findSearch($data, $user);
                if(sizeof($sorties) == 0)
                {
                    $this->addFlash('warning', 'Aucun résultat ne correspond à votre recherche !');
                }
            }
        }
        return $this->render('home/home.html.twig', [
            'currentDate' => $currentDate,
            'sorties' => $sorties,
            'searchForm' => $searchForm->createView(),
        ]);
    }

}
