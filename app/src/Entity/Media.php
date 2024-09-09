<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\MediaRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MediaRepository::class, readOnly: false)]
#[ORM\Table(name: "media")]
#[ORM\UniqueConstraint(
    name: 'owner_id_idx',
    columns: ['owner_id', 'id']
)]
#[UniqueEntity(fields: ['owner', 'id'], message: 'Not unique owner content.')]
class Media implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, length: 128)]
    protected string $id;

    #[Assert\NotBlank(message: 'Media must have owner.')]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'medias')]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    protected ?User $owner = null;

    #[ORM\ManyToOne(targetEntity: MediaAiPrompt::class)]
    #[ORM\JoinColumn(name: 'media_ai_prompt', referencedColumnName: 'id')]
    protected ?MediaAiPrompt $mediaAiPrompt = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    protected string $mimetype;

    #[ORM\Column(type: Types::STRING, length: 20)]
    protected string $extension;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    protected int $size = 0;

    #[ORM\Column(type: Types::STRING)]
    protected string $path;

    #[ORM\Column(type: Types::STRING, options: ['default' => 'local'])]
    protected string $serverAlias = 'local';

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true, 'default' => 0])]
    protected int $version = 0;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected DateTimeInterface $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected DateTimeInterface $updatedAt;

    /**
     * Many Media have Many Tags.
     * @var Collection<int, Tag>
     */
    #[ORM\JoinTable(name: 'media_tag')]
    #[ORM\JoinColumn(name: 'media_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    protected Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    public function getMediaAiPrompt(): ?MediaAiPrompt
    {
        return $this->mediaAiPrompt;
    }

    public function setMediaAiPrompt(?MediaAiPrompt $mediaAiPrompt): void
    {
        $this->mediaAiPrompt = $mediaAiPrompt;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getServerAlias(): string
    {
        return $this->serverAlias;
    }

    public function setServerAlias(string $serverAlias): void
    {
        $this->serverAlias = $serverAlias;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner = null): void
    {
        $this->owner = $owner;
    }

    public function getMimetype(): string
    {
        return $this->mimetype;
    }

    public function setMimetype(string $mimetype): void
    {
        $this->mimetype = $mimetype;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
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

    public function getObject(): string
    {
        $namespace = explode('\\', static::class);

        return array_pop($namespace);
    }

    public function getRawId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
