<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\VariantRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VariantRepository::class, readOnly: false)]
#[ORM\Table(name: "variant")]
class Variant extends AbstractEntity
{
    #[Assert\NotBlank(message: 'Variant must have project connection.')]
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'variants')]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id')]
    protected Project $project;

    #[Assert\NotBlank(message: 'Title cannot be blank.')]
    #[Assert\Length(min: 5, max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    protected string $title;

    #[Assert\Length(min: 20, max: 65000)]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $description = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $meta = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $endAt = null;

    #[ORM\Column(name: "is_active", type: Types::BOOLEAN, options: ["default" => false])]
    protected bool $isActive = false;

    #[Assert\LessThanOrEqual(100)]
    #[Assert\GreaterThanOrEqual(0)]
    #[ORM\Column(name: "weight", type: Types::INTEGER, options: ["default" => 50])]
    protected int $weight = 50;

    protected array $tags = [];

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getMeta(): ?array
    {
        return $this->meta;
    }

    public function setMeta(?array $meta): void
    {
        $this->meta = $meta;
    }

    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?DateTimeInterface $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getEndAt(): ?DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?DateTimeInterface $endAt): void
    {
        $this->endAt = $endAt;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }
}
