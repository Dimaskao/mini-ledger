<?php

namespace App\Entity;

use App\Repository\ActivityLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityLogRepository::class)]
class ActivityLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $entityType;

    #[ORM\Column]
    private int $entityId;

    #[ORM\Column(length: 255)]
    private string $action;

    #[ORM\Column(nullable: true)]
    private ?array $oldValuesJson = null;

    #[ORM\Column(nullable: true)]
    private ?array $newValuesJson = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function setEntityType(string $entityType): static
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function getEntityId(): int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): static
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getOldValuesJson(): ?array
    {
        return $this->oldValuesJson;
    }

    public function setOldValuesJson(?array $oldValuesJson): static
    {
        $this->oldValuesJson = $oldValuesJson;

        return $this;
    }

    public function getNewValuesJson(): ?array
    {
        return $this->newValuesJson;
    }

    public function setNewValuesJson(?array $newValuesJson): static
    {
        $this->newValuesJson = $newValuesJson;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
