<?php

namespace App\Form;

use App\Entity\Dci;
use App\Repository\DciRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
    public function __construct(private readonly DciRepository $dciRepository)
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "mapped"=>false,
                "attr"=> [
                    "autofocus"=>true,
                ]
            ])
            ->add('price', MoneyType::class, [
                "mapped"=>false,
                "required"=>false,
                "grouping" => true,
                'divisor' => 100,
            ])
            ->add('dci', EntityType::class, [
                "mapped"=>false,
                'class' => Dci::class,
                "query_builder"=> $this->dciRepository->findAllDci(),
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => Product::class,
        ]);
    }
}
