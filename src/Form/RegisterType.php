<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Votre prénom'
                /*'attr' => [
                    'placeholder' => "merci d'écrire votre prénom" //ne pas oublier la virgule après label
                ]
                */
            ])
            ->add ('nom', TextType::class, [
                'label' => 'Votre nom'
            ])
            ->add('email',EmailType::class, [
                'label' => 'Votre email'
            ])
            // RepeatedType permet de dire que c'est une donnée qui doit être répété dans le formulaire, symfony gère automatiquement le fait que les deux labels doivent être identique quand l'utilisateur saisi quelque chose
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être idetique',
                'required' => true,
                'first_options'=> ['label' => 'Votre mot de passe'],
                'second_options'=> ['label' => 'Confirmez votre mot de passe']
            ])
            /*->add('password_confirm', PasswordType::class, [
                'label' => 'Confirmation de votre mot de passe',
                'mapped' => false //la propriété que tu lis dans le formulaire n'est pas lié dans l'entité
            ])*/
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
