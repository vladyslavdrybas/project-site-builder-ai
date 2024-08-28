<?php
declare(strict_types=1);

namespace App\Entity\Type;

use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class JsonDataTransferObjectType extends JsonType
{
    const NAME = 'json_dto';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?IDataTransferObjectType
    {
        $data = parent::convertToPHPValue($value, $platform);

        try {
            if (null === $data) {
                return null;
            }

            if (!array_key_exists($this->getDtoClassNameKey(), $data)) {
                throw new \InvalidArgumentException('Missing class namespace.');
            }

            $className = $data[$this->getDtoClassNameKey()];
            if (!class_exists($className)) {
                throw new \InvalidArgumentException('Class "' . $className . '" does not exist.');
            }

            if (!in_array(IDataTransferObjectType::class, class_implements($className), true)) {
                throw new \InvalidArgumentException('Class "' . $className . '" does not implement IDataTransferObjectType.');
            }

            return $className::fromArray($data);
        } catch (\Exception $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof IDataTransferObjectType) {
            $data = $value->__serialize() + [$this->getDtoClassNameKey() => get_class($value)];

            return parent::convertToDatabaseValue($data, $platform);
        }

        throw new \InvalidArgumentException('Expected instance of ' . IDataTransferObjectType::class);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getDtoClassNameKey(): string
    {
        return self::NAME . '_class_name';
    }
}
