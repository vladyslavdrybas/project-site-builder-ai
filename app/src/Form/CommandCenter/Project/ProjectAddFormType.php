<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Project;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('startAt',
                DateTimeType::class,
                [
                    'required' => false
                ]
            )
            ->add('endAt',
                DateTimeType::class,
                [
                    'required' => false
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
                            'help' => 'Describe proposal or features that user will get.',
                        ]
                    )
                    ->add('value',
                        TextareaType::class,
                        [
                            'help' => 'Short description what value audience will get from using product.',
                            'required' => false,
                        ]
                    )
                    ->add('tone',
                        ChoiceType::class,
                        [
                            'help' => 'Tone of content.',
                            'choices'  => [
                                'formal' => 'formal',
                                'casual' => 'casual',
                                'authoritative' => 'authoritative',
                                'friendly' => 'friendly',
                                'creative' => 'creative',
                                'business' => 'business',
                                'convincing' => 'convincing',
                                'urgent' => 'urgent',
                                'persuasive' => 'persuasive',
                            ],
                            'multiple' => true,
                        ]
                    )
                    ->add('style',
                        ChoiceType::class,
                        [
                            'help' => 'Style of content.',
                            'choices'  => [
                                'humor' => 'humor',
                                'straightforward' => 'straightforward',
                                'promotional' => 'promotional',
                                'gaming' => 'gaming',
                                'childlike' => 'childlike',
                                'bright' => 'bright',
                                'optimistic' => 'optimistic',
                                'pessimistic' => 'pessimistic',
                                'depressive' => 'depressive',
                                'gayish' => 'gayish',
                                'army' => 'army',
                            ],
                            'multiple' => true,
                        ]
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
