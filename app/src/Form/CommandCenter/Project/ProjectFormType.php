<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Project;

use App\Entity\Project;
use App\Entity\Tag;
use App\Form\CommandCenter\PromptMetaFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Project $project */
        $project = $builder->getData();

        $builder
            ->add('title',
                TextType::class,
                [
                    'help' => 'Short title to easy find it and share with others.',
                    'data' => $project->getTitle()
                ]
            )
            ->add('description',
                TextareaType::class,
                [
                    'help' => 'Short description just for you to better understand what about this project.',
                    'required' => false,
                    'data' => $project->getDescription()
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
                    'data' => $project->getTags()->reduce(function(string $result, Tag $a) {
                            return $result . ' ' . $a->getRawId();
                        }, '') ?? null
                ]
            )
            ->add('startAt',
                DateTimeType::class,
                [
                    'required' => false,
                    'help' => 'Automatically start show for public view all variants, if they have no other configuration.',
                    'data' => $project->getStartAt()
                ]
            )
            ->add('endAt',
                DateTimeType::class,
                [
                    'required' => false,
                    'help' => 'Automatically close from public view all variants, if they have no other configuration.',
                    'data' => $project->getEndAt()
                ]
            )
            ->add(
                $builder->create(
                    'promptMeta',
                    PromptMetaFormType::class,
                    [
                        'label' => 'Project metadata for content auto-generation.',
                        'required' => false,
                        'label_attr' => [
                            'class' => 'text-start fw-bold',
                        ],
                        'data' => $project->getPromptMeta() ?? null
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
