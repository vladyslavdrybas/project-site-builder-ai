<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\MediaAiPromptRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MediaAiPromptRepository::class, readOnly: false)]
#[ORM\Table(name: "media_ai_prompt")]
class MediaAiPrompt extends AbstractEntity
{
    #[Assert\NotBlank(message: 'Media prompt must have owner.')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    protected ?User $owner = null;

    #[Assert\Length(min: 20, max: 65000)]
    #[ORM\Column(type: Types::TEXT)]
    protected string $prompt;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $apiName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $modelName = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $promptMeta = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $promptAnswer = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    protected ?int $width = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    protected ?int $height = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $mimeType = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $url = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => false])]
    protected bool $isDone = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $requestAt = null;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0, 'comment' => 'milliseconds', 'unsigned' => true])]
    protected int $executionMilliseconds = 0;

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): void
    {
        $this->height = $height;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function getPromptMeta(): ?array
    {
        return $this->promptMeta;
    }

    public function setPromptMeta(?array $promptMeta): void
    {
        $this->promptMeta = $promptMeta;
    }

    public function getApiName(): ?string
    {
        return $this->apiName;
    }

    public function setApiName(?string $apiName): void
    {
        $this->apiName = $apiName;
    }

    public function getModelName(): ?string
    {
        return $this->modelName;
    }

    public function setModelName(?string $modelName): void
    {
        $this->modelName = $modelName;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): void
    {
        $this->prompt = $prompt;
    }

    public function getPromptAnswer(): ?array
    {
        return $this->promptAnswer;
    }

    public function setPromptAnswer(?array $promptAnswer): void
    {
        $this->promptAnswer = $promptAnswer;
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
