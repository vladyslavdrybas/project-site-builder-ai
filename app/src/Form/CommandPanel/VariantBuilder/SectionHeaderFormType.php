<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\Builder\SectionHeaderFormDto;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionHeaderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var SectionHeaderFormDto $data */
        $data = $builder->getData();

        $builder
            ->add('isActive',
                SwitchType::class,
                [
                    'label' => 'On',
                    'data' => $data?->isActive
                ]
            )
            ->add('callToActionButton',
                CallToActionButtonFormType::class,
                [
                    'label' => 'Action Button',
                    'data' => $data?->callToActionButton
                ]
            )
            ->add('brand',
                BrandFormType::class,
                [
                    'label' => 'Brand',
                    'data' => $data?->brand
                ]
            )
            ->add(
                $builder->create(
                    'navigation',
                    FormType::class,
                    [
                        'label' => 'Navigation',
                        'required' => false,
                    ]
                )
                    ->add('home',
                        TextType::class,
                        [
                            'data' => $data->navigation['home'] ?? null
                        ]
                    )
                    ->add('hero',
                        TextType::class,
                        [
                            'data' => $data->navigation['hero'] ?? null
                        ]
                    )
                    ->add('features',
                        TextType::class,
                        [
                            'data' => $data->navigation['features'] ?? null
                        ]
                    )
                    ->add('howitworks',
                        TextType::class,
                        [
                            'data' => $data->navigation['howitworks'] ?? null
                        ]
                    )
                    ->add('reviews',
                        TextType::class,
                        [
                            'data' => $data->navigation['reviews'] ?? null
                        ]
                    )
                    ->add('pricing',
                        TextType::class,
                        [
                            'data' => $data->navigation['pricing'] ?? null
                        ]
                    )
                    ->add('newsletter',
                        TextType::class,
                        [
                            'data' => $data->navigation['newsletter'] ?? null
                        ]
                    )
                    ->add('contact',
                        TextType::class,
                        [
                            'data' => $data->navigation['contact'] ?? null
                        ]
                    )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SectionHeaderFormDto::class,
        ]);
    }
}
