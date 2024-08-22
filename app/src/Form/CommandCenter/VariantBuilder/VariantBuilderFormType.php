<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\VariantBuilder;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VariantBuilderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                $builder->create(
                    'header',
                    FormType::class,
                    [
                        'label' => 'Header',
                        'required' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                        ]
                    )
                    ->add('ctaBtnText',
                        TextType::class,
                        [
                            'label' => 'Action Button Text'
                        ]
                    )
                    ->add('logoText', TextType::class, [])
                    ->add('logoPathFile', FileType::class, [
                        'label' => 'Logo Image',
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new File([
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/png',
                                ],
                                'maxSize' => '5M',
                                'mimeTypesMessage' => 'Please upload a valid Image',
                            ])
                        ],
                    ])
            )
            ->add(
                $builder->create(
                    'navigationLinksText',
                    FormType::class,
                    [
                        'label' => 'Navigation Links Text',
                        'required' => false,
                    ]
                )
                    ->add('home',
                        TextType::class
                    )
                    ->add('hero',
                        TextType::class
                    )
                    ->add('features',
                        TextType::class
                    )
                    ->add('howItWorks',
                        TextType::class
                    )
                    ->add('reviews',
                        TextType::class
                    )
                    ->add('pricing',
                        TextType::class
                    )
                    ->add('newsletter',
                        TextType::class
                    )
                    ->add('contact',
                        TextType::class
                    )
            )
            ->add(
                $builder->create(
                    'hero',
                    FormType::class,
                    [
                        'label' => 'Hero',
                        'required' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                        ]
                    )
                    ->add('header', TextType::class, [])
                    ->add('description', TextareaType::class, [])
                    ->add('ctaBtnText',
                        TextType::class,
                        [
                            'label' => 'Action Button Text'
                        ]
                    )
                    ->add('coverPathFile', FileType::class, [
                        'label' => 'Presentation Image or Video',
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new File([
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/png',
                                    'image/gif',
                                    'video/mp4',
                                ],
                                'maxSize' => '5M',
                                'mimeTypesMessage' => 'Please upload a valid Image or Video',
                            ])
                        ],
                    ])
                    ->add('backgroundPathFile', FileType::class, [
                        'label' => 'Background Image or Video',
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new File([
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/png',
                                    'image/gif',
                                    'video/mp4',
                                ],
                                'maxSize' => '5M',
                                'mimeTypesMessage' => 'Please upload a valid Image or Video',
                            ])
                        ],
                    ])
            )
            ->add(
                $builder->create(
                    'features',
                    FormType::class,
                    [
                        'label' => 'Features',
                        'required' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
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
                            ->add('head', TextType::class, [])
                            ->add('description', TextareaType::class, [])
                            ->add('iconPathFile', FileType::class, [
                                'label' => 'Icon Image',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                        ],
                                        'maxSize' => '3M',
                                        'mimeTypesMessage' => 'Please upload a valid Image',
                                    ])
                                ],
                            ])
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
                            ->add('head', TextType::class, [])
                            ->add('description', TextareaType::class, [])
                            ->add('iconPathFile', FileType::class, [
                                'label' => 'Icon Image',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                        ],
                                        'maxSize' => '3M',
                                        'mimeTypesMessage' => 'Please upload a valid Image',
                                    ])
                                ],
                            ])
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
                            ->add('head', TextType::class, [])
                            ->add('description', TextareaType::class, [])
                            ->add('iconPathFile', FileType::class, [
                                'label' => 'Icon Image',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                        ],
                                        'maxSize' => '3M',
                                        'mimeTypesMessage' => 'Please upload a valid Image',
                                    ])
                                ],
                            ])
                    )
            )
            ->add(
                $builder->create(
                    'howitworks',
                    FormType::class,
                    [
                        'label' => 'How It Works',
                        'required' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
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
                            ->add('head', TextType::class, [])
                            ->add('description', TextareaType::class, [])
                            ->add('mediaPathFile', FileType::class, [
                                'label' => 'Step Image or Video',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                            'image/gif',
                                            'video/mp4',
                                        ],
                                        'maxSize' => '5M',
                                        'mimeTypesMessage' => 'Please upload a valid Image or Video',
                                    ])
                                ],
                            ])
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
                            ->add('head', TextType::class, [])
                            ->add('description', TextareaType::class, [])
                            ->add('mediaPathFile', FileType::class, [
                                'label' => 'Step Image or Video',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                            'image/gif',
                                            'video/mp4',
                                        ],
                                        'maxSize' => '5M',
                                        'mimeTypesMessage' => 'Please upload a valid Image or Video',
                                    ])
                                ],
                            ])
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
                            ->add('head', TextType::class, [])
                            ->add('description', TextareaType::class, [])
                            ->add('mediaPathFile', FileType::class, [
                                'label' => 'Step Image or Video',
                                'mapped' => false,
                                'required' => false,
                                'constraints' => [
                                    new File([
                                        'mimeTypes' => [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                            'image/gif',
                                            'video/mp4',
                                        ],
                                        'maxSize' => '5M',
                                        'mimeTypesMessage' => 'Please upload a valid Image or Video',
                                    ])
                                ],
                            ])
                    )
            )
            ->add(
                $builder->create(
                    'testimonial',
                    FormType::class,
                    [
                        'label' => 'Testimonials',
                        'required' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                        ]
                    )
                    ->add('header', TextType::class, [])
                    ->add('maxReviews',
                        NumberType::class,
                        [
                            'label' => 'Maximum Reviews',
                            'required' => false,
                            'html5' => true,
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
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
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
                            ->add('head', TextType::class, [])
                            ->add('description', TextareaType::class, [])
                            ->add('ctaBtnText',
                                TextType::class,
                                [
                                    'label' => 'Action Button Text'
                                ]
                            )
                            ->add('price', TextType::class, [])
                            ->add('CurrencySign', TextType::class, [])
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
                            ->add('head', TextType::class, [])
                            ->add('description', TextareaType::class, [])
                            ->add('ctaBtnText',
                                TextType::class,
                                [
                                    'label' => 'Action Button Text'
                                ]
                            )
                            ->add('price', TextType::class, [])
                            ->add('CurrencySign', TextType::class, [])
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
                            ->add('head', TextType::class, [])
                            ->add('description', TextareaType::class, [])
                            ->add('ctaBtnText',
                                TextType::class,
                                [
                                    'label' => 'Action Button Text'
                                ]
                            )
                            ->add('price', TextType::class, [])
                            ->add('CurrencySign', TextType::class, [])
                    )
            )
            ->add(
                $builder->create(
                    'newsletter',
                    FormType::class,
                    [
                        'label' => 'Newsletter',
                        'required' => false,
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                        ]
                    )
                    ->add('header', TextType::class, [])
                    ->add('inputFieldPlaceholder', TextType::class, [])
                    ->add('description', TextareaType::class, [])
                    ->add('ctaBtnText',
                        TextType::class,
                        [
                            'label' => 'Action Button Text'
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
                    ]
                )
                    ->add('isActive',
                        CheckboxType::class,
                        [
                            'label' => 'Enabled',
                            'row_attr' => [
                                'class' => 'form-switch px-3'
                            ],
                        ]
                    )
                    ->add('copyright', TextType::class, [])
                    ->add('privacyPolicyFull', TextareaType::class, [])
                    ->add('termsOfServiceFull', TextareaType::class, [])

                    ->add(
                        $builder->create(
                            'socialLinks',
                            FormType::class,
                            [
                                'label' => 'Social Links',
                                'required' => false,
                            ]
                        )
                            ->add('linkedIn',
                                UrlType::class,
                                [
                                    'label' => 'LinkedIn'
                                ]
                            )
                            ->add('instagram', UrlType::class, [])
                            ->add('youtube', UrlType::class, [])
                            ->add('twitter', UrlType::class, [])
                            ->add('tikTok',
                                UrlType::class,
                                [
                                    'label' => 'TikTok'
                                ]
                            )
                            ->add('facebook', UrlType::class, [])
                    )
            )
            ->add(
                $builder->create(
                    'designSettings',
                    FormType::class,
                    [
                        'label' => 'Design Settings',
                        'required' => false,
                    ]
                )
                    ->add('primary',
                        TextType::class,
                    )
                    ->add('secondary',
                        TextType::class,
                    )
                    ->add('success',
                        TextType::class,
                    )
                    ->add('error',
                        TextType::class,
                    )
                    ->add('info',
                        TextType::class,
                    )
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
//            'data_class' => Variant::class,
        ]);
    }
}
