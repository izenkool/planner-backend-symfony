<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTagDTO
{
    #[Assert\NotBlank]
    public string $name;
}