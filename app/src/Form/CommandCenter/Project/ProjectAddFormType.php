<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Project;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectAddFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',
                TextType::class,
                [
                    'help' => 'Short title to easy find it and share with others.',
                ]
            )
            ->add('description',
                TextareaType::class,
                    [
                        'help' => 'Short description just for you to better understand what about this project.',
                        'required' => false,
                    ]
            )
            ->add('tags',
                TextType::class,
                    [
                        'help' => 'Separate tags via space. Short tags that will help to easy search and filter.',
                        'required' => false,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'input-tags text-secondary',
                            'data-ub-tag-separator' => ' ',
                        ],
                    ]
            )
            ->add('startAt',
                DateTimeType::class,
                [
                    'required' => false,
                    'help' => 'Automatically start show for public view all variants, if they have no other configuration.',
                ]
            )
            ->add('endAt',
                DateTimeType::class,
                [
                    'required' => false,
                    'help' => 'Automatically close from public view all variants, if they have no other configuration.',
                ]
            )
            ->add(
                $builder->create(
                    'promptVariables',
                    FormType::class,
                    [
                        'label' => 'Project metadata for content auto-generation.',
                        'required' => false,
                        'mapped' => false,
                        'label_attr' => [
                            'class' => 'text-start fw-bold',
                        ],
                    ]

                )
                    ->add('product',
                        TextType::class,
                        [
                            'help' => 'Describe your product in one sentence.',
                        ]
                    )
                    ->add('productDescription',
                        TextType::class,
                        [
                            'help' => 'Describe project with more details.',
                        ]
                    )
                    ->add('audience',
                        TextType::class,
                        [
                            'help' => 'Describe your audience with more details you can.',
                        ]
                    )
                    ->add('proposal',
                        TextType::class,
                        [
                            'help' => 'Describe proposal or unique features that user will get.',
                        ]
                    )
                    ->add('value',
                        TextareaType::class,
                        [
                            'help' => 'Short description what value audience will get from product.',
                            'required' => false,
                        ]
                    )
                    ->add('competitors',
                        TextareaType::class,
                        [
                            'label' => 'Competitors homepage links',
                            'help' => 'Each link must be from a new line.',
                            'required' => false,
                        ]
                    )
                    ->add(
                        $builder->create(
                            'tone',
                            FormType::class,
                            [
                                'label' => 'Tone of content.',
                                'required' => false,
                                'mapped' => false,
                                'attr' => [
                                    'class' => 'd-flex align-items-center flex-row justify-content-start flex-wrap',
                                ],
                            ]

                        )
                            ->add('formal',
                                CheckboxType::class,
                                [
                                    'label'    => 'formal',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('casual',
                                CheckboxType::class,
                                [
                                    'label'    => 'casual',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('authoritative',
                                CheckboxType::class,
                                [
                                    'label'    => 'authoritative',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('friendly',
                                CheckboxType::class,
                                [
                                    'label'    => 'friendly',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('creative',
                                CheckboxType::class,
                                [
                                    'label'    => 'creative',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('business',
                                CheckboxType::class,
                                [
                                    'label'    => 'business',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('convincing',
                                CheckboxType::class,
                                [
                                    'label'    => 'convincing',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('urgent',
                                CheckboxType::class,
                                [
                                    'label'    => 'urgent',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('persuasive',
                                CheckboxType::class,
                                [
                                    'label'    => 'persuasive',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )

                    )
                    ->add(
                        $builder->create(
                            'style',
                            FormType::class,
                            [
                                'label' => 'Style of content.',
                                'required' => false,
                                'mapped' => false,
                                'attr' => [
                                    'class' => 'd-flex align-items-center flex-row justify-content-start flex-wrap',
                                ],
                            ]

                        )
                            ->add('humor',
                                CheckboxType::class,
                                [
                                    'label'    => 'humor',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('straightforward',
                                CheckboxType::class,
                                [
                                    'label'    => 'straightforward',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('promotional',
                                CheckboxType::class,
                                [
                                    'label'    => 'promotional',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('gamer',
                                CheckboxType::class,
                                [
                                    'label'    => 'gamer',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('childlike',
                                CheckboxType::class,
                                [
                                    'label'    => 'childlike',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('pessimistic',
                                CheckboxType::class,
                                [
                                    'label'    => 'pessimistic',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                            ->add('optimistic',
                                CheckboxType::class,
                                [
                                    'label'    => 'optimistic',
                                    'label_attr' => [
                                        'class' => 'me-3',
                                    ]
                                ]
                            )
                    )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
