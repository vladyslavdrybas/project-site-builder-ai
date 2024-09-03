<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\Builder\MediaCreatorFormDto;
use App\Form\ImageFromStocksType;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaCreatorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var MediaCreatorFormDto $data */
        $data = $builder->getData();

        $builder
            ->add('file',
                ImageType::class,
                [
                    'mapped' => true,
                    'label' => 'Upload image',
                ]
            )
            ->add('stock',
                ImageFromStocksType::class,
                [
                    'data' => $data?->stockKeywords,
                ]
            )
            ->add('systemId',
                HiddenType::class,
                [
                    'data' => $data?->systemId
                ]
            )
            ->add('remove',
                ButtonType::class,
                [
                    'attr' => [
                        'class' => 'btn-sm btn-light'
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MediaCreatorFormDto::class,
        ]);
    }
}
