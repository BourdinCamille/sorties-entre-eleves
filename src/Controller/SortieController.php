<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\AnnulationType;
use App\Form\SortieType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/creer", name="sortie_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $etat = $this->getDoctrine()->getManager()->getRepository(Etat::class)->findOneBy(['libelle' => 'Créée']);
        $organisateur = $this->getUser();
        $sortie->setEtat($etat);
        $sortie->setOrganisateur($organisateur);
        $sortie->setSiteOrganisateur($organisateur->getCampus());

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'La sortie a bien été créée !');

            return $this->redirectToRoute('home_home', [
            ]);
        }
        return $this->render('sortie/add.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

    /**
     * @Route("/sortie/{id}", name="sortie_detail", requirements={"id": "\d+"})
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function getSortie(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if(empty($sortie))
        {
            $this->addFlash('error', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('home_home');
        }
        else
        {
            return $this->render('sortie/detail.html.twig', [
                "sortie" => $sortie
            ]);
        }
    }

    /**
     * @Route("/sortie/modifier/{id}", name="sortie_modifier", requirements={"id" = "\d+"})
     */
    public function update(int $id, EntityManagerInterface $entityManager , Request $request)
    {
        $user = $this->getUser();
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if(empty($sortie))
        {
            $this->addFlash('error', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('home_home');
        }
        else
        {
            $organisateur = $sortie->getOrganisateur();
            if($organisateur !== $user || !$user->getIsAdmin())
            {
                $this->addFlash('error', 'Vous ne pouvez pas modifier cette sortie !');
                return $this->redirectToRoute('home_home', [
                ]);
            }
            else
            {
                $sortieForm = $this->createForm(SortieType::class, $sortie);
                $sortieForm->handleRequest($request);

                if($sortieForm->isSubmitted() && $sortieForm->isValid())
                {
                    $entityManager->persist($sortie);
                    $entityManager->flush();

                    $this->addFlash('success', 'La sortie a bien été modifiée !');

                    return $this->redirectToRoute('home_home', [
                    ]);
                }
            }
            return $this->render('sortie/update.html.twig', [
                'sortieForm' => $sortieForm->createView(),
                'sortie' => $sortie,
            ]);
        }
    }

    /**
     * @Route("/sortie/supprimer/{id}", name="sortie_supprimer", requirements={"id" = "\d+"})
     */
    public function delete(int $id, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if(empty($sortie))
        {
            $this->addFlash('error', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('home_home');
        }
        else
        {
            $organisateur = $sortie->getOrganisateur();
            if($organisateur !== $user || !$user->getIsAdmin())
            {
                $this->addFlash('error', 'Vous n\'avez pas l\'autorisation de supprimer cette sortie !');
                return $this->redirectToRoute('home_home', [
                ]);
            }
            else
            {
                $entityManager->remove($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'La sortie a bien été supprimée !');
                return $this->redirectToRoute('home_home');
            }
        }
    }

    /**
     * @Route("/sortie/publier/{id}", name="sortie_publier", requirements={"id" = "\d+"})
     */
    public function publish(int $id, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if(empty($sortie))
        {
            $this->addFlash('error', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('home_home');
        }
        else
        {
            $organisateur = $sortie->getOrganisateur();
            if($organisateur !== $user || !$user->getIsAdmin())
            {
                $this->addFlash('error', 'Vous n\'avez pas l\'autorisation de publier cette sortie !');
                return $this->redirectToRoute('home_home', [
                ]);
            }
            else
            {
                $etat = $this->getDoctrine()->getManager()->getRepository(Etat::class)->findOneBy(['libelle' => 'Ouverte']);
                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'La sortie vient d\'être publiée');
                return $this->redirectToRoute('home_home');
            }
        }
    }

    /**
     * @Route("/sortie/inscription/{id}", name="sortie_inscription", requirements={"id" = "\d+"})
     */
    public function inscription(int $id, EntityManagerInterface $entityManager)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        if(empty($sortie))
        {
            $this->addFlash('error', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('home_home');
        }
        else
        {
            $now = new DateTime('now');
            $user = $this->getUser();

            if($now < $sortie->getDateLimiteInscription() && $sortie->getEtat() == 'Ouverte' && $sortie->getNbInscriptionsMax() > count($sortie->getParticipants()))
            {
                $sortie->addParticipant($user);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Vous êtes bien inscrit(e) à cette sortie');
                return $this->redirectToRoute('home_home', [
                ]);
            }
            return $this->render('sortie/detail.html.twig', [
                "sortie" => $sortie
            ]);
        }
    }

    /**
     * @Route("/sortie/desistement/{id}", name="sortie_desistement", requirements={"id" = "\d+"})
     */
    public function desistement(int $id, EntityManagerInterface $entityManager)
    {
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if(empty($sortie))
        {
            $this->addFlash('error', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('home_home');
        }
        else
        {
            $now = new DateTime('now');
            $user = $this->getUser();

            if($now < $sortie->getDateHeureDebut())
            {
                $sortie->removeParticipant($user);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Vous êtes bien désincrit(e) de cette sortie');
                return $this->redirectToRoute('home_home', [
                ]);
            }
            return $this->render('sortie/detail.html.twig', [
                "sortie" => $sortie
            ]);
        }
    }

    /**
     * @Route("/sortie/annuler/{id}", name="sortie_annuler", requirements={"id" = "\d+"})
     */
    public function cancel(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $user = $this->getUser();
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if(empty($sortie))
        {
            $this->addFlash('error', 'Cette sortie n\'existe pas');
            return $this->redirectToRoute('home_home');
        }
        else
        {
            $organisateur = $sortie->getOrganisateur();

            $etat = $this->getDoctrine()->getManager()->getRepository(Etat::class)->findOneBy(['libelle' => 'Annulée']);

            if($organisateur !== $user || !$user->getIsAdmin())
            {
                $this->addFlash('error', 'Vous n\'avez pas l\'autorisation d\'accéder à cette page !');
                return $this->redirectToRoute('home_home', [
                ]);
            }
            else
            {
                $annulationForm = $this->createForm(AnnulationType::class, $sortie);
                $annulationForm->handleRequest($request);
                if($annulationForm->isSubmitted() && $annulationForm->isValid())
                {
                    $sortie->setEtat($etat);
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                    $this->addFlash('success', 'La sortie vient d\'être annulée');
                    return $this->redirectToRoute('home_home');
                }
            }
            return $this->render('sortie/cancel.html.twig', [
                'sortie' => $sortie,
                'annulationForm' => $annulationForm->createView(),
            ]);
        }
    }

}
