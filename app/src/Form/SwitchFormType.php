<?php
declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SwitchFormType extends CheckboxType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('label_attr', [ 'class' => 'me-3']);
        $resolver->setDefault('row_attr', [ 'class' => 'form-switch ps-4 switch-form-type']);
        $resolver->setDefault('attr', [ 'class' => 'me-1']);
    }
}
