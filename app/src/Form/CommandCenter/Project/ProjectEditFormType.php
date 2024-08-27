<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Project;

use App\Entity\Project;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectEditFormType extends ProjectFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Project $project */
        $project = $builder->getData();

        $builder
            ->add('isActive',
                ChoiceType::class,
                [
                    'placeholder' => false,
                    'data' => $project->isActive(),
                    'choices'  => [
                        'No' => false,
                        'Yes' => true,
                    ],
                ]
            )
        ;

        parent::buildForm($builder, $options);
    }
}
