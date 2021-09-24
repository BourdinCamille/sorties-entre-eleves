<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, [
                'required' => true,
                'label' => 'Prénom : ',
            ])
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Nom : ',
            ])
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'Pseudo :'
            ])
            ->add('telephone', TelType::class, [
                'required' => false,
                'label' => 'Téléphone : ',
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email : ',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Mot de passe : '],
                'second_options' => ['label' => 'Confirmer le mot de passe : '],
                'invalid_message' => 'Les champs Mot de passe et Confirmation doivent être identiques !',
            ])
            ->add('campus',  EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'required' => true,
                'label' => 'Campus : ',
                'placeholder' => '-- choisir un campus --',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('isAdmin', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Utilisateur' => false,
                    'Administrateur' => true,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('isActive', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Actif' => true,
                    'Inactif' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
