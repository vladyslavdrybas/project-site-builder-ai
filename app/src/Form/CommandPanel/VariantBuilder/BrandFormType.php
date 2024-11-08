<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\Builder\BrandFormDto;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrandFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var BrandFormDto $data */
        $data = $builder->getData();

        $builder
            ->add('isVisible',
                SwitchType::class,
                [
                    'label' => 'Show on page',
                    'data' => $data?->isVisible
                ]
            )
            ->add('name',
                TextType::class,
                [
                    'data' => $data?->name
                ]
            )
            ->add('logo',
                MediaCreatorFormType::class,
                [
                    'required' => false,
                    'data' => $data?->logo,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BrandFormDto::class,
        ]);
    }
}
