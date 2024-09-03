<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\Builder\SectionFooterFormDto;
use App\Form\DescriptionType;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionFooterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var SectionFooterFormDto $data */
        $data = $builder->getData();

        $builder
            ->add('isActive',
                SwitchType::class,
                [
                    'label' => 'On',
                    'data' => $data?->isActive
                ]
            )
            ->add('copyright',
                TextType::class,
                [
                    'data' => $data?->copyright,
                ]
            )
            ->add('privacyPolicyFull',
                DescriptionType::class,
                [
                    'data' => $data?->privacyPolicyFull,
                ]
            )
            ->add('termsOfServiceFull',
                DescriptionType::class,
                [
                    'data' => $data?->termsOfServiceFull,
                ]
            )

            ->add(
                $builder->create(
                    'socialLinks',
                    FormType::class,
                    [
                        'label' => 'Social Links',
                        'required' => false,
                    ]
                )
                    ->add('discord',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['discord'] ?? null,
                        ]
                    )
                    ->add('facebook',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['facebook'] ?? null,
                        ]
                    )
                    ->add('github',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['github'] ?? null,
                        ]
                    )
                    ->add('instagram',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['instagram'] ?? null,
                        ]
                    )
                    ->add('linkedIn',
                        TextType::class,
                        [
                            'label' => 'LinkedIn',
                            'data' => $data->socialLinks['linkedIn'] ?? null,
                        ]
                    )
                    ->add('medium',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['medium'] ?? null,
                        ]
                    )
                    ->add('reddit',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['reddit'] ?? null,
                        ]
                    )
                    ->add('telegram',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['telegram'] ?? null,
                        ]
                    )
                    ->add('youtube',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['youtube'] ?? null,
                        ]
                    )
                    ->add('twitter',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['twitter'] ?? null,
                        ]
                    )
                    ->add('tikTok',
                        TextType::class,
                        [
                            'label' => 'TikTok',
                            'data' => $data->socialLinks['tikTok'] ?? null,
                        ]
                    )
                    ->add('twitter',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['twitter'] ?? null,
                        ]
                    )
                    ->add('youtube',
                        TextType::class,
                        [
                            'data' => $data->socialLinks['youtube'] ?? null,
                        ]
                    )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SectionFooterFormDto::class,
        ]);
    }
}
