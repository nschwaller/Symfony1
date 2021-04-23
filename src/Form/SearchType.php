<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

//formulaire crée à la main
Class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('string', TextType::class,[
                //permet de ne pas avoir de label
                'label'=>false,
                //permet de définir que l'input ne soit pas obligatoire
                'required'=> false,
                'attr'=> [
                    'placeholder' => 'Votre recherche ...',
                    'class'=> 'form-control-sm'
                ]
            ])
            //pour affiché une table il faut utilisé le type entity
            ->add('categories', EntityType::class,[
                'label'=>false,
                'required'=> false,
                'class'=>Category::class,
                //permet de séléctionner plusieur valeur
                'multiple'=>true,
                //avoir une vue en checkbox
                'expanded'=>true
            ])
            ->add('submit',SubmitType::class, [
                'label'=> 'Filtrer',
                //permet d'avoir une classe dans notre bouton
                'attr'=> [
                    'class' => 'btn-block btn-info'
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //classe utilisé par notre formulaire
            'data_class' => Search::class,
            //methode qu'on choisi on met en get pour que les utilisateur puisse transmettre leur lien
            'method' => 'GET',
            //comme on est dans un formulaire de recherche, on a pas forcement besoin de sécurité
            'crsf_protection' => false,

        ]);
    }

    //Permet d'avoir une url clean
    public function getBlockPrefix(){
        return '';
    }

}