<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Categorie;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                "label"=> "Titre du livre",
                "required"=> false,
                "constraints"=> [
                    new NotBlank([
                        "message"=>"Le titre ne peut pas être vide"
                    ])
                ]
            ])
            ->add('auteur')
            ->add('categorie', EntityType::class, [
                "class"=>Categorie::class,
                "choice_label"=>"titre",
                "label"=>"Categorie",
                "placeholder"=>"choisir une categorie..."
            ])
            ->add("couverture", FileType::class, [
                "mapped"=>false
            ])
            ->add("enregistrer", SubmitType::class, [
                "attr"=> ["btn btn-primary"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
