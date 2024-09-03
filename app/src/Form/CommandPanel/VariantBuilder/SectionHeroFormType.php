<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\Builder\SectionHeroFormDto;
use App\Form\DescriptionType;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionHeroFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var SectionHeroFormDto $data */
        $data = $builder->getData();

        $builder
            ->add('isActive',
                SwitchType::class,
                [
                    'label' => 'On',
                    'data' => $data?->isActive
                ]
            )
            ->add('headline',
                TextType::class,
                [
                    'data' => $data?->headline,
                ]
            )
            ->add('subheadline',
                DescriptionType::class,
                [
                    'data' => $data?->subheadline,
                ]
            )
            ->add('callToActionButton',
                CallToActionButtonFormType::class,
                [
                    'label' => 'Action Button',
                    'data' => $data?->callToActionButton
                ]
            )
            ->add('media',
                MediaCreatorFormType::class,
                [
                    'label' => 'Presentation Image',
                    'required' => false,
                    'data' => $data?->media,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SectionHeroFormDto::class,
        ]);
    }
}
