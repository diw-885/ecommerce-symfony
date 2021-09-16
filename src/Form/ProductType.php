<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            // ->add('slug')
            ->add('description', TextareaType::class)
            ->add('price')
            // ->add('createdAt')
            ->add('liked')
            // ->add('image')
            ->add('promotion')
            ->add('category', null, [
                'choice_label' => 'name', // propriété name de la classe Category
                // 'expanded' => true, // Pour avoir des radios au lieu d'un select
            ])
            ->add('colors', null, [
                'choice_label' => 'name',
                'expanded' => true, // Checkboxes au lieu de select multiple
                // 'multiple' => true, // ManyToMany donc le multiple est à true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
