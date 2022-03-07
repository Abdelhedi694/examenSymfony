<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Film;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('director')
            ->add('date')
            ->add('categorie', EntityType::class, [
                'class'=>Categorie::class,
                'choice_label'=>'description'
            ])
            ->add('imageFile', VichImageType::class,[
        'allow_delete'=>false
    ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
