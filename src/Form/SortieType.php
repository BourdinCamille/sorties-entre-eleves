<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Nom de la sortie :',
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'required' => true,
                'label' => 'Date et heure de la sortie :',
                'widget' => 'single_text',
                /*'choice_translation_domain' => true,
                'format' =>'dd/MM/yyyy H:mm',
                'html5' => false,*/
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'required' => true,
                'label' => 'Date limite d\'inscription :',
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionsMax', NumberType::class, [
                'required' => true,
                'label' => 'Nombre de places :',
            ])
            ->add('duree', NumberType::class, [
                'required' => true,
                'label' => 'Durée (en minutes) :',
            ])
            ->add('infosSortie', TextareaType::class, [
                'required' => true,
                'label' => 'Description et infos :',
            ])
            ->add('lieu', EntityType::class, [
                'required' => true,
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => 'Lieu :',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
