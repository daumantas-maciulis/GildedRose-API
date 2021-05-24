<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ItemType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex('/_item$/'),
                ],
            ])
            ->add('value', NumberType::class, [
                'constraints' => [
                    new GreaterThanOrEqual(10),
                    new LessThanOrEqual(100)
                ]
            ])
            ->add('quality', IntegerType::class, [
                'constraints' => [
                    new GreaterThanOrEqual(-10),
                    new LessThanOrEqual(50)
                ]
            ])
            ->add('categoryName', TextType::class)
            ->add("sellIn", IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'csrf_protection' => false,
        ]);
    }
}

