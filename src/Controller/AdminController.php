<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/liste", name="liste_participants")
     * @param ParticipantRepository $participantRepo
     * @return Response
     */
    public function getListeParticipants(ParticipantRepository $participantRepo): Response
    {
        $participants = $participantRepo->findAll();

        return $this->render('admin/liste_participants.html.twig', [
            'participants' => $participants,
        ]);
    }

    /**
     * @Route("/participant/supprimer/{id}", name="participant_supprimer", requirements={"id" = "\d+"})
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function supprimerParticipant(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant = $participantRepo->find($id);
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortieRepo->findAll();

        if($user->getIsAdmin(true))
        {
            foreach($sorties as $sortie)
            {
                if($sortie->getOrganisateur() === $participant)
                {
                    $this->addFlash('error', $participant->getUsername() .' ne peut pas être supprimé car il est organisateur d\'au moins une sortie !');
                    return $this->redirectToRoute('liste_participants', [
                    ]);
                }
                else
                {
                    $participant->removeSortie($sortie);
                }
            }
            $entityManager->remove($participant);
            $entityManager->flush();
            $this->addFlash('success', 'Le participant ' . $participant->getUsername() . ' a bien été supprimé !');
            return $this->redirectToRoute('liste_participants');
        }
        else
        {
            $this->addFlash('error', 'vous n\'avez pas l\'autorisation pour supprimer un participant !');
            return $this->redirectToRoute('home_home', [
            ]);
        }
    }

    /**
     * @Route("/participant/desactiver/{id}", name="participant_desactiver", requirements={"id" = "\d+"})
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function desactiverParticipant(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant = $participantRepo->find($id);
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $sortieRepo->findAll();

        if($user->getIsAdmin(true))
        {
            foreach($sorties as $sortie)
            {
                if($sortie->getOrganisateur() === $participant)
                {
                    $this->addFlash('error', $participant->getUsername() .' ne peut pas être désactivé car il est organisateur d\'au moins une sortie !');
                    return $this->redirectToRoute('liste_participants', [
                    ]);
                }
                else
                {
                    $participant->setIsActive(false);
                }
            }
            $entityManager->persist($participant);
            $entityManager->flush();
            $this->addFlash('success', 'Le participant ' . $participant->getUsername() . ' a bien été désactivé !');
            return $this->redirectToRoute('liste_participants');
        }
        else
        {
            $this->addFlash('error', 'Nous n\'avez pas l\'autorisation pour désactiver un participant !');
            return $this->redirectToRoute('home_home', [
            ]);
        }
    }

    /**
     * @Route("/participant/activer/{id}", name="participant_activer", requirements={"id" = "\d+"})
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function activerParticipant(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant = $participantRepo->find($id);

        if($user->getIsAdmin(true))
        {
            $participant->setIsActive(true);
            $entityManager->persist($participant);
            $entityManager->flush();
            $this->addFlash('success', 'Le participant ' . $participant->getUsername() . ' a bien été activé !');
            return $this->redirectToRoute('liste_participants');
        }
        else
        {
            $this->addFlash('error', 'Vous n\'avez pas l\'autorisation pour activer un participant !');
            return $this->redirectToRoute('home_home', [
            ]);
        }
    }
}
