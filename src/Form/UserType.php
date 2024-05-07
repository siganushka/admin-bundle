<?php

declare(strict_types=1);

namespace Siganushka\AdminBundle\Form;

use Siganushka\AdminBundle\Entity\Role;
use Siganushka\AdminBundle\Entity\User;
use Siganushka\Contracts\Doctrine\ResourceInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', EntityType::class, [
                'label' => 'admin_user.role',
                'class' => Role::class,
                'choice_label' => 'name',
                'constraints' => new NotBlank(),
            ])
            ->add('identifier', TextType::class, [
                'label' => 'admin_user.identifier',
                'constraints' => new NotBlank(),
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'This password is not match.',
                'first_options' => [
                    'label' => 'admin_user.password',
                    'constraints' => [
                        new NotBlank(['groups' => 'NotBlank']),
                        new Regex([
                            'pattern' => '/^[a-zA-Z0-9-_\.\@]{6,16}+$/',
                            'message' => 'This password format is not invalid.',
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'admin_user.password_confirmation',
                    'constraints' => new NotBlank(['groups' => 'NotBlank']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
            'constraints' => new UniqueEntity(['fields' => 'identifier']),
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();

                return $data instanceof ResourceInterface && null !== $data->getId()
                    ? ['Default']
                    : ['Default', 'NotBlank'];
            },
        ]);
    }
}
