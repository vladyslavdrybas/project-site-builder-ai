<?php
declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageFromStocksType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'Search Image on stocks',
            'help' => 'Search by keywords. Add keywords separated by space.',
            'mapped' => false,
            'required' => false,
        ]);
    }

    public function getParent(): string
    {
        return TagsType::class;
    }
}
