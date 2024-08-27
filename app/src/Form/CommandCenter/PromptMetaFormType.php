<?php
declare(strict_types=1);

namespace App\Form\CommandCenter;

use App\DataTransferObject\PromptMetaDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromptMetaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var PromptMetaDto $dto */
        $dto = $builder->getData();

        $builder->add('productShortDescription',
            TextType::class,
                [
                    'label' => 'Describe your product in one sentence.',
                    'help' => 'example: SaaS platform to generate landing pages to create audience and test ideas.',
                    'data' => $dto?->productShortDescription,
                ]
            )
            ->add('productDescription',
                TextType::class,
                [
                    'label' => 'Describe project with more details.',
                    'data' => $dto?->productDescription,
                ]
            )
            ->add('targetAudience',
                TextType::class,
                [
                    'label' => 'Describe your audience. Portrait of your customer',
                    'help' => 'example: people who have a bunch of ideas, but have no time or do not know how to test them before start development.',
                    'data' => $dto?->targetAudience,
                ]
            )
            ->add('proposal',
                TextType::class,
                [
                    'label' => 'Describe proposal or unique features that user will get.',
                    'help' => 'example: you can fast and easy generate content and start promoting your idea to collect audience and find collaborators.',
                    'data' => $dto?->proposal,

                ]
            )
            ->add('value',
                TextareaType::class,
                [
                    'label' => 'What value audience will get from product.',
                    'help' => 'example: you can get a product audience before spending dozens of thousands on development.',
                    'data' => $dto?->value,
                ]
            )
            ->add('competitors',
                TextareaType::class,
                [
                    'label' => 'Competitors homepage links',
                    'help' => 'Each link must be from a new line.',
                    'data' => $dto?->competitors,
                ]
            )
            ->add(
                $builder->create(
                    'tone',
                    FormType::class,
                    [
                        'label' => 'Tone of content.',
                        'attr' => [
                            'class' => 'd-flex align-items-center flex-row justify-content-start flex-wrap',
                        ],
                        'mapped' => false,
                    ]

                )
                    ->add('formal',
                        CheckboxType::class,
                        [
                            'label'    => 'formal',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->tone['formal'] ?? null,
                        ]
                    )
                    ->add('casual',
                        CheckboxType::class,
                        [
                            'label'    => 'casual',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->tone['casual'] ?? null,
                        ]
                    )
                    ->add('authoritative',
                        CheckboxType::class,
                        [
                            'label'    => 'authoritative',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->tone['authoritative'] ?? null,
                        ]
                    )
                    ->add('friendly',
                        CheckboxType::class,
                        [
                            'label'    => 'friendly',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->tone['friendly'] ?? null,
                        ]
                    )
                    ->add('creative',
                        CheckboxType::class,
                        [
                            'label'    => 'creative',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->tone['creative'] ?? null,
                        ]
                    )
                    ->add('business',
                        CheckboxType::class,
                        [
                            'label'    => 'business',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->tone['business'] ?? null,
                        ]
                    )
                    ->add('convincing',
                        CheckboxType::class,
                        [
                            'label'    => 'convincing',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->tone['convincing'] ?? null,
                        ]
                    )
                    ->add('urgent',
                        CheckboxType::class,
                        [
                            'label'    => 'urgent',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->tone['urgent'] ?? null,
                        ]
                    )
                    ->add('persuasive',
                        CheckboxType::class,
                        [
                            'label'    => 'persuasive',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->tone['persuasive'] ?? null,
                        ]
                    )
            )
            ->add(
                $builder->create(
                    'style',
                    FormType::class,
                    [
                        'label' => 'Style of content.',
                        'attr' => [
                            'class' => 'd-flex align-items-center flex-row justify-content-start flex-wrap',
                        ],
                        'mapped' => false,
                    ]

                )
                    ->add('humor',
                        CheckboxType::class,
                        [
                            'label'    => 'humor',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->style['humor'] ?? null,
                        ]
                    )
                    ->add('straightforward',
                        CheckboxType::class,
                        [
                            'label'    => 'straightforward',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->style['straightforward'] ?? null,
                        ]
                    )
                    ->add('promotional',
                        CheckboxType::class,
                        [
                            'label'    => 'promotional',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->style['promotional'] ?? null,
                        ]
                    )
                    ->add('gamer',
                        CheckboxType::class,
                        [
                            'label'    => 'gamer',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->style['gamer'] ?? null,
                        ]
                    )
                    ->add('childlike',
                        CheckboxType::class,
                        [
                            'label'    => 'childlike',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->style['childlike'] ?? null,
                        ]
                    )
                    ->add('pessimistic',
                        CheckboxType::class,
                        [
                            'label'    => 'pessimistic',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->style['pessimistic'] ?? null,
                        ]
                    )
                    ->add('optimistic',
                        CheckboxType::class,
                        [
                            'label'    => 'optimistic',
                            'label_attr' => [
                                'class' => 'me-3',
                            ],
                            'data' => $dto?->style['optimistic'] ?? null,
                        ]
                    )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PromptMetaDto::class,
        ]);
    }
}
