<?php
declare(strict_types=1);

namespace App\Entity;

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
    protected string $title;

    #[Assert\NotBlank(message: 'Description cannot be blank.')]
    #[Assert\Length(min: 20, max: 65000)]
    #[ORM\Column(type: Types::TEXT)]
    protected ?string $description;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $proposal = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $customerPortrait = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $endAt = null;

    #[ORM\Column(name: "is_active", type: Types::BOOLEAN, options: ["default" => false])]
    protected bool $isActive = false;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Variant::class)]
    #[ORM\OrderBy(['endAt' => 'DESC'])]
    protected Collection $variants;

    public function __construct()
    {
        parent::__construct();
        $this->variants = new ArrayCollection();
    }

    protected ?string $teamId = null;
    protected ?string $analyticsId = null;
    protected array $tags = [];

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function isActive(): bool
    {
        $now = new \DateTime();
        $active = $this->isActive;

        if ($active && !empty($this->getStartAt())) {
            $active = $now >= $this->getStartAt();
        }

        if ($active && !empty($this->getEndAt())) {
            $active = $now <= $this->getEndAt();
        }

        return $active ;
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

    public function getProposal(): ?string
    {
        return $this->proposal;
    }

    public function setProposal(?string $proposal): void
    {
        $this->proposal = $proposal;
    }

    public function getCustomerPortrait(): ?string
    {
        return $this->customerPortrait;
    }

    public function setCustomerPortrait(?string $customerPortrait): void
    {
        $this->customerPortrait = $customerPortrait;
    }

    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function setVariants(Collection $variants): void
    {
        $this->variants = $variants;
    }
}
