<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\Builder\DescriptionWithThumbFormDto;
use App\Form\DescriptionType;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DescriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var DescriptionWithThumbFormDto $data */
        $data = $builder->getData();

        $builder
            ->add('isActive',
                SwitchType::class,
                [
                    'label' => 'On',
                    'data' => $data?->isActive
                ]
            )
            ->add('headline',
                TextType::class,
                [
                    'data' => $data?->headline,
                ]
            )
            ->add('subheadline',
                DescriptionType::class,
                [
                    'data' => $data?->subheadline,
                ]
            )
        ;

        if ($data?->hasThumb) {
            $builder
                ->add('media',
                    MediaCreatorFormType::class,
                    [
                        'label' => $data?->imageLabel ?? false,
                        'required' => false,
                        'data' => $data?->media,
                    ]
                )
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DescriptionWithThumbFormDto::class,
        ]);
    }
}
