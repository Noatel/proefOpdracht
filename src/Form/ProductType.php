<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                ]
            ])
            ->add('price', NumberType::class,[
                'constraints' => [
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'data_class'      => Product::class,
            // enable/disable CSRF protection for this form
            'csrf_protection' => false,
        ]);
    }
}
