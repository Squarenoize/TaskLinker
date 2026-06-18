<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;
use App\Entity\Worker;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $project = (new Project())
            ->setName('TaskLinker')
            ->setStartingDate(new \DateTimeImmutable('2026-06-01'))
            ->setDeadlineDate(new \DateTimeImmutable('2026-09-30'))
            ->setArchiveDate(null);

        $project2 = (new Project())
            ->setName('Vadrouille')
            ->setStartingDate(new \DateTimeImmutable('2026-05-05'))
            ->setDeadlineDate(new \DateTimeImmutable('2026-10-30'))
            ->setArchiveDate(null);

        $todo = (new Status())
            ->setName('To Do')
            ->setProject($project);

        $doing = (new Status())
            ->setName('Doing')
            ->setProject($project);

        $done = (new Status())
            ->setName('Done')
            ->setProject($project);

        $todo2 = (new Status())
            ->setName('To Do')
            ->setProject($project2);

        $doing2 = (new Status())
            ->setName('Doing')
            ->setProject($project2);

        $done2 = (new Status())
            ->setName('Done')
            ->setProject($project2);

        $worker1 = (new Worker())
            ->setLastname('Dubois')
            ->setFirstname('Maxime')
            ->setContract('CDI')
            ->setStartingDate(new \DateTimeImmutable('2024-01-03'));

        $worker2 = (new Worker())
            ->setLastname('Alves')
            ->setFirstname('Nina')
            ->setContract('CDI')
            ->setStartingDate(new \DateTimeImmutable('2026-01-10'));

        $worker3 = (new Worker())
            ->setLastname('Moreau')
            ->setFirstname('Lucas')
            ->setContract('Freelance')
            ->setStartingDate(new \DateTimeImmutable('2026-03-04'));

        $task1 = (new Task())
            ->setTitle('Gestion des droits d\'accès')
            ->setDescription('Un employé ne peut accéder qu\'à ses projets')
            ->setDeadlineDate(new \DateTimeImmutable('2026-07-10'))
            ->setProject($project)
            ->setStatus($todo)
            ->setWorker(null);

        $task2 = (new Task())
            ->setTitle('Développement de la page employé')
            ->setDescription('Page permettant à un employé de voir ses projets et tâches')
            ->setDeadlineDate(new \DateTimeImmutable('2026-07-15'))
            ->setProject($project)
            ->setStatus($doing)
            ->setWorker($worker2);

        $task3 = (new Task())
            ->setTitle('developpement de la structure globale')
            ->setDescription('Intégrer les maquettes')
            ->setDeadlineDate(new \DateTimeImmutable('2026-07-20'))
            ->setProject($project)
            ->setStatus($done)
            ->setWorker($worker2);

        $task4 = (new Task())
            ->setTitle('Créer la page projet')
            ->setDescription('Afficher les colonnes par statut')
            ->setDeadlineDate(new \DateTimeImmutable('2026-07-10'))
            ->setProject($project)
            ->setStatus($done)
            ->setWorker($worker1);

        $task5 = (new Task())
            ->setTitle('Basculer sur le framework Symfony')
            ->setDescription('Reprendre le projet MVC en Symfony pour une meilleure maintenabilité')
            ->setDeadlineDate(new \DateTimeImmutable('2026-11-11'))
            ->setProject($project2)
            ->setStatus($todo2)
            ->setWorker($worker3);

        $task6 = (new Task())
            ->setTitle('Intégrer le service de mails')
            ->setDescription('Permettre l\'envoi de mails pour les notifications')
            ->setDeadlineDate(new \DateTimeImmutable('2026-11-20'))
            ->setProject($project2)
            ->setStatus($doing2)
            ->setWorker($worker1);

        $user = (new User())
            ->setEmail('admin@example.com')
            ->setPassword('adminpassword')
            ->setRoles(['ROLE_ADMIN'])
            ->setWorker($worker1)
            ->setAlias('Max');

        $user2 = (new User())
            ->setEmail('user@example.com')
            ->setPassword('userpassword')
            ->setRoles(['ROLE_USER'])
            ->setWorker($worker2)
            ->setAlias('Ninou');

        $user3 = (new User())
            ->setEmail('user3@example.com')
            ->setPassword('user3password')
            ->setRoles(['ROLE_USER'])
            ->setWorker($worker3)
            ->setAlias('Lulu');

            $project->addWorker($worker1);
            $project->addWorker($worker2);

            $project2->addWorker($worker1);
            $project2->addWorker($worker3);

        $manager->persist($project);
        $manager->persist($project2);
        $manager->persist($todo);
        $manager->persist($doing);
        $manager->persist($done);
        $manager->persist($todo2);
        $manager->persist($doing2);
        $manager->persist($done2);
        $manager->persist($worker1);
        $manager->persist($worker2);
        $manager->persist($worker3);
        $manager->persist($task1);
        $manager->persist($task2);
        $manager->persist($task3);
        $manager->persist($task4);
        $manager->persist($task5);
        $manager->persist($task6);
        $manager->persist($user);
        $manager->persist($user2);
        $manager->persist($user3);

        $manager->flush();
    }
}
