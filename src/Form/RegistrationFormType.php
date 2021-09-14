<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entre votre mot de passe',
                    ]),
                    //new Length([
                        //'min' => 6,
                        //'minMessage' => 'votre mot de passe doit contenir au moins {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        //'max' => 4096,
                        //'max' => 10,
                        //'maxMessage'=> 'votre mot de passe ne doit pas depasser {{ limit }} caracteres '
                    //]),
                    new Regex([
                        "pattern"=>"^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$",
                        "message"=>"votre mot de passe doit contenir de beaucoup de condition minuscule, majuscule, caractere specieuax, chiffre, et certains caracteres 8-15"
                    ])
                ],
                "required"=>false
            ])
            ->add('prenom', TextType::class, [
                "label"=>"Prenom",
                "required"=>false
            ])

            ->add('nom', TextType::class, [
                "required"=>false
            ])
                
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter le CGU.',
                    ]),
                ],
                "label"=>"CGU"
            ]);
    
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
