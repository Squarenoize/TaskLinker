<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Worker;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            /*->add('starting_date', null, [
                'widget' => 'single_text',
            ])
            ->add('deadline_date', null, [
                'widget' => 'single_text',
            ])
            ->add('archive_date', null, [
                'widget' => 'single_text',
            ])*/
            ->add('workers', EntityType::class, [
                'class' => Worker::class,
                'choice_label' => function (Worker $worker) {
                    return $worker->getFirstname() . ' ' . $worker->getLastname();
                },
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
