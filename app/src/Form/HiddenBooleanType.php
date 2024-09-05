<?php
declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HiddenBooleanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function (mixed $value) use ($builder): bool {
                // transform the builder data to a boolean
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            },
            function (mixed $value) use ($builder): bool {
                // transform the input data back to a boolean
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('attr', [ 'class' => 'd-none']);
    }

    public function getParent(): string
    {
        return HiddenType::class;
    }
}
