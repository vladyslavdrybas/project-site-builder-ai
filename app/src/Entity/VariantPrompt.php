<?php
declare(strict_types=1);

namespace App\Entity;

use App\DataTransferObject\Variant\AI\Prompt\VariantPromptMetaDto;
use App\Entity\Type\JsonDataTransferObjectType;
use App\Repository\VariantPromptRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VariantPromptRepository::class, readOnly: false)]
#[ORM\Table(name: "variant_prompt")]
class VariantPrompt extends AbstractEntity
{
    #[Assert\NotBlank(message: 'Prompt must have variant connection.')]
    #[ORM\OneToOne(inversedBy: 'prompt', targetEntity: Variant::class)]
    #[ORM\JoinColumn(name: 'variant_id', referencedColumnName: 'id')]
    protected Variant $variant;

    #[Assert\Length(min: 20, max: 65000)]
    #[ORM\Column(type: Types::TEXT)]
    protected string $prompt;

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?VariantPromptMetaDto $promptMeta = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $promptTemplate = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $activeParts = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $promptAnswer = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => false])]
    protected bool $isDone = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $requestAt = null;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0, 'comment' => 'milliseconds', 'unsigned' => true])]
    protected int $executionMilliseconds = 0;

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

    public function getActiveParts(): ?array
    {
        return $this->activeParts;
    }

    public function setActiveParts(?array $activeParts): void
    {
        $this->activeParts = $activeParts;
    }

    public function getVariant(): Variant
    {
        return $this->variant;
    }

    public function setVariant(Variant $variant): void
    {
        $this->variant = $variant;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): void
    {
        $this->prompt = $prompt;
    }

    public function getPromptMeta(): ?VariantPromptMetaDto
    {
        return $this->promptMeta;
    }

    public function setPromptMeta(?VariantPromptMetaDto $promptMeta): void
    {
        $this->promptMeta = $promptMeta;
    }

    public function getPromptTemplate(): ?string
    {
        return $this->promptTemplate;
    }

    public function setPromptTemplate(?string $promptTemplate): void
    {
        $this->promptTemplate = $promptTemplate;
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): void
    {
        $this->isDone = $isDone;
    }

    public function getPromptAnswer(): ?array
    {
        return $this->promptAnswer;
    }

    public function setPromptAnswer(?array $promptAnswer): void
    {
        $this->promptAnswer = $promptAnswer;
    }
}
