<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\OpenAiPromptRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OpenAiPromptRepository::class, readOnly: false)]
#[ORM\Table(name: "open_ai_prompt")]
class OpenAIPrompt extends AbstractEntity
{
    #[Assert\NotBlank(message: 'Prompt must have owner.')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    protected ?User $owner = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $promptHash = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $modelName = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $promptMeta = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $promptAnswer = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => false])]
    protected bool $isDone = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $requestAt = null;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0, 'comment' => 'milliseconds', 'unsigned' => true])]
    protected int $executionMilliseconds = 0;

    public function getPromptHash(): ?string
    {
        return $this->promptHash;
    }

    public function setPromptHash(?string $promptHash): void
    {
        $this->promptHash = $promptHash;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }

    public function getModelName(): ?string
    {
        return $this->modelName;
    }

    public function setModelName(?string $modelName): void
    {
        $this->modelName = $modelName;
    }

    public function getPromptMeta(): ?array
    {
        return $this->promptMeta;
    }

    public function getPromptAnswer(): ?array
    {
        return $this->promptAnswer;
    }

    public function setPromptAnswer(?array $promptAnswer): void
    {
        $this->promptAnswer = $promptAnswer;
    }

    public function setPromptMeta(?array $promptMeta): void
    {
        $this->promptMeta = $promptMeta;
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): void
    {
        $this->isDone = $isDone;
    }

    public function getRequestAt(): ?DateTimeInterface
    {
        return $this->requestAt;
    }

    public function setRequestAt(?DateTimeInterface $requestAt): void
    {
        $this->requestAt = $requestAt;
    }

    public function getExecutionMilliseconds(): int
    {
        return $this->executionMilliseconds;
    }

    public function setExecutionMilliseconds(int $executionMilliseconds): void
    {
        $this->executionMilliseconds = $executionMilliseconds;
    }
}
