<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('auteur')
            ->add('genre', EntityType::class, [
                'class' => 'App\Entity\Genre',
                'choice_label' => 'nom',
                'label' => 'Genre',
                // Ajoutez d'autres options selon vos besoins
            ])
            ->add('anneePublication')
            ->add('resume')
            ->add('isbn')
            ->add('langue')
            ->add('editeur')
            ->add('disponibilite')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
