<?php
declare(strict_types=1);

namespace App\Form;

use App\DataTransformer\TagToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagsType extends AbstractType
{
    public function __construct(
        protected readonly TagToStringTransformer $tagToStringTransformer
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->tagToStringTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'help' => 'Separate tags via space. Short tags that will help to easy search and filter.',
            'required' => false,
            'mapped' => false,
            'attr' => [
                'class' => 'input-tags text-secondary',
                'data-ub-tag-separator' => ' ',
            ],
        ]);
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
