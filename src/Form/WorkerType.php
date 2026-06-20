<?php

namespace App\Form;

use App\Entity\Worker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class WorkerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'required' => true
            ])
            ->add('firstname', TextType::class, [
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'mapped' => false
            ])
            ->add('contract', TextType::class, [
                'required' => true
            ])
            ->add('starting_date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'input' => 'datetime_immutable',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Worker::class,
        ]);
    }
}
