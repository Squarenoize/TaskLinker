<?php

namespace App\Form;

use App\Entity\Status;
use App\Entity\Task;
use App\Entity\Worker;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Get the project associated with the tasks and workers
        $project = $builder->getData()?->getProject();

        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('deadline_date', null, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
                'choices' => $project ? $project->getStatuses() : [],
            ])
            ->add('worker', EntityType::class, [
                'class' => Worker::class,
                'choice_label' => function (Worker $worker) {
                    return $worker->getFirstname() . ' ' . $worker->getLastname();
                },
                'choices' => $project ? $project->getWorkers() : [],
                'placeholder' => '',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
