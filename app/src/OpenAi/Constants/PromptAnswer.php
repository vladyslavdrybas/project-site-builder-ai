<?php
declare(strict_types=1);

namespace App\OpenAi\Constants;

enum PromptAnswer: string
{
    case VARIANT_HEADER = '{"data":{"brand":{"text":""},"callToActionButton":{"text":""},"navigation":{"home":"","hero":"","features":"","howitworks":"","reviews":"","pricing":"","newsletter":"","contact":""}}}';
    case VARIANT_HERO = '{"data":{"headline":"","subheadline":"","callToActionButton":{"text":""}}}';
    case VARIANT_ABOUT_US = '{"data":{"headline":"","subheadline":"","description":""}}';
    case VARIANT_FEATURES = '{"data":{"headline":"","subheadline":"","items":{"feature1":{"headline":"","description":""},"feature2":{"headline":"","description":""},"feature3":{"headline":"","description":""}}}}';
    case VARIANT_WORK_EXAMPLE = '{"data":{"headline":"","subheadline":"","items":{"work1":{"headline":"","description":""},"work2":{"headline":"","description":""},"work3":{"headline":"","description":""}}}}';
    case VARIANT_REASONS_TO_USE = '{"data":{"headline":"","subheadline":"","items":{"point1":{"headline":"","description":""},"point2":{"headline":"","description":""},"point3":{"headline":"","description":""}}, "callToActionButton":{"text":""}}}';
    case VARIANT_WHO_USE_IT = '{"data":{"headline":"","subheadline":"", "callToActionButton":{"text":""}}}';
    case VARIANT_PRICING_SUBSCRIPTION = '{"data":{"headline":"","subheadline":"","items":{"plan1":{"headline":"","features":[],"callToActionButton":{"text":""},"price":"","currencySign":""},"plan2":{"headline":"","features":[],"callToActionButton":{"text":""},"price":"","currencySign":""},"plan3":{"headline":"","features":[],"callToActionButton":{"text":""},"price":"","currencySign":""}}}}';
    case VARIANT_HOW_IT_WORKS = '{"data":{"headline":"","subheadline":"","items":{"step1":{"headline":"","description":""},"step2":{"headline":"","description":""},"step3":{"headline":"","description":""}}}}';
    case VARIANT_TESTIMONIALS = '{"data":{"headline":"","subheadline":"","maxItems":7,"items":[{"name":"","review":""}]}}';
    case VARIANT_PARTNERS = '{"data":{"headline":"","subheadline":"","maxItems":10,"items":[{"name":"","link":"", "description":"","reasonToUse":"","howToUse":"","features":""}]}}';
    case VARIANT_NEWSLETTER = '{"data":{"headline":"","subheadline":"","inputFieldPlaceholder":"Enter your contact email","callToActionButton":{"text":""}}}';
    case VARIANT_PRODUCT_PRICE = '{"data":{"headline":"","subheadline":"","callToActionButton":{"text":""}, "price":"","currencySign":""}}';

}
