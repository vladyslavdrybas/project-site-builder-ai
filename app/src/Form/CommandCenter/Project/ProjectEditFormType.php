<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Project;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Project $project */
        $project = $builder->getData();

        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('proposal', TextareaType::class, ['required' => false])
            ->add('customerPortrait', TextareaType::class, ['required' => false])
            ->add('startAt', DateTimeType::class, ['required' => false])
            ->add('endAt', DateTimeType::class, ['required' => false])
            ->add('isActive', ChoiceType::class, [
                'choices'  => [
                    'No' => false,
                    'Yes' => true,
                ],
                'data' => $project->isActive(),
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'save btn btn-primary rounded-5'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
