<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/mon_profil", name="my_profil")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function getMyProfile(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $profil = $this->getUser();
        $photoExistante = $profil->getPhoto();
        $profilForm = $this->createForm(ProfilType::class, $profil);

        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted() && $profilForm->isValid())
        {
            $hashed = $encoder->encodePassword($profil, $profil->getPassword());
            $profil->setPassword($hashed);
            $photo = $profilForm->get('photo')->getData();
            if($photo != null)
            {
                $file = md5(uniqid()).'.'.$photo->guessExtension();
                $photo->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                $profil->setPhoto($file);
            }
            else
            {
                $profil->setPhoto($photoExistante);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profil);
            $entityManager->flush();
            $this->addFlash('success', 'les modifications ont bien été enregistrées');
            return $this->redirectToRoute('home_home');
        }
        $this->getDoctrine()->getManager()->refresh($profil);

        return $this->render('profil/mon_profil.html.twig', [
            'profilForm' => $profilForm->createView(),
        ]);
    }

    /**
     * @Route("/profil_participant/{id}", name="profil_participant", requirements={"id" = "\d+"})
     * @param int $id
     * @return Response
     */
    public function getOtherMemberProfile(int $id): Response
    {
        $profilRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participant = $profilRepo->find($id);

        if(empty($participant))
        {
            $this->addFlash('error', 'Ce participant n\'existe pas');
            return $this->redirectToRoute('home_home');
        }
        else
        {
            return $this->render('profil/profil.html.twig', [
                'participant' => $participant,
            ]);
        }
    }

}
