<?php


namespace App\Form;

use App\Util\SearchData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // Version dropdown
            ->add('campusSelectionne', EntityType::class, [
                'class' => Campus::class,
                'label' => 'Campus',
                'mapped' => true,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '-- Choisir le campus --',
                'required' => false,
            ])
            // Version radio buttons
            /*->add('campusSelectionne', EntityType::class, [
                'label' => 'Veuillez sélectionner un campus :',
                'required' => false,
                'class' => Campus::class,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
            ])*/
            ->add('q', TextType::class, [
                'label' => 'Le nom de la sortie contient :',
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('dateMin', DateType::class, [
                'label' => 'Entre',
                'widget' => 'single_text'
            ])
            ->add('dateMax', DateType::class, [
                'label' => 'et',
                'widget' => 'single_text'
            ])
            ->add('isOrganisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])
            ->add('isInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit(e)',
                'required' => false,
            ])
            ->add('isNotInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit(e)',
                'required' => false,
            ])
            ->add('isPassee', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}