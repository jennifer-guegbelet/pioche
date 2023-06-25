<?php


namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $livre = $options['livre'];
        $utilisateur = $options['utilisateur'];

        $builder
            ->add('livre', EntityType::class, [
                'class' => Livre::class,
                'choices' => [$livre],
                'choice_label' => 'titre',
                'disabled' => true,
                'required' => true,
            ])
            ->add('dateEmprunt', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => ['class' => 'datepicker'],
            ])
            ->add('dateRetourPrevue', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => ['class' => 'datepicker'],
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choices' => [$utilisateur],
                'choice_label' => 'username',
                'disabled' => true,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
            'livre' => null,
            'utilisateur' => null,
        ]);
    }
}
