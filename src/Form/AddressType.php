<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label'=> 'Quel nom souhaitez-vous donner à votre adresse ?'
            ])
            ->add('firstname', TextType::class,[
                'label'=> 'Votre prénom'
            ])
            ->add('lastname', TextType::class,[
                'label'=> 'Votre nom'
            ])
            ->add('company', TextType::class,[
                'label'=> 'Votre société (facultatif)',
                'required' => false,
            ])
            ->add('address', TextType::class,[
                'label'=> 'Votre adresse'
            ])
            ->add('postal', TextType::class,[
                'label'=> 'Votre code postal'
            ])
            ->add('city', TextType::class,[
                'label'=> 'Votre ville'
            ])
            //Country Type permet d'afficher tout les pays pour que le client séléctionne un payse
            ->add('country', CountryType::class,[
                'label'=> 'Votre pays'
            ])
            ->add('phone', TelType::class,[
                'label'=> 'Votre téléphone'
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer",
                'attr' => [
                    'class'=> 'btn-info btn-block mt-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
