<?php

namespace App\Form;

use App\Entity\Emprunt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add('dateEmprunt', DateTimeType::class, [
                'label' => 'Date',
                'data' => (new \DateTime()),
                'attr' => [
                    'readonly' => 'readonly',
                ],
            ])
            ->add('dateRetour', DateTimeType::class, [
                'label' => 'Date',
                'data' => (new \DateTime())->modify('+1 month'),
                'attr' => [
                    'readonly' => 'readonly',
                ],
            ])
            ->add('utilisateur', HiddenType::class, [
                'data' => $options['utilisateur'] ? $options['utilisateur']->getId() : null, // Récupérer l'utilisateur en tant que valeur par défaut du champ caché
            ])
            ->add('livre', HiddenType::class, [
                'data' => $options['livre'] ? $options['livre']->getId() : null, // Récupérer le livre en tant que valeur par défaut du champ caché
            ])
            ->add('exemplaire', HiddenType::class, [
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
            'livre' => null, // Définir l'option 'livre' avec une valeur par défaut null
            'utilisateur' => null, // Définir l'option 'utilisateur' avec une valeur par défaut null
        ]);
    }
}
