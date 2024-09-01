<?php

namespace App\DataFixtures;

use App\Builder\UserBuilder;
use App\Builder\VariantBuilder;
use App\Entity\Project;
use App\Entity\Variant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Serializer\SerializerInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        protected readonly UserBuilder $userBuilder,
        protected readonly VariantBuilder $variantBuilder,
        protected readonly SerializerInterface $serializer
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $variants = json_decode(file_get_contents(__DIR__ . '/data/variants.json'), true);
        $productDescription = trim(file_get_contents(__DIR__ . '/data/product_description.txt'));

        $user = $this->userBuilder->base(
            $faker->email(),
            '123123'
        );

        $manager->persist($user);

        foreach ($variants as $variantArray) {
            $project = new Project();
            $project->setOwner($user);
            $project->setTitle($variantArray['projectTitle']);
            $project->setDescription($productDescription);

            $manager->persist($project);

            $variant = new Variant();
            $variant->setTitle($variantArray['variantTitle']);
            $variant->setProject($project);

            $variant = $this->buildVariantMetaFromArray($variant, $variantArray);

            $manager->persist($variant);
        }

        $manager->flush();
    }

    protected function buildVariantMetaFromArray(
        Variant $variant,
        array $data
    ): Variant {
        return $this->variantBuilder->buildVariantMetaFromPromptArray($variant, $data);
    }
}
