<?php
namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Entity\User;

class ProjectAccessVoter extends Voter
{
    
    public function __construct(
        private ProjectRepository $projectRepository,
        private TaskRepository $taskRepository,
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === 'access_project' || $attribute === 'access_task';
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $project = null;
        
        switch ($attribute) {
            case 'access_project':
                $project = $this->projectRepository->find($subject);
                break;
            case 'access_task':
                $task = $this->taskRepository->find($subject);
                $project = $task?->getProject();
                break;
        }

        $authenticatedUser = $token->getUser();

        if (!$authenticatedUser instanceof User || !$project) {
            return false;
        }

        $worker = $authenticatedUser->getWorker();

        if (null === $worker) {
            return false;
        }

        return in_array('ROLE_ADMIN', $authenticatedUser->getRoles()) || $project->getWorkers()->contains($worker);
    }
}