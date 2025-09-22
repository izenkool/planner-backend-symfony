<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: 'tasks')]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'tasks')]
    private Collection $tags;

    public function __construct(
        #[ORM\Column(length: 255)]
        private string $title,
        #[ORM\Column(type: Types::TEXT)]
        private string $description,
        #[ORM\Column]
        private int $priority,
        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private ?\DateTimeInterface $created_at = new \DateTime(),
        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private ?\DateTimeInterface $updated_at = new \DateTime(),
        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?\DateTimeInterface $completed_at = null,
        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?\DateTimeInterface $deadline = null,
        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?\DateTimeInterface $starts_at = null,
        #[ORM\ManyToOne(inversedBy: 'tasks')]
        private ?RecurrenceRule $recurrence_rules = null
    ) {
        $this->id = Uuid::v7();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function setCompleted(bool $completed): static
    {
        if ($completed) {
            $this->completed_at = new \DateTime();
        } else {
            $this->completed_at = null;
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeInterface
    {
        return $this->completed_at;
    }

    public function setCompletedAt(?\DateTimeInterface $completed_at): static
    {
        $this->completed_at = $completed_at;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTimeInterface $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->starts_at;
    }

    public function setStartsAt(?\DateTimeInterface $starts_at): static
    {
        $this->starts_at = $starts_at;

        return $this;
    }

    public function getRecurrenceRules(): ?RecurrenceRule
    {
        return $this->recurrence_rules;
    }

    public function setRecurrenceRules(?RecurrenceRule $recurrence_rules): static
    {
        $this->recurrence_rules = $recurrence_rules;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
