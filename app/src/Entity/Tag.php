<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class, readOnly: false)]
#[ORM\Table(name: "tag")]
class Tag implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, unique: true)]
    protected string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
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

    public function __toString(): string
    {
        return $this->getRawId();
    }
}
