<?php

namespace App\DTO;

use App\Enum\RecurrenceTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class RecurrenceRuleDTO
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public ?string $taskId = null;

    #[Assert\NotNull]
    public ?RecurrenceTypeEnum $recurrenceType = null;

    #[Assert\NotNull]
    #[Assert\Type('integer')]
    public ?int $interval = null;

    #[Assert\NotNull]
    #[Assert\Type('integer')]
    public ?int $daysOfWeek = null;

    #[Assert\NotNull]
    #[Assert\Type('integer')]
    public ?int $daysOfMonth = null;
}
