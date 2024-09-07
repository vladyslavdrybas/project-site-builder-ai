<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\Builder\MediaCreatorFormDto;
use App\Form\HiddenBooleanType;
use App\Form\ImageFromStocksType;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaCreatorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var MediaCreatorFormDto $data */
        $data = $builder->getData();

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var MediaCreatorFormDto $formData */
                $formData = $event->getData();
                $form = $event->getForm();

                if ($form['removeBtn']->isClicked()) {
                    $formData->toRemove = true;
                } else if ($form['generateBtn']->isClicked()) {
                    $formData->toGenerate = true;
                } else if ($form['getFromStockBtn']->isClicked()) {
                    $formData->toGetFromStock = true;
                }
            }
        );

        $builder
            ->add('toRemove', HiddenBooleanType::class, ['data' => false])
            ->add('toGenerate', HiddenBooleanType::class, ['data' => false])
            ->add('toGetFromStock', HiddenBooleanType::class, ['data' => false])
            ->add('systemId',
                HiddenType::class,
                [
                    'data' => $data?->systemId
                ]
            )
            ->add('file',
                ImageType::class,
                [
                    'mapped' => true,
                    'label' => 'Upload image',
                ]
            )
            ->add('stockTags',
                ImageFromStocksType::class,
                [
                    'mapped' => true,
                    'data' => $data?->stockTags,
                ]
            )
            ->add('getFromStockBtn',
                SubmitType::class,
                [
                    'label' => 'Get random stock image',
                    'attr' => [
                        'class' => 'btn-sm btn-dark formSubmit btn-media-submit'
                    ],
                ]
            )
            ->add('generateBtn',
                SubmitType::class,
                [
                    'label' => 'Generate Image',
                    'attr' => [
                        'class' => 'btn-sm btn-dark formSubmit btn-media-submit'
                    ]
                ]
            )
            ->add('removeBtn',
                SubmitType::class,
                [
                    'label' => 'Remove',
                    'attr' => [
                        'class' => 'btn-sm btn-dark formSubmit btn-media-submit'
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MediaCreatorFormDto::class,
        ]);
    }
}
