<?php

namespace App\Entity;

use App\Enum\RecurrenceTypeEnum;
use App\Repository\RecurrenceRuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RecurrenceRuleRepository::class)]
#[ORM\Table(name: 'recurrence_rules')]
class RecurrenceRule
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[Groups(['task:export', 'task:import', 'recurrence_rule:read'])]
    private Uuid $id;

    #[ORM\Column(length: 255, enumType: RecurrenceTypeEnum::class)]
    #[Groups(['task:export', 'task:import', 'recurrence_rule:read'])]
    private ?RecurrenceTypeEnum $recurrence_type = null;

    #[ORM\Column]
    #[Groups(['task:export', 'task:import', 'recurrence_rule:read'])]
    private ?int $interval = null;

    #[ORM\Column]
    #[Groups(['task:export', 'task:import', 'recurrence_rule:read'])]
    private ?int $days_of_week = null;

    #[ORM\Column]
    #[Groups(['task:export', 'task:import', 'recurrence_rule:read'])]
    private ?int $days_of_month = null;

    #[ORM\Column]
    #[Groups(['task:export', 'task:import'])]
    private ?bool $active = null;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'recurrence_rules')]
    private Collection $tasks;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getRecurrenceType(): ?RecurrenceTypeEnum
    {
        return $this->recurrence_type;
    }

    public function setRecurrenceType(RecurrenceTypeEnum $recurrence_type): static
    {
        $this->recurrence_type = $recurrence_type;

        return $this;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }

    public function setInterval(int $interval): static
    {
        $this->interval = $interval;

        return $this;
    }

    public function getDaysOfWeek(): ?int
    {
        return $this->days_of_week;
    }

    public function setDaysOfWeek(int $days_of_week): static
    {
        $this->days_of_week = $days_of_week;

        return $this;
    }

    public function getDaysOfMonth(): ?int
    {
        return $this->days_of_month;
    }

    public function setDaysOfMonth(int $days_of_month): static
    {
        $this->days_of_month = $days_of_month;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setRecurrenceRules($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getRecurrenceRules() === $this) {
                $task->setRecurrenceRules(null);
            }
        }

        return $this;
    }
}
