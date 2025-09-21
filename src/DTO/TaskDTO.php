<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class TaskDTO
{
    #[Assert\NotBlank]
    public string $title;

    #[Assert\NotBlank]
    public string $description;

    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 2)]
    public int $priority;

    /**
     * @var string[]
     */
    #[Assert\NotBlank]
    public array $tags;
}