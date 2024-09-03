<?php
declare(strict_types=1);

namespace App\DataTransferObject\Variant\AI\Prompt;

use App\Entity\Type\IDataTransferObjectType;

class VariantPromptMetaDto implements IDataTransferObjectType
{
    public function __construct(
        public ?string $productShortDescription = null,
        public ?string $productDescription = null,
        public ?string $targetAudience = null,
        public ?string $proposal = null,
        public ?string $value = null,
        public ?string $competitors = null,
        public ?array $tone = [],
        public ?array $style = []
    ) {}

    public function __serialize(): array
    {
        return [
            'productShortDescription' => $this->productShortDescription,
            'productDescription' => $this->productDescription,
            'targetAudience' => $this->targetAudience,
            'proposal' => $this->proposal,
            'value' => $this->value,
            'competitors' => $this->competitors,
            'tone' => $this->tone,
            'style' => $this->style,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->productShortDescription = $data['productShortDescription'] ?? null;
        $this->productDescription = $data['productDescription'] ?? null;
        $this->targetAudience = $data['targetAudience'] ?? null;
        $this->proposal = $data['proposal'] ?? null;
        $this->value = $data['value'] ?? null;
        $this->competitors = $data['competitors'] ?? null;
        $this->tone = $data['tone'] ?? null;
        $this->style = $data['style'] ?? null;
    }

    public static function fromArray(array $data): IDataTransferObjectType
    {
        return new self(
            $data['productShortDescription'] ?? null,
            $data['productDescription'] ?? null,
            $data['targetAudience'] ?? null,
            $data['proposal'] ?? null,
            $data['value'] ?? null,
            $data['competitors'] ?? null,
            $data['tone'] ?? null,
            $data['style'] ?? null
        );
    }

    public function __toString(): string
    {
        return json_encode($this->__serialize(), JSON_THROW_ON_ERROR);
    }
}
