<?php

namespace App\DataFixtures;

use App\Builder\UserBuilder;
use App\DataTransferObject\Variant\Meta\BrandDto;
use App\DataTransferObject\Variant\Meta\CallToActionButtonDto;
use App\DataTransferObject\Variant\Meta\DesignSettingsDto;
use App\DataTransferObject\Variant\Meta\FeatureDto;
use App\DataTransferObject\Variant\Meta\FeaturesPartDataDto;
use App\DataTransferObject\Variant\Meta\FeaturesPartDto;
use App\DataTransferObject\Variant\Meta\FooterPartDto;
use App\DataTransferObject\Variant\Meta\HeaderPartDataDto;
use App\DataTransferObject\Variant\Meta\HeaderPartDto;
use App\DataTransferObject\Variant\Meta\HeroPartDataDto;
use App\DataTransferObject\Variant\Meta\HeroPartDto;
use App\DataTransferObject\Variant\Meta\HowItWorksDto;
use App\DataTransferObject\Variant\Meta\HowItWorksPartDataDto;
use App\DataTransferObject\Variant\Meta\HowItWorksPartDto;
use App\DataTransferObject\Variant\Meta\NewsletterPartDto;
use App\DataTransferObject\Variant\Meta\PartsDto;
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDataDto;
use App\DataTransferObject\Variant\Meta\SubscriptionsPartDto;
use App\DataTransferObject\Variant\Meta\TestimonialPartDto;
use App\DataTransferObject\Variant\Meta\VariantMetaDto;
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
        protected readonly SerializerInterface $serializer
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $variants = json_decode(file_get_contents(__DIR__ . '/data/variants.json'), true);
        $productDescription = trim(file_get_contents(__DIR__ . '/data/product_description.txt'));
        $targetAudience = trim(file_get_contents(__DIR__ . '/data/target_audience.txt'));

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
            $project->setCustomerPortrait($targetAudience);

            $manager->persist($project);

            $variant = new Variant();
            $variant->setTitle($variantArray['variantTitle']);
            $variant->setProject($project);

            $variant = $this->buildVariantMetaFromArray($project, $variant, $variantArray);

            $manager->persist($variant);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    protected function buildVariantMetaFromArray(
        Project $project,
        Variant $variant,
        array $data
    ): Variant {

        $header = new HeaderPartDto(
            new HeaderPartDataDto(
                new BrandDto(
                    null,
                    $data['parts']['header']['data']['brand']['text'] ?? null,
                ),
                new CallToActionButtonDto(
                    $data['parts']['header']['data']['callToActionButton']['text'] ?? null,
                    '#pricing'
                ),
                $data['parts']['header']['data']['navigation']
            ),
            true,
        );

        $hero = new HeroPartDto(
            new HeroPartDataDto(
                $data['parts']['hero']['data']['headline'] ?? null,
                $data['parts']['hero']['data']['subheadline'] ?? null,
                new CallToActionButtonDto(
                    $data['parts']['hero']['data']['callToActionButton']['text'] ?? null,
                    '#pricing'
                ),
            ),
            true
        );

        $features = new FeaturesPartDto(
            new FeaturesPartDataDto(
                $data['parts']['features']['data']['headline'] ?? null,
                [
                    'feature1' => new FeatureDto(
                        $data['parts']['features']['data']['items']['feature1']['headline'] ?? null,
                        $data['parts']['features']['data']['items']['feature1']['description'] ?? null
                    ),
                    'feature2' => new FeatureDto(
                        $data['parts']['features']['data']['items']['feature2']['headline'] ?? null,
                        $data['parts']['features']['data']['items']['feature2']['description'] ?? null
                    ),
                    'feature3' => new FeatureDto(
                        $data['parts']['features']['data']['items']['feature3']['headline'] ?? null,
                        $data['parts']['features']['data']['items']['feature3']['description'] ?? null
                    ),
                ]
            ),
            true
        );

        $howitworks = new HowItWorksPartDto(
            new HowItWorksPartDataDto(
                $data['parts']['howitworks']['data']['headline'] ?? null,
                [
                    'step1' => new HowItWorksDto(
                        $data['parts']['howitworks']['data']['items']['step1']['headline'] ?? null,
                        $data['parts']['howitworks']['data']['items']['step1']['description'] ?? null
                    ),
                    'step2' => new HowItWorksDto(
                        $data['parts']['howitworks']['data']['items']['step2']['headline'] ?? null,
                        $data['parts']['howitworks']['data']['items']['step2']['description'] ?? null
                    ),
                    'step3' => new HowItWorksDto(
                        $data['parts']['howitworks']['data']['items']['step3']['headline'] ?? null,
                        $data['parts']['howitworks']['data']['items']['step3']['description'] ?? null
                    ),
                ]
            ),
            true
        );

        $testimonial = new TestimonialPartDto(
            $data['parts']['testimonials']['headline'] ?? null,
            (int) $data['parts']['testimonials']['maxReviews'],
            $data['parts']['testimonials']['items'],
            true
        );

        $pricing = new SubscriptionsPartDto(
            new SubscriptionsPartDataDto(
                $data['parts']['productSubscriptions']['data']['headline'] ?? null,
                [
                    'plan1' => [
                        'head' => $data['parts']['productSubscriptions']['data']['items']['plan1']['headline'] ?? null,
                        'description' => $data['parts']['productSubscriptions']['data']['items']['plan1']['features'] ?? null,
                        'ctaBtnText' => $data['parts']['productSubscriptions']['data']['items']['plan1']['callToActionButton']['text'] ?? null,
                        'price' => $data['parts']['productSubscriptions']['data']['items']['plan1']['price'],
                        'currencySign' => $data['parts']['productSubscriptions']['data']['items']['plan1']['currencySign'],
                    ],
                    'plan2' => [
                        'head' => $data['parts']['productSubscriptions']['data']['items']['plan2']['headline'] ?? null,
                        'description' => $data['parts']['productSubscriptions']['data']['items']['plan2']['features'] ?? null,
                        'ctaBtnText' => $data['parts']['productSubscriptions']['data']['items']['plan2']['callToActionButton']['text'] ?? null,
                        'price' => $data['parts']['productSubscriptions']['data']['items']['plan2']['price'],
                        'currencySign' => $data['parts']['productSubscriptions']['data']['items']['plan2']['currencySign'],
                    ],
                    'plan3' => [
                        'head' => $data['parts']['productSubscriptions']['data']['items']['plan3']['headline'] ?? null,
                        'description' => $data['parts']['productSubscriptions']['data']['items']['plan3']['features'] ?? null,
                        'ctaBtnText' => $data['parts']['productSubscriptions']['data']['items']['plan3']['callToActionButton']['text'] ?? null,
                        'price' => $data['parts']['productSubscriptions']['data']['items']['plan3']['price'],
                        'currencySign' => $data['parts']['productSubscriptions']['data']['items']['plan3']['currencySign'],
                    ],
                ]
            ),
            true
        );

        $newsletter = new NewsletterPartDto(
            $data['parts']['newsletter']['headline'] ?? null,
            $data['parts']['newsletter']['subheadline'] ?? null,
            $data['parts']['newsletter']['inputFieldPlaceholder'] ?? null,
            new CallToActionButtonDto(
                $data['parts']['newsletter']['callToActionButton']['text'] ?? null,
            ),
            true
        );

        $footer = new FooterPartDto(
            $data['variantTitle'],
            null,
            null,
            [
                'linkedIn' => '#linkedIn'
            ],
            true
        );

        $parts = new PartsDto(
            $header,
            $hero,
            $features,
            $howitworks,
            $testimonial,
            $pricing,
            $newsletter,
            $footer,
        );

        $design = new DesignSettingsDto();

        $meta = new VariantMetaDto(
            $variant->getRawId(),
            $project->getRawId(),
            $parts,
            $design
        );

        $variantMetaArray = $this->serializer->normalize($meta);

        $variant->setMeta($variantMetaArray);

        return $variant;
    }
}
