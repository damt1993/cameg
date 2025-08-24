<?php

namespace App\Form;

use App\Entity\Dci;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductallForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "attr" => [
                    "autofocus" => true,
                ]
            ])
            ->add('price', IntegerType::class)
            ->add('dci', EntityType::class, [
                'class' => Dci::class,
                'choice_label' => 'name',
            ])
            ->add('publicPrice', IntegerType::class)
            ->add('peromptAt', DateType::class, [
                'widget'=>'single_text',
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
