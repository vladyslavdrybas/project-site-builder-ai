<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\Builder\VariantBuilderFormDto;
use App\DataTransferObject\Variant\Meta\VariantMetaDto;
use App\Form\DescriptionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VariantBuilderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var VariantBuilderFormDto $data */
        $data = $builder->getData();

        $builder
            ->add('variantId',
                HiddenType::class,
                [
                    'data' => $data->variantId,
                ]
            )
            ->add('projectId',
                HiddenType::class,
                [
                    'data' => $data->projectId,
                ]
            )
            ->add('header',
                SectionHeaderFormType::class,
                [
                    'label' => 'Header',
                    'required' => false,
                    'data' => $data->header,
                ]
            )
            ->add('hero',
                SectionHeroFormType::class,
                [
                    'label' => 'Hero',
                    'required' => false,
                    'data' => $data->hero,
                ]
            )
            ->add('features',
                SectionWithDescriptionItemsFormType::class,
                [
                    'label' => 'Features',
                    'required' => false,
                    'data' => $data->features,
                ]
            )
            ->add('howitworks',
                SectionWithDescriptionItemsFormType::class,
                [
                    'label' => 'Features',
                    'required' => false,
                    'data' => $data->howitworks,
                ]
            )
            ->add('testimonial',
                SectionWithDescriptionItemsFormType::class,
                [
                    'label' => 'Testimonial',
                    'required' => false,
                    'data' => $data->testimonial,
                ]
            )
            ->add('subscriptions',
                SectionSubscriptionsFormType::class,
                [
                    'label' => 'Subscriptions',
                    'required' => false,
                    'data' => $data->subscriptions,
                ]
            )
            ->add('newsletter',
                SectionNewsletterFormType::class,
                [
                    'label' => 'Newsletter',
                    'required' => false,
                    'data' => $data->newsletter,
                ]
            )
            ->add('footer',
                SectionFooterFormType::class,
                [
                    'label' => 'Footer',
                    'required' => false,
                    'data' => $data->footer,
                ]
            )
            ->add(
                'backBtn',
                SubmitType::class,
                [
                    'label' => 'Back',
                    'attr' => [
                        'class' => 'btn btn-light w-100',
                    ],
                ]
            )
            ->add(
                'cancelBtn',
                SubmitType::class,
                [
                    'label' => 'Cancel',
                    'attr' => [
                        'class' => 'btn btn-light w-100',
                    ],
                ]
            )
            ->add(
                'saveBtn',
                SubmitType::class,
                [
                    'label' => 'Save',
                    'attr' => [
                        'class' => 'btn btn-light w-100',
                    ],
                ]
            )
            ->add(
                'previewBtn',
                SubmitType::class,
                [
                    'label' => 'Preview',
                    'attr' => [
                        'class' => 'btn btn-light w-100',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VariantBuilderFormDto::class,
        ]);
    }
}
