<?php
declare(strict_types=1);

namespace App\Service\OpenAi\Constants;

enum PromptAnswer: string
{
    case VARIANT_PART = '{"headline":<string>,"subheadline":<string>, "items":<array>, ...}';
    case VARIANT_HEADER = '{"brand":{"text":""},"callToActionButton":{"text":""},"navigation":{"<sectionAnchorID>":"<linkName>", ...}}"';
    case VARIANT_HERO = '{"headline":"","subheadline":"","callToActionButton":{"text":""}}';
    case VARIANT_ABOUT_US = '{"headline":"","subheadline":"","description":""}';
    case VARIANT_FEATURES = '{"headline":"","subheadline":"","items":{"feature1":{"headline":"","description":""},"feature2":{"headline":"","description":""},"feature3":{"headline":"","description":""}}}';
    case VARIANT_WHO_USE_IT = '{"headline":"","subheadline":"","items":{"story1":{"case":"","solution":""},"story2":{"case":"","solution":""},"story3":{"case":"","solution":""}}}';
    case VARIANT_WORK_EXAMPLE = '{"headline":"","subheadline":"","items":{"work1":{"headline":"","description":""},"work2":{"headline":"","description":""},"work3":{"headline":"","description":""}}, "callToActionButton":{"text":""}}';
    case VARIANT_REASONS_TO_USE = '{"headline":"","subheadline":"","items":{"point1":{"headline":"","description":""},"point2":{"headline":"","description":""},"point3":{"headline":"","description":""}}, "callToActionButton":{"text":""}}';
    case VARIANT_PRICING_SUBSCRIPTION = '{"headline":"","subheadline":"","items":{"plan1":{"headline":"","features":[],"callToActionButton":{"text":""},"price":"","currencySign":"","period":"week|month|year"},"plan2":{"headline":"","features":[],"callToActionButton":{"text":""},"price":"","currencySign":"","period":"week|month|year"},"plan3":{"headline":"","features":[],"callToActionButton":{"text":""},"price":"","currencySign":"","period":"week|month|year"}}}';
    case VARIANT_HOW_IT_WORKS = '{"headline":"","subheadline":"","items":{"step1":{"headline":"","description":""},"step2":{"headline":"","description":""},"step3":{"headline":"","description":""}}}';
    case VARIANT_SIMPLE_DESCRIPTION_ITEMS = '{"headline":"","subheadline":"","items":[{"headline":"","description":""}<from 5 to 7>]}';
    case VARIANT_PARTNERS = '{"headline":"","subheadline":"","items":[{"name":"","link":"", "description":"","reasonToUse":"","howToUse":"","features":""}]}';
    case VARIANT_NEWSLETTER = '{"headline":"","subheadline":"","inputFieldPlaceholder":"Enter your contact email","callToActionButton":{"text":""}}';
    case VARIANT_PRODUCT_PRICE = '{"headline":"","subheadline":"","callToActionButton":{"text":""}, "price":"","currencySign":""}';
}
