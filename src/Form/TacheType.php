<?php

namespace App\Form;

use App\Entity\Tache;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la tâche',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('dateEcheance', DateType::class, [
                'label' => 'Date',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'required' => true,
                'choices' => [
                    'To Do'  => 'todo',
                    'Doing'  => 'doing',
                    'Done'   => 'done',
                ],
            ])
            // choice_label permet d'afficher le texte dans la liste déroulante.
            // Symfony passe chaque employé dans la petite fonction (fn(Employe $e)).
            // $e représente un employé.
            // Ici on affiche : prénom + espace + nom.
            ->add('employeAssigne', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => fn(Employe $e) => $e->getPrenom().' '.$e->getNom(),
                'required' => false,
                'placeholder' => '',
                'label' => 'Membre',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}
