<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('plainPassword', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class, [
                'label' => 'Пароль',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
            ])
            ->add('firstName', null, [
                'label' => 'Имя',
            ])
            ->add('lastName', null, [
                'label' => 'Фамилия',
            ])
            ->add('phone', null, [
                'label' => 'Телефон',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
