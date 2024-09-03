<?php
declare(strict_types=1);

namespace App\Entity;

use App\DataTransferObject\Variant\AI\Prompt\VariantPromptMetaDto;
use App\Entity\Type\JsonDataTransferObjectType;
use App\Repository\ProjectRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class, readOnly: false)]
#[ORM\Table(name: "project")]
class Project extends AbstractEntity
{
    #[Assert\NotBlank(message: 'Project must have owner.')]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'projects')]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    protected User $owner;

    #[Assert\NotBlank(message: 'Title cannot be blank.')]
    #[Assert\Length(min: 11, max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    protected ?string $title = null;

    #[Assert\NotBlank(message: 'Description cannot be blank.')]
    #[Assert\Length(min: 20, max: 65000)]
    #[ORM\Column(type: Types::TEXT)]
    protected ?string $description = null;

    #[ORM\Column(type: JsonDataTransferObjectType::NAME, nullable: true)]
    protected ?VariantPromptMetaDto $promptMeta = null;

    #[ORM\Column(name: "is_active", type: Types::BOOLEAN, options: ["default" => false])]
    protected bool $isActive = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $endAt = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Variant::class)]
    #[ORM\OrderBy(['endAt' => 'DESC'])]
    protected Collection $variants;

    /**
     * Many Projects have Many Tags.
     * @var Collection<int, Tag>
     */
    #[ORM\JoinTable(name: 'project_tag')]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\OrderBy(['id' => 'ASC'])]
    protected Collection $tags;

    public function __construct()
    {
        parent::__construct();
        $this->variants = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    protected ?string $teamId = null;
    protected ?string $analyticsId = null;

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title = null): void
    {
        $this->title = $title;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
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

    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function setVariants(Collection $variants): void
    {
        $this->variants = $variants;
    }

    public function getPromptMeta(): ?VariantPromptMetaDto
    {
        return $this->promptMeta;
    }

    public function setPromptMeta(?VariantPromptMetaDto $promptMeta): void
    {
        $this->promptMeta = $promptMeta;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->offsetSet($tag->getId(), $tag);
        }
    }

    public function hasTagByKey(string $key): bool
    {
        return $this->tags->containsKey($key);
    }
}
