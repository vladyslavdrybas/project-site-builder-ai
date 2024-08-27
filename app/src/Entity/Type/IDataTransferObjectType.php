<?php
declare(strict_types=1);

namespace App\Entity\Type;

interface IDataTransferObjectType
{
    public function __serialize(): array;
    public function __unserialize(array $data): void;
    public function __toString(): string;

    public static function fromArray(array $data): self;
}
