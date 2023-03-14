<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\{
    Form\AbstractType,
    Form\Extension\Core\Type\EmailType,
    Form\Extension\Core\Type\PasswordType,
    Form\FormBuilderInterface,
    OptionsResolver\OptionsResolver,
    Validator\Constraints\NotBlank
};

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['required' => true, 'constraints' => new NotBlank()])
            ->add('password', PasswordType::class, [
                'hash_property_path' => 'password',
                'mapped' => false,
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
