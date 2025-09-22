<?php

namespace App\Controller;

use App\DTO\RecurrenceRuleDTO;
use App\Entity\RecurrenceRule;
use App\Repository\RecurrenceRuleRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class RecurrenceRuleController extends AbstractController
{
    #[Route('/recurrence-rules', name: 'app_recurrence_rules_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] RecurrenceRuleDTO $dto,
        RecurrenceRuleRepository $recurrenceRuleRepository,
        TaskRepository $taskRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $task = $taskRepository->find($dto->taskId);

        if (!$task) {
            return $this->json(['message' => 'Task not found'], 404);
        }

        $rule = $recurrenceRuleRepository->findOneBy([
            'recurrence_type' => $dto->recurrenceType,
            'interval' => $dto->interval,
            'days_of_week' => $dto->daysOfWeek,
            'days_of_month' => $dto->daysOfMonth,
        ]);

        if (!$rule) {
            $rule = new RecurrenceRule();
            $rule->setRecurrenceType($dto->recurrenceType);
            $rule->setInterval($dto->interval);
            $rule->setDaysOfWeek($dto->daysOfWeek);
            $rule->setDaysOfMonth($dto->daysOfMonth);
            $rule->setActive(true);
            $entityManager->persist($rule);
        }

        $task->setRecurrenceRules($rule);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json($rule, 200, [], ['groups' => 'recurrence_rule:read']);
    }
}
