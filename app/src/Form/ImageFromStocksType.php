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
            'help' => 'Add keywords separated by space. for example: width512, height512, blur5, grayscale, etc.',
            'required' => false,
            'row_attr' => [
                'class' => 'w-100',
            ],
            'attr' => [
                'class' => 'input-tags text-secondary search-keywords w-100',
                'placeholder' => 'keywords',
                'data-ub-tag-separator' => ' ',
            ]
        ]);
    }

    public function getParent(): string
    {
        return TagsType::class;
    }
}
