<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Variant;

use App\Entity\Project;
use App\Entity\User;
use App\Entity\Variant;
use App\Exceptions\AccessDenied;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariantEditFormType extends AbstractType
{
    public function __construct(
        protected readonly Security $security
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new AccessDenied();
        }

        /** @var Variant $variant */
        $variant = $builder->getData();

        $plainMeta = null === $variant->getMeta() ? '' : json_encode($variant->getMeta());

        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, [
                'required' => false,
                'data' => $variant->getDescription(),
            ])

            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choices' => $user->getProjects(),
                'choice_label' => function (Project $project) {
                    $suffix = explode('-', $project->getRawId());
                    $suffix = $suffix[count($suffix) - 1];

                    return $project->getTitle() . ' | ' . $suffix;
                }, // Adjust this to the appropriate field of the DependentEntity
                'placeholder' => 'Select an Project',
                'required' => true,
                'empty_data' => $variant->getProject(), // Ensure no default value is selected
            ])
            ->add('startAt', DateTimeType::class, ['required' => false])
            ->add('endAt', DateTimeType::class, ['required' => false])
            ->add('isActive', ChoiceType::class, [
                'choices'  => [
                    'No' => false,
                    'Yes' => true,
                ],
                'data' => $variant->isActive(),
            ])
            ->add('weight', NumberType::class, [
                'data' => $variant->getWeight(),
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ]
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
            'data_class' => Variant::class,
        ]);
    }
}
