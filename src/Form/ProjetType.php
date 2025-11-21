<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // champ pour le titre du projet
            ->add('titre', null, [
                'label' => 'Titre du projet',
            ])

            // champ pour inviter des membres (employés)
            ->add('employes', EntityType::class, [
                'class' => Employe::class,
                // ici j’affiche “Prénom Nom” dans la liste déroulante
                'choice_label' => function (Employe $e) {
                    return $e->getPrenom().' '.$e->getNom();
                },
                'multiple' => true,   // on peut choisir plusieurs employés
                'expanded' => false,  // affiché sous forme de <select>
                'required' => false,  // un projet peut exister sans membres
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}