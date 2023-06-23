<?php
// src/Form/ReservationType.php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('utilisateur', HiddenType::class, [
                'data' => $options['utilisateur'] ? $options['utilisateur']->getId() : null, // Récupérer l'utilisateur en tant que valeur par défaut du champ caché
            ])
            ->add('livre', HiddenType::class, [
                'data' => $options['livre'] ? $options['livre']->getId() : null, // Récupérer le livre en tant que valeur par défaut du champ caché
            ])
            ->add('reserve', SubmitType::class, [
                'label' => 'Réserver',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'livre' => null, // Définir l'option 'livre' avec une valeur par défaut null
            'utilisateur' => null, // Définir l'option 'utilisateur' avec une valeur par défaut null
        ]);
    }
}
