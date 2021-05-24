<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('password', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(6, null, "Your password must be longer than 6 symbols"),
                ]
            ])
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Type("Alpha, space", "Your first name name can only be letters and spaces, 40 symbols long")
                ]
            ])
            ->add('lastName', TextType::class, [
                'constrains' => [
                    new NotBlank(),
                    new Type("Alpha, space", "Your last name name can only be letters and spaces, 40 symbols long")
                ]
            ])
            ->add('phoneNumber', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex(
                        "/^\+370\d/i", "Your phone number must start with +370"
                    )
                ]
            ])
            ->add('position', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false
        ]);
    }
}

