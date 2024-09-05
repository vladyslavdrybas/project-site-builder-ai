<?php
declare(strict_types=1);

namespace App\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

class TagToStringTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): string
    {
        if (is_string($value)) {
            return $this->purify($value);
        }

        if ($value instanceof ArrayCollection) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            return $this->purify(implode(' ', $value));
        }

        return '';
    }

    public function reverseTransform(mixed $value): array
    {
        if (!is_string($value)) {
            return [];
        }

        $value = $this->purify($value);
        $value = array_unique(explode(' ', $value));
;
        return $value;
    }

    protected function purify(string $value): string
    {
        return preg_replace('/[^-_\w]+/', ' ', $value);
    }
}
