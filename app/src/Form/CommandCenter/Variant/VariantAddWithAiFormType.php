<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Variant;

use App\DataTransferObject\Variant\AddWithAiFormDto;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddWithAiFormDto::class,
        ]);
    }
}
