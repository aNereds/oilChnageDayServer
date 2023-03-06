<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\{
    AbstractType,
    Extension\Core\Type\EmailType,
    Extension\Core\Type\PasswordType,
    FormBuilderInterface,
};
use Symfony\Component\Validator\{
    Constraints\NotBlank,
    Constraints\Length,
    Constraints\Email
};

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 255]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
        ]);
    }
}
