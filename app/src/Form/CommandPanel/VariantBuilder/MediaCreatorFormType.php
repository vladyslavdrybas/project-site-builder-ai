<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\Builder\MediaCreatorFormDto;
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
                $formData = $event->getData();
                $form = $event->getForm();

                if ($form['removeBtn']->isClicked()) {
                    $formData->remove = true;
                }
            }
        );

        $builder
            ->add('file',
                ImageType::class,
                [
                    'mapped' => true,
                    'label' => 'Upload image',
                ]
            )
            ->add('stock',
                ImageFromStocksType::class,
                [
                    'data' => $data?->stockKeywords,
                ]
            )
            ->add('systemId',
                HiddenType::class,
                [
                    'data' => $data?->systemId
                ]
            )
            ->add('removeBtn',
                SubmitType::class,
                [
                    'label' => 'Remove',
                    'attr' => [
                        'class' => 'btn-sm btn-light'
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
