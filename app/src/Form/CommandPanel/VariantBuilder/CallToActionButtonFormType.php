<?php
declare(strict_types=1);

namespace App\Form\CommandPanel\VariantBuilder;

use App\DataTransferObject\Variant\CallToActionButtonDto;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CallToActionButtonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
            ->add('isVisible',
                SwitchType::class,
                [
                    'label' => 'Show on page',
                    'data' => $data?->isVisible
                ]
            )
            ->add('text',
            TextType::class,
                [
                    'label' => 'Text',
                    'data' => $data?->text
                ]
            )
            ->add('link',
            TextType::class,
                [
                    'label' => 'Link',
                    'data' => $data?->link
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CallToActionButtonDto::class,
        ]);
    }
}
