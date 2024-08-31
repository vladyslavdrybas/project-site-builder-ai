<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Variant;

use App\DataTransferObject\Variant\AddWithAiFormDto;
use App\Entity\Project;
use App\Form\SwitchFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariantAddWithAiFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var AddWithAiFormDto $data */
        $data = $builder->getData();

        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choices' => $data->projects,
                'choice_label' => function (Project $project) {
                    $suffix = explode('-', $project->getRawId());
                    $suffix = $suffix[count($suffix) - 1];

                    return $project->getTitle() . ' | ' . $suffix;
                },
                'placeholder' => 'Select an Project',
                'required' => true,
                'empty_data' => null,
                'label' => 'Select your project',
                'help' => 'I need prompt metadata of the project to start content generation.',
            ])
            ->add(
                $builder->create(
                    'parts',
                    FormType::class,
                    [
                        'label' => 'Select parts you want to use on you page',
                        'required' => false,
                        'label_attr' => [
                            'class' => 'text-start fw-bold',
                        ],
                    ]
                )
                    ->add('header',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['header'] ?? null,
                        ]
                    )
                    ->add('heroWithCallToAction',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['heroWithCallToAction'] ?? null,
                        ]
                    )
                    ->add('features',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['features'] ?? null,
                        ]
                    )
                    ->add('whoUseIt',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['whoUseIt'] ?? null,
                        ]
                    )
                    ->add('reasonsToUse',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['reasonsToUse'] ?? null,
                        ]
                    )
                    ->add('howItWorks',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['howItWorks'] ?? null,
                        ]
                    )
                    ->add('partners',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['partners'] ?? null,
                        ]
                    )
                    ->add('testimonials',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['testimonials'] ?? null,
                        ]
                    )
                    ->add('workExample',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['workExample'] ?? null,
                        ]
                    )
                    ->add('subscriptionPlans',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['subscriptionPlans'] ?? null,
                        ]
                    )
                    ->add('productPrice',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['productPrice'] ?? null,
                        ]
                    )
                    ->add('newsletterSubscription',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['newsletterSubscription'] ?? null,
                        ]
                    )
                    ->add('aboutUs',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['aboutUs'] ?? null,
                        ]
                    )
                    ->add('frequentlyAskedQuestions',
                        SwitchFormType::class,
                        [
                            'data' => $data?->parts['frequentlyAskedQuestions'] ?? null,
                        ]
                    )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddWithAiFormDto::class,
        ]);
    }
}
