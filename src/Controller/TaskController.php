<?php

namespace App\Controller;

use App\DTO\TaskDTO;
use App\Entity\Task;
use App\Repository\TagRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tasks')]
class TaskController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TaskRepository $taskRepository,
        private readonly TagRepository $tagRepository
    ) {
    }

    #[Route(methods: 'POST')]
    public function create(#[MapRequestPayload] TaskDTO $dto): JsonResponse
    {
        $task = new Task(
            $dto->title,
            $dto->description,
            $dto->priority
        );

        foreach ($dto->tags as $tagName) {
            $tag = $this->tagRepository->findOneBy(['name' => $tagName]);
            $task->addTag($tag);
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->json($task, 201);
    }

    #[Route('/{taskId}', methods: 'GET')]
    public function get(int $taskId): JsonResponse
    {
        return $this->json($this->taskRepository->find($taskId));
    }

    #[Route(methods: 'GET')]
    public function list(Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);

        $tasks = $this->taskRepository->findBy([], [], $limit, ($page - 1) * $limit);
        $total = $this->taskRepository->count();

        return $this->json([
            'tasks' => $tasks,
            'total' => $total,
        ]);
    }

    #[Route('/{taskId}', methods: 'PUT')]
    public function update(int $taskId, #[MapRequestPayload] TaskDTO $dto): JsonResponse
    {
        $task = $this->taskRepository->find($taskId);

        $task->setTitle($dto->title);
        $task->setDescription($dto->description);
        $task->setPriority($dto->priority);

        foreach ($task->getTags() as $tag) {
            $task->removeTag($tag);
        }

        foreach ($dto->tags as $tagName) {
            $tag = $this->tagRepository->findOneBy(['name' => $tagName]);
            $task->addTag($tag);
        }

        $this->entityManager->flush();

        return $this->json($task);
    }

    #[Route('/{taskId}', methods: 'PATCH')]
    public function complete(int $taskId): JsonResponse
    {
        $task = $this->taskRepository->find($taskId);
        $task->setCompleted(true);

        $this->entityManager->flush();

        return $this->json(null, 200);
    }

    #[Route('/{taskId}', methods: 'DELETE')]
    public function delete(int $taskId): JsonResponse
    {
        $task = $this->taskRepository->find($taskId);

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $this->json(null, 200);
    }
}