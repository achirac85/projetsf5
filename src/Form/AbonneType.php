<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $abonne=$options["data"];
        $builder
            ->add('pseudo')
            ->add('roles', ChoiceType::class, [
                "choices"=>[
                    "Lecteur"=>"ROLE_LECTEUR",
                    "Bibliothécaire"=>"ROLE_BIBLIO",
                    "Directeur"=>"ROLE_ADMIN",
                    "Abonné"=>"ROLE_USER",
                    "Développeur"=>"ROLE_DEV",
                ],
                "multiple"=> true,
                "expanded"=> true,
                "label"=> "Autorisations"
            ])
            ->add('password', TextType::class, [
                "required" => $abonne->getId() ? false : true,
                "mapped"=>false
            ])
            ->add('nom')
            ->add('prenom')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
