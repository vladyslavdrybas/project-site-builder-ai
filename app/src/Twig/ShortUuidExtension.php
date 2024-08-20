<?php
declare(strict_types=1);

namespace App\Twig;


use Symfony\Component\Uid\Uuid;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ShortUuidExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('shortUuid', array($this, 'process') ),
        ];
    }

    public function process(Uuid $id): string
    {
        $components = explode('-' , $id->toRfc4122());


        return $components[0] . '...' . substr($components[count($components) - 1], -5);
    }
}
