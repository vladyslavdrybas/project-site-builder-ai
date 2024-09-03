<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\Builder\SectionWithDescriptionItemsFormDto;
use App\Form\DescriptionType;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionWithDescriptionItemsFormType extends AbstractType
{
    public const ITEMS_AMOUNT = 3;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var SectionWithDescriptionItemsFormDto $data */
        $data = $builder->getData();

        $partLabel = ucfirst(str_replace(['-', '_'], ' ', $data->itemKeyName));

        $items = $builder->create(
            'items',
            FormType::class,
            [
                'label' => $partLabel,
                'required' => false,
            ]
        );

        $dataItems = $data?->items ?? [];

        $len = self::ITEMS_AMOUNT;
        if (null !== $data && count($data->items) > 0) {
            $len = count($data->items);
        }

        $itemKey = $data->itemKeyName;
        if (array_is_list($dataItems) && count($dataItems) > 0) {
            $itemKey = null;
        }

        for ($i = 0; $i < $len; $i++) {
            if (null !== $itemKey) {
                $key = $data->itemKeyName . ($i + 1);
            } else {
                $key = $i;
            }

            $items
                ->add(
                    (string) $key,
                    DescriptionFormType::class,
                    [
                        'label' => $partLabel . ' ' . ($i + 1),
                        'required' => false,
                        'data' => $data?->items[$key] ?? $data?->items[$i] ?? null,
                    ]
                )
            ;
        }

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
            ->add($items)
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SectionWithDescriptionItemsFormDto::class,
        ]);
    }
}
