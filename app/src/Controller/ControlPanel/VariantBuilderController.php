<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\Entity\Variant;
use App\Form\CommandCenter\VariantBuilder\VariantBuilderFormType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/v",
    name: "cp_variant",
    requirements: [
        'variant' => RouteRequirements::UUID->value
    ]
)]
class VariantBuilderController extends AbstractControlPanelController
{
    #[Route(
        path: '/{variant}/builder',
        name: '_builder',
        methods: ['GET', 'POST']
    )]
    public function show(
        Request $request,
        Variant $variant
    ): Response {

        $builderForm = $this->createForm(VariantBuilderFormType::class, $variant->getMeta());

        $builderForm->handleRequest($request);

        $data = $this->getVariantMeta($request, $variant);

        if ($builderForm->isSubmitted() && $builderForm->isValid()) {
            dump('Submitted');
            dump($builderForm->getData());

            dump([
               $builderForm->get('saveBtn')->isClicked(),
               $builderForm->get('previewBtn')->isClicked(),
            ]);



            if ($builderForm->get('saveBtn')->isClicked()) {

            }

            if ($builderForm->get('previewBtn')->isClicked()) {
                $this->previewBtnHandler(
                    $request,
                    $variant
                );
            }

        }

        return $this->render(
            'control-panel/variant/builder/index.html.twig',
            [
                'variant' => $variant,
                'builderForm' => $builderForm,
                'data' => $data,
            ]
        );
    }

    #[Route(
        path: '/{variant}/preview',
        name: '_preview',
        methods: ['GET']
    )]
    public function preview(
        Request $request,
        Variant $variant
    ): Response {
        $data = $this->getVariantMeta($request, $variant);

        dump($data);

        return $this->render(
            '_parts/base/base.html.twig',
            [
                'variant' => $variant,
                'data' => $data,
            ]
        );
    }

    protected function saveBtnHandler(
        Variant $variant
    ): void {

    }

    protected function previewBtnHandler(
        Request $request,
        Variant $variant
    ): void {
        $request->getSession()->set(
            'variantBuilderData',
            $this->builderData($variant)
        );


    }

    protected function getVariantMeta(
        Request $request,
        Variant $variant
    ): array {
        $data = $request->getSession()->get('variantBuilderData');

        if (null === $data) {
            $data = $this->builderData($variant);
        }

        return $data;
    }

    protected function builderData(
        Variant $variant,
        ?FormTypeInterface $builderForm = null
    ): array {
        return [
            'variant_id' => $variant->getRawId(),
            'project_id' => $variant->getProject()->getRawId(),
            'design' => [],
            'parts' => [
                'header' => [
                    'isActive' => true,
                    'position' => 0,
                    'type' => 'header',
                    'template' => 'testLaunch',
                    'data' => [
                        'brand' => [
                            'logo' => 'brand-ai-cv.svg',
                            'text' => 'AI/CV',
                        ],
                        'navigation' => [
                            'Home' => '#home',
                            'Features' => '#features',
                            'Testimonials' => '#testimonials',
                            'Pricing' => '#pricing',
                            'Contact' => '#contact',
                        ],
                        'callToActionButton' => [
                            'text' => 'Get Started',
                            'link' => '#pricing',
                        ],
                    ],
                ],
                'hero' => [
                    'isActive' => true,
                    'position' => 1,
                    'type' => 'hero',
                    'template' => 'testLaunch',
                    'data' => [
                        'head' => 'Land Your Dream Job with an AI-Powered Resume',
                        'description' => 'Let our AI create the perfect resume to showcase your skills and get you hired faster.',
                        'callToActionButton' => [
                            'text' => 'Create Your Resume Now',
                            'link' => '#pricing',
                        ],
                        'background' => [
                            'image' => '',
                            'color' => '',
                            'gradient' => '',
                        ],
                        'thumb' => 'undraw_online_resume_re_ru7s.svg'
                    ],
                ],
                'features' => [
                    'isActive' => true,
                    'position' => 2,
                    'type' => 'features',
                    'template' => 'testLaunch',
                    'data' => [
                        'head' => 'We\'re here to help you feel better.',
                        'items' => [
                            [
                              'thumb' => 'undraw_organize_resume_re_k45b.svg',
                              'head' => 'AI-Powered Optimization',
                              'description' => 'Our AI analyzes your experience and suggests improvements to make your resume stand out.',
                            ],
                            [
                              'thumb' => 'undraw_personal_information_re_vw8a.svg',
                              'head' => 'Tailored to Job Descriptions',
                              'description' => 'Get personalized resume suggestions based on the job you’re applying for.',
                            ],
                            [
                              'thumb' => 'undraw_speed_test_re_pe1f.svg',
                              'head' => 'Quick & Easy',
                              'description' => 'Create a professional resume in minutes with our user-friendly interface.',
                            ],
                        ],
                    ],
                ],
                'howitworks' => [
                    'isActive' => true,
                    'position' => 3,
                    'type' => 'howitworks',
                    'template' => 'testLaunch',
                    'data' => [
                        'head' => 'How It Works',
                        'items' => [
                            [
                                'head' => '1. Enter Your Details',
                                'description' => 'Provide us with your work history and skills.',
                                'thumb' => 'undraw_details_8k13.svg',

                            ],
                            [
                                'head' => '2. Let the AI Work',
                                'description' => 'Our AI will analyze and enhance your resume content.',
                                'thumb' => 'undraw_file_bundle_re_6q1e.svg',
                            ],
                            [
                                'head' => '3. Download & Apply',
                                'description' => 'Download your polished resume and start applying.',
                                'thumb' => 'undraw_export_files_re_99ar.svg',
                            ],
                        ],
                    ],
                ],
                'testimonial' => [
                    'isActive' => true,
                    'position' => 4,
                    'type' => 'testimonial',
                    'template' => 'testLaunchCarousel',
                    'data' => [
                        'head' => 'Reviews',
                        'items' => [
                            [
                                'head' => 'Jane Doe',
                                'description' => '"I got my dream job thanks to this resume builder! The AI suggestions were spot on."',
                                'thumb' => 'undraw_pic_profile_re_7g2h.svg',

                            ],
                            [
                                'head' => 'John Smith',
                                'description' => '"This service is amazing! I created a professional resume in just a few minutes."',
                                'thumb' => 'undraw_profile_pic_re_iwgo.svg',
                            ],
                            [
                                'head' => 'Emily Johnson',
                                'description' => '"The AI did an incredible job tailoring my resume to the job I wanted. Highly recommend!"',
                                'thumb' => 'undraw_female_avatar_efig.svg',
                            ],
                        ],
                    ],
                ],
                'pricing' => [
                    'isActive' => true,
                    'position' => 5,
                    'type' => 'pricing',
                    'template' => 'testLaunch',
                    'data' => [
                        'head' => 'Choose Your Plan',
                        'items' => [
                            [
                                'head' => 'Basic',
                                'description' => [
                                    '1 Resume Template',
                                    '2 GB Storage',
                                    'Basic AI Optimization',
                                    'Single User',
                                    'Sales Dashboard',
                                    'Minimal Features',
                                    '1000 Logs',
                                    '',
                                ],
                                'callToActionButton' => [
                                    'text' => 'Try for free now',
                                    'link' => '#pricing',
                                ],
                                'price' => '9.99',
                                'currencySign' => '$'
                            ],
                            [
                                'head' => 'Pro',
                                'description' => [
                                    '5 Resume Templates',
                                    'Advanced AI Optimization',
                                    '10 GB Hosting',
                                    '5 Users',
                                    'Sales Dashboard',
                                    'Premium Features',
                                    '50,000 Logs',
                                ],
                                'callToActionButton' => [
                                    'text' => 'Start Pro',
                                    'link' => '#pricing',
                                ],
                                'price' => '19.99',
                                'currencySign' => '$'
                            ],
                            [
                                'head' => 'Premium',
                                'description' => [
                                    'Unlimited Templates',
                                    'Premium AI Optimization',
                                    '50 GB Hosting',
                                    'Unlimited Users',
                                    'Sales and Marketing Dashboard',
                                    'Premium Features',
                                    'Unlimited Logs',
                                ],
                                'callToActionButton' => [
                                    'text' => 'Surpass All',
                                    'link' => '#pricing',
                                ],
                                'price' => '29.99',
                                'currencySign' => '$'
                            ],

                        ],
                    ],
                ],
                'newsletter' => [
                    'isActive' => true,
                    'position' => 6,
                    'type' => 'newsletter',
                    'template' => 'testLaunch',
                    'data' => [
                        'head' => 'Stay Updated Always',
                        'description' => 'Subscribe to our newsletter for tips on resume writing and job hunting.',
                        'callToActionButton' => [
                            'text' => 'Subscribe',
                        ],
                        'inputPlaceholder' => 'Enter your email'
                    ],
                ],
                'footer' => [
                    'isActive' => true,
                    'position' => 7,
                    'type' => 'footer',
                    'template' => 'testLaunch',
                    'data' => [
                        'description' => 'AI Resume Builder. All Rights Reserved.',
                        'items' => [
                            [
                                'alt' => 'linkedin',
                                'link' => '#',
                                'thumb' => '5279114_linkedin_network_social network_linkedin logo_icon.svg',
                            ],
                            [
                                'alt' => 'facebook',
                                'link' => '#',
                                'thumb' => '5279115_chat bubble_facebook_messenger_messenger logo_icon.png',
                            ],
                            [
                                'alt' => 'twitter',
                                'link' => '#',
                                'thumb' => '5279120_play_video_youtube_youtuble logo_icon.png',
                            ],
                        ],
                        'privacyPolicy' => 'some privacy policy text',
                        'termsOfService' => 'some terms of service text',
                    ],
                ],
            ],

        ];
    }
}
