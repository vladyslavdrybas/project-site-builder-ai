<?php
declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $acceptTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/svg',
            'image/svg+xml',
            'image/webp',
        ];
        $maxSize = '5M';

        $resolver->setDefaults([
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'mimeTypes' => $acceptTypes,
                    'maxSize' => $maxSize,
                    'mimeTypesMessage' => 'Please upload a valid Image',
                ])
            ],
            'attr' => [
                'accept' => implode(',', $acceptTypes),
                'maxSize' => $maxSize,
            ]
        ]);
    }

    public function getParent(): string
    {
        return FileType::class;
    }
}
