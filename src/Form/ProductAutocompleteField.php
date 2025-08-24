<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class ProductAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Product::class,
            'attr'=>[
                'autofocus'=>true,
            ],
            'placeholder' => 'Veuillez entrer un produit',
            'choice_label' => 'name',
            'query_filter'=> function(ProductRepository $productRepository, string $filter){
                return $productRepository->findAllProductSearch($filter);
            },
            'query_builder'=> function(ProductRepository $productRepository){
                return $productRepository->findAllProduct();
            },
            //load items only 3 characters taped
            'min_characters'=>3,
            'preload'=>false,

            // choose which fields to use in the search
            // if not passed, *all* fields are used
            //'searchable_fields' => ['product.name'],

            // 'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
