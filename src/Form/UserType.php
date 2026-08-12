<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $options['data'] ?? null;
        $selectedRole = 'ROLE_USER';

        if ($data instanceof User) {
            $selectedRole = $data->getRoles()[0] ?? 'ROLE_USER';
        }

        $builder
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Chef de projet' => 'ROLE_ADMIN',
                    'Employé' => 'ROLE_USER',
                ],
                'mapped' => false,
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'data' => $selectedRole,
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
