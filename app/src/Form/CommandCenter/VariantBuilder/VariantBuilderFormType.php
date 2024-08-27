<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\VariantBuilder;

use App\DataTransferObject\Variant\Meta\VariantMetaDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VariantBuilderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var VariantMetaDto $data */
        $data = $builder->getData();

        $builder
            ->add('variantId',
                HiddenType::class,
                [
                    'data' => $data->variantId,
                ]
            )
            ->add('projectId',
                HiddenType::class,
                [
                    'data' => $data->projectId,
                ]
            )
            ->add(
                $builder->create(
                    'header',
                    FormType::class,
                    [
                        'label' => 'Header',
                        'required' => false,
                        'mapped' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                            'data' => $data->parts->header->isActive
                        ]
                    )
                    ->add('ctaBtnText',
                        TextType::class,
                        [
                            'label' => 'Action Button Text',
                            'data' => $data->parts->header->data->callToActionButton->text
                        ]
                    )
                    ->add('logoText',
                        TextType::class,
                        [
                            'data' => $data->parts->header->data->brand->text
                        ]
                    )
                    ->add('mediaFile', FileType::class, [
                        'label' => 'Logo Image',
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new File([
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/png',
                                    'image/svg',
                                    'image/svg+xml',
                                ],
                                'maxSize' => '5M',
                                'mimeTypesMessage' => 'Please upload a valid Image',
                            ])
                        ],
                    ])
                    ->add('mediaId',
                        HiddenType::class,
                        [
                            'data' => $data->parts->header->data->brand->media->id ?? null
                        ]
                    )
            )
            ->add(
                $builder->create(
                    'navigationLinksText',
                    FormType::class,
                    [
                        'label' => 'Navigation Links Text',
                        'required' => false,
                        'mapped' => false,
                    ]
                )
                    ->add('home',
                        TextType::class,
                        [
                            'data' => $data->parts->header->data->navigation['home'] ?? null
                        ]
                    )
                    ->add('hero',
                        TextType::class,
                        [
                            'data' => $data->parts->header->data->navigation['hero'] ?? null
                        ]
                    )
                    ->add('features',
                        TextType::class,
                        [
                            'data' => $data->parts->header->data->navigation['features'] ?? null
                        ]
                    )
                    ->add('howitworks',
                        TextType::class,
                        [
                            'data' => $data->parts->header->data->navigation['howitworks'] ?? null
                        ]
                    )
                    ->add('reviews',
                        TextType::class,
                        [
                            'data' => $data->parts->header->data->navigation['reviews'] ?? null
                        ]
                    )
                    ->add('pricing',
                        TextType::class,
                        [
                            'data' => $data->parts->header->data->navigation['pricing'] ?? null
                        ]
                    )
                    ->add('newsletter',
                        TextType::class,
                        [
                            'data' => $data->parts->header->data->navigation['newsletter'] ?? null
                        ]
                    )
                    ->add('contact',
                        TextType::class,
                        [
                            'data' => $data->parts->header->data->navigation['contact'] ?? null
                        ]
                    )
            )
            ->add(
                $builder->create(
                    'hero',
                    FormType::class,
                    [
                        'label' => 'Hero',
                        'required' => false,
                        'mapped' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3',
                            ],
                            'data' => $data->parts->hero->isActive,
                        ]
                    )
                    ->add('head',
                        TextType::class,
                        [
                            'data' => $data->parts->hero->data->head,
                        ]
                    )
                    ->add('description',
                        TextareaType::class,
                        [
                            'data' => $data->parts->hero->data->description,
                        ]
                    )
                    ->add('ctaBtnText',
                        TextType::class,
                        [
                            'label' => 'Action Button Text',
                            'data' => $data->parts->hero->data->callToActionButton->text,
                        ]
                    )
                    ->add('mediaFile', FileType::class, [
                        'label' => 'Presentation Image',
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new File([
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/png',
                                    'image/gif',
                                    'image/svg',
                                    'image/svg+xml',
//                                    'video/mp4',
                                ],
                                'maxSize' => '5M',
                                'mimeTypesMessage' => 'Please upload a valid Image',
                            ])
                        ],
                    ])
                    ->add('mediaId',
                        HiddenType::class,
                        [
                            'data' => $data->parts->hero->data->media->id ?? null
                        ]
                    )
            )
            ->add(
                $builder->create(
                    'features',
                    FormType::class,
                    [
                        'label' => 'Features',
                        'required' => false,
                        'mapped' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                            'data' => $data->parts->features->isActive,
                        ]
                    )
                    ->add('head',
                        TextType::class,
                        [
                            'data' => $data->parts->features->data?->head,
                        ]
                    )
                    ->add(
                        $builder->create(
                            'feature1',
                            FormType::class,
                            [
                                'label' => 'Feature 1',
                                'required' => false,
                            ]
                        )
                            ->add('head',
                                TextType::class,
                                [
                                    'data' => $data->parts->features->data?->items['feature1']?->head ?? null,
                                ]
                            )
                            ->add('description',
                                TextareaType::class,

                                [
                                    'data' => $data->parts->features->data?->items['feature1']?->description ?? null,
                                ]
                            )
                            ->add('mediaFile', FileType::class, [
                                'label' => 'Icon Image',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                            'image/svg',
                                            'image/svg+xml',
                                        ],
                                        'maxSize' => '3M',
                                        'mimeTypesMessage' => 'Please upload a valid Image',
                                    ])
                                ],
                            ])
                            ->add('mediaId',
                                HiddenType::class,
                                [
                                    'data' => $data->parts->features->data->items['feature1']->media->id ?? null
                                ]
                            )
                    )
                    ->add(
                        $builder->create(
                            'feature2',
                            FormType::class,
                            [
                                'label' => 'Feature 2',
                                'required' => false,
                            ]
                        )
                            ->add('head',
                                TextType::class,
                                [
                                    'data' => $data->parts->features->data?->items['feature2']?->head ?? null,
                                ]
                            )
                            ->add('description',
                                TextareaType::class,

                                [
                                    'data' => $data->parts->features->data?->items['feature2']?->description ?? null,
                                ]
                            )
                            ->add('mediaFile', FileType::class, [
                                'label' => 'Icon Image',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                            'image/svg',
                                            'image/svg+xml',
                                        ],
                                        'maxSize' => '3M',
                                        'mimeTypesMessage' => 'Please upload a valid Image',
                                    ])
                                ],
                            ])
                            ->add('mediaId',
                                HiddenType::class,
                                [
                                    'data' => $data->parts->features->data->items['feature2']->media->id ?? null
                                ]
                            )
                    )
                    ->add(
                        $builder->create(
                            'feature3',
                            FormType::class,
                            [
                                'label' => 'Feature 3',
                                'required' => false,
                            ]
                        )
                            ->add('head',
                                TextType::class,
                                [
                                    'data' => $data->parts->features->data?->items['feature3']?->head ?? null,
                                ]
                            )
                            ->add('description',
                                TextareaType::class,

                                [
                                    'data' => $data->parts->features->data?->items['feature3']?->description ?? null,
                                ]
                            )
                            ->add('mediaFile', FileType::class, [
                                'label' => 'Icon Image',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                            'image/svg',
                                            'image/svg+xml',
                                        ],
                                        'maxSize' => '3M',
                                        'mimeTypesMessage' => 'Please upload a valid Image',
                                    ])
                                ],
                            ])
                            ->add('mediaId',
                                HiddenType::class,
                                [
                                    'data' => $data->parts->features->data->items['feature3']->media->id ?? null
                                ]
                            )
                    )
            )
            ->add(
                $builder->create(
                    'howitworks',
                    FormType::class,
                    [
                        'label' => 'How It Works',
                        'required' => false,
                        'mapped' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                            'data' => $data->parts->howitworks->isActive,
                        ]
                    )
                    ->add('head',
                        TextType::class,
                        [
                            'data' => $data->parts->howitworks->data?->head,
                        ]
                    )
                    ->add(
                        $builder->create(
                            'step1',
                            FormType::class,
                            [
                                'label' => 'Step 1',
                                'required' => false,
                            ]
                        )
                            ->add('head',
                                TextType::class,
                                [
                                    'data' => $data->parts->howitworks->data?->items['step1']?->head ?? null,
                                ]
                            )
                            ->add('description',
                                TextareaType::class,

                                [
                                    'data' => $data->parts->howitworks->data?->items['step1']?->description ?? null,
                                ]
                            )
                            ->add('mediaFile', FileType::class, [
                                'label' => 'Step Image',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                            'image/gif',
                                            'image/svg',
                                            'image/svg+xml',
//                                            'video/mp4',
                                        ],
                                        'maxSize' => '5M',
                                        'mimeTypesMessage' => 'Please upload a valid Image',
                                    ])
                                ],
                            ])
                            ->add('mediaId',
                                HiddenType::class,
                                [
                                    'data' => $data->parts->howitworks->data->items['step1']->media->id ?? null
                                ]
                            )
                    )
                    ->add(
                        $builder->create(
                            'step2',
                            FormType::class,
                            [
                                'label' => 'Step 2',
                                'required' => false,
                            ]
                        )
                            ->add('head',
                                TextType::class,
                                [
                                    'data' => $data->parts->howitworks->data?->items['step2']?->head ?? null,
                                ]
                            )
                            ->add('description',
                                TextareaType::class,

                                [
                                    'data' => $data->parts->howitworks->data?->items['step2']?->description ?? null,
                                ]
                            )
                            ->add('mediaFile', FileType::class, [
                                'label' => 'Step Image',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                            'image/gif',
                                            'image/svg',
                                            'image/svg+xml',
//                                            'video/mp4',
                                        ],
                                        'maxSize' => '5M',
                                        'mimeTypesMessage' => 'Please upload a valid Image',
                                    ])
                                ],
                            ])
                            ->add('mediaId',
                                HiddenType::class,
                                [
                                    'data' => $data->parts->howitworks->data->items['step2']->media->id ?? null
                                ]
                            )
                    )
                    ->add(
                        $builder->create(
                            'step3',
                            FormType::class,
                            [
                                'label' => 'Step 3',
                                'required' => false,
                            ]
                        )
                            ->add('head',
                                TextType::class,
                                [
                                    'data' => $data->parts->howitworks->data?->items['step3']?->head ?? null,
                                ]
                            )
                            ->add('description',
                                TextareaType::class,

                                [
                                    'data' => $data->parts->howitworks->data?->items['step3']?->description ?? null,
                                ]
                            )
                            ->add('mediaFile', FileType::class, [
                                'label' => 'Step Image',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                            'image/gif',
                                            'image/svg',
                                            'image/svg+xml',
//                                            'video/mp4',
                                        ],
                                        'maxSize' => '5M',
                                        'mimeTypesMessage' => 'Please upload a valid Image',
                                    ])
                                ],
                            ])
                            ->add('mediaId',
                                HiddenType::class,
                                [
                                    'data' => $data->parts->howitworks->data->items['step3']->media->id ?? null
                                ]
                            )
                    )
            )
            ->add(
                $builder->create(
                    'testimonial',
                    FormType::class,
                    [
                        'label' => 'Testimonials',
                        'required' => false,
                        'mapped' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                            'data' => $data->parts->testimonial->isActive
                        ]
                    )
                    ->add('head',
                        TextType::class,
                        [
                            'data' => $data->parts->testimonial->head
                        ]
                    )
                    ->add('maxReviews',
                        NumberType::class,
                        [
                            'label' => 'Maximum Reviews',
                            'required' => false,
                            'html5' => true,
                            'data' => $data->parts->testimonial->maxReviews ?? 1,
                            'attr' => [
                                'min' => 1,
                                'max' => 10,
                            ]
                        ]
                    )
            )
            ->add(
                $builder->create(
                    'subscriptions',
                    FormType::class,
                    [
                        'label' => 'Subscriptions',
                        'required' => false,
                        'mapped' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                            'data' => $data->parts->pricing->isActive
                        ]
                    )
                    ->add('head',
                        TextType::class,
                        [
                            'data' => $data->parts->pricing->data?->head ?? null,
                        ]
                    )
                    ->add(
                        $builder->create(
                            'plan1',
                            FormType::class,
                            [
                                'label' => 'Plan 1',
                                'required' => false,
                            ]
                        )
                            ->add('head',
                                TextType::class,
                                [
                                    'data' => $data->parts->pricing->data?->items['plan1']?->head ?? null,
                                ]
                            )
                            ->add('description',
                                TextareaType::class,
                                [
                                    'data' => implode("\n", $data->parts->pricing->data?->items['plan1']->description ?? []) ?? null,
                                ]
                            )
                            ->add('ctaBtnText',
                                TextType::class,
                                [
                                    'label' => 'Action Button Text',
                                    'data' => $data->parts->pricing->data?->items['plan1']?->calwlToActionButton->text ?? null
                                ]
                            )
                            ->add('price',
                                TextType::class,
                                [
                                    'data' => $data->parts->pricing->data?->items['plan1']?->price ?? null,
                                ]
                            )
                            ->add('currencySign',
                                TextType::class,
                                [
                                    'data' => $data->parts->pricing->data?->items['plan1']?->currencySign ?? null,
                                ]
                            )
                    )
                    ->add(
                        $builder->create(
                            'plan2',
                            FormType::class,
                            [
                                'label' => 'Plan 2',
                                'required' => false,
                            ]
                        )
                            ->add('head',
                                TextType::class,
                                [
                                    'data' => $data->parts->pricing->data?->items['plan2']?->head ?? null,
                                ]
                            )
                            ->add('description',
                                TextareaType::class,
                                [
                                    'data' => implode("\n", $data->parts->pricing->data?->items['plan2']->description ?? []) ?? null,
                                ]
                            )
                            ->add('ctaBtnText',
                                TextType::class,
                                [
                                    'label' => 'Action Button Text',
                                    'data' => $data->parts->pricing->data?->items['plan2']?->callToActionButton->text ?? null
                                ]
                            )
                            ->add('price',
                                TextType::class,
                                [
                                    'data' => $data->parts->pricing->data?->items['plan2']?->price ?? null,
                                ]
                            )
                            ->add('currencySign',
                                TextType::class,
                                [
                                    'data' => $data->parts->pricing->data?->items['plan2']?->currencySign ?? null,
                                ]
                            )
                    )
                    ->add(
                        $builder->create(
                            'plan3',
                            FormType::class,
                            [
                                'label' => 'Plan 3',
                                'required' => false,
                            ]
                        )
                            ->add('head',
                                TextType::class,
                                [
                                    'data' => $data->parts->pricing->data?->items['plan3']?->head ?? null,
                                ]
                            )
                            ->add('description',
                                TextareaType::class,
                                [
                                    'data' => implode("\n", $data->parts->pricing->data?->items['plan3']->description ?? []) ?? null,
                                ]
                            )
                            ->add('ctaBtnText',
                                TextType::class,
                                [
                                    'label' => 'Action Button Text',
                                    'data' => $data->parts->pricing->data?->items['plan3']?->callToActionButton->text ?? null
                                ]
                            )
                            ->add('price',
                                TextType::class,
                                [
                                    'data' => $data->parts->pricing->data?->items['plan3']?->price ?? null,
                                ]
                            )
                            ->add('currencySign',
                                TextType::class,
                                [
                                    'data' => $data->parts->pricing->data?->items['plan3']?->currencySign ?? null,
                                ]
                            )
                    )
            )
            ->add(
                $builder->create(
                    'newsletter',
                    FormType::class,
                    [
                        'label' => 'Newsletter',
                        'required' => false,
                        'mapped' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                            'data' => $data->parts->newsletter->isActive
                        ]
                    )
                    ->add('head',
                        TextType::class,
                        [
                            'data' => $data->parts->newsletter->head
                        ]
                    )
                    ->add('description',
                        TextareaType::class,
                        [
                            'data' => $data->parts->newsletter->description
                        ]
                    )
                    ->add('inputFieldPlaceholder',
                        TextType::class,
                        [
                            'data' => $data->parts->newsletter->inputFieldPlaceholder
                        ]
                    )
                    ->add('ctaBtnText',
                        TextType::class,
                        [
                            'label' => 'Action Button Text',
                            'data' => $data->parts->newsletter->callToActionButtonDto->text ?? null
                        ]
                    )
            )
            ->add(
                $builder->create(
                    'footer',
                    FormType::class,
                    [
                        'label' => 'Footer',
                        'required' => false,
                        'mapped' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                            'data' => $data->parts->footer->isActive,
                        ]
                    )
                    ->add('copyright',
                        TextType::class,
                        [
                            'data' => $data->parts->footer->copyright,
                        ]
                    )
                    ->add('privacyPolicyFull',
                        TextareaType::class,
                        [
                            'data' => $data->parts->footer->privacyPolicyFull,

                        ]
                    )
                    ->add('termsOfServiceFull',
                        TextareaType::class,
                        [
                            'data' => $data->parts->footer->termsOfServiceFull,

                        ]
                    )
                    ->add(
                        $builder->create(
                            'socialLinks',
                            FormType::class,
                            [
                                'label' => 'Social Links',
                                'required' => false,
                            ]
                        )
                            ->add('discord',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['discord'] ?? null,
                                ]
                            )
                            ->add('facebook',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['facebook'] ?? null,
                                ]
                            )
                            ->add('github',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['github'] ?? null,
                                ]
                            )
                            ->add('instagram',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['instagram'] ?? null,
                                ]
                            )
                            ->add('linkedIn',
                                TextType::class,
                                [
                                    'label' => 'LinkedIn',
                                    'data' => $data->parts->footer->socialLinks['linkedIn'] ?? null,
                                ]
                            )
                            ->add('medium',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['medium'] ?? null,
                                ]
                            )
                            ->add('reddit',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['reddit'] ?? null,
                                ]
                            )
                            ->add('telegram',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['telegram'] ?? null,
                                ]
                            )
                            ->add('youtube',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['youtube'] ?? null,
                                ]
                            )
                            ->add('twitter',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['twitter'] ?? null,
                                ]
                            )
                            ->add('tikTok',
                                TextType::class,
                                [
                                    'label' => 'TikTok',
                                    'data' => $data->parts->footer->socialLinks['tikTok'] ?? null,
                                ]
                            )
                            ->add('twitter',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['twitter'] ?? null,
                                ]
                            )
                            ->add('youtube',
                                TextType::class,
                                [
                                    'data' => $data->parts->footer->socialLinks['youtube'] ?? null,
                                ]
                            )
                    )
            )
            ->add(
                $builder->create(
                    'designSettings',
                    FormType::class,
                    [
                        'label' => 'Design Settings',
                        'required' => false,
                        'mapped' => false,
                    ]
                )
                    ->add('primary',
                        TextType::class,
                        [
                            'data' => $data->parts->design->colors['primary'] ?? null,
                        ]
                    )
                    ->add('secondary',
                        TextType::class,
                        [
                            'data' => $data->parts->design->colors['secondary'] ?? null,
                        ]
                    )
                    ->add('success',
                        TextType::class,
                        [
                            'data' => $data->parts->design->colors['success'] ?? null,
                        ]
                    )
                    ->add('error',
                        TextType::class,
                        [
                            'data' => $data->parts->design->colors['error'] ?? null,
                        ]
                    )
                    ->add('info',
                        TextType::class,
                        [
                            'data' => $data->parts->design->colors['info'] ?? null,
                        ]
                    )
            )
            ->add(
                'backBtn',
                SubmitType::class,
                [
                    'label' => 'Back',
                    'attr' => [
                        'class' => 'btn btn-primary rounded-5',
                    ],
                ]
            )
            ->add(
                'cancelBtn',
                SubmitType::class,
                [
                    'label' => 'Cancel',
                    'attr' => [
                        'class' => 'btn btn-primary rounded-5',
                    ],
                ]
            )
            ->add(
                'saveBtn',
                SubmitType::class,
                [
                    'label' => 'Save',
                    'attr' => [
                        'class' => 'btn btn-primary rounded-5',
                    ],
                ]
            )
            ->add(
                'previewBtn',
                SubmitType::class,
                [
                    'label' => 'Preview',
                    'attr' => [
                        'class' => 'btn btn-primary rounded-5',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VariantMetaDto::class,
        ]);
    }
}
