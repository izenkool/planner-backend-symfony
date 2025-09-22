<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class TaskImportService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface    $serializer,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function import(string $data): void
    {
        $tasks = $this->serializer->deserialize($data, Task::class . '[]', 'json', ['groups' => 'task:import']);
        
        foreach ($tasks as $task) {
            $this->entityManager->persist($task);
        }

        $this->entityManager->flush();
    }
}
