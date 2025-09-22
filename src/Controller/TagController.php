<?php

namespace App\Controller;

use App\DTO\CreateTagDTO;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/api/tags')]
class TagController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TagRepository $tagRepository
    ) {
    }

    #[Route(methods: 'POST')]
    public function create(#[MapRequestPayload] CreateTagDTO $dto): JsonResponse
    {
        $tag = new Tag($dto->name);

        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return $this->json($tag);
    }

    #[Route(methods: 'GET')]
    public function list(): JsonResponse
    {
        return $this->json($this->tagRepository->findAll());
    }

    #[Route('/{tagId}', methods: 'DELETE')]
    public function delete(string $tagId): JsonResponse
    {
        $tag = $this->tagRepository->find(Uuid::fromString($tagId));

        $this->entityManager->remove($tag);
        $this->entityManager->flush();

        return $this->json('delete success!');
    }
}