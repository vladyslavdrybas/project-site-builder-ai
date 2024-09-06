<?php
declare(strict_types=1);

namespace App\Entity;

use App\DataTransferObject\Variant\AI\Prompt\VariantPromptMetaDto;
use App\Entity\Type\JsonDataTransferObjectType;
use App\Repository\VariantRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $metaPublished = null;

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?VariantPromptMetaDto $promptMeta = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $promptTemplate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $endAt = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => false])]
    protected bool $isActive = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => false])]
    protected bool $isAiMade = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => true])]
    protected bool $isVisible = true;

    #[Assert\LessThanOrEqual(100)]
    #[Assert\GreaterThanOrEqual(0)]
    #[ORM\Column(name: "weight", type: Types::INTEGER, options: ["default" => 50])]
    protected int $weight = 50;

    /**
     * Many Variants have Many Media.
     * @var Collection<int, Media>
     */
    #[ORM\JoinTable(name: 'variant_media')]
    #[ORM\JoinColumn(name: 'variant_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'media_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Media::class)]
    protected Collection $medias;

    #[ORM\OneToOne(mappedBy: 'variant', targetEntity: VariantPrompt::class)]
    protected ?VariantPrompt $prompt = null;

    public function __construct()
    {
        parent::__construct();
        $this->medias = new ArrayCollection();
    }

    public function getMetaPublished(): ?array
    {
        return $this->metaPublished;
    }

    public function setMetaPublished(?array $metaPublished): void
    {
        $this->metaPublished = $metaPublished;
    }

    public function getPrompt(): ?VariantPrompt
    {
        return $this->prompt;
    }

    public function setPrompt(?VariantPrompt $prompt): void
    {
        $this->prompt = $prompt;
    }

    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function setMedias(Collection $medias): void
    {
        $this->medias = $medias;
    }

    public function addMedia(Media $media): void
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
        }
    }

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

    public function isEnabled(): bool
    {
        $now = new \DateTime();
        $enabled = $this->isActive;

        if ($enabled && !empty($this->getStartAt())) {
            $enabled = $now >= $this->getStartAt();
        }

        if ($enabled && !empty($this->getEndAt())) {
            $enabled = $now <= $this->getEndAt();
        }

        return $enabled ;
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

    public function isAiMade(): bool
    {
        return $this->isAiMade;
    }

    public function setIsAiMade(bool $isAiMade): void
    {
        $this->isAiMade = $isAiMade;
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

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): void
    {
        $this->isVisible = $isVisible;
    }
}
