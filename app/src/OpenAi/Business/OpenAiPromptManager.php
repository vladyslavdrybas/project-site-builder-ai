<?php
declare(strict_types=1);

namespace App\OpenAi\Business;

use App\DataTransferObject\Ai\PromptDto;
use App\DataTransferObject\Variant\VariantPromptMetaDto;
use App\OpenAi\Client\OpenAiClient;
use App\OpenAi\Constants\PromptAnswer;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

class OpenAiPromptManager
{
    public function __construct(
        protected readonly Filesystem $filesystem,
        protected readonly OpenAiClient $client,
        protected readonly string $projectDir,
        protected readonly string $projectEnvironment

    ) {}

    public function requestPrompt(
        PromptDto $prompt
    ): array {
        $answer = $this->client->prompt($prompt->text);

        return $answer;
    }

    public function convertVariantPromptMetaToPrompt(
        VariantPromptMetaDto $promptMetaDto,
        string               $promptTemplate,
        array $parts
    ): PromptDto {
        $promptTemplateDirPath = sprintf('%s/src/OpenAi/config/prompts/%s', $this->projectDir, $promptTemplate);

        if (!$this->filesystem->exists($promptTemplateDirPath)) {
            throw new FileNotFoundException($promptTemplate);
        }

        $indexFilePath = sprintf('%s/%s.txt', $promptTemplateDirPath, 'index');
        if (!$this->filesystem->exists($indexFilePath)) {
            throw new FileNotFoundException($promptTemplate . '/index');
        }
        $indexPromptText = file_get_contents($indexFilePath);

        $jsonStructures = [
            'partHeader' => PromptAnswer::VARIANT_HEADER->value,
            'partHeroWithCallToAction' => PromptAnswer::VARIANT_HERO->value,
            'partFeatures' => PromptAnswer::VARIANT_FEATURES->value,
            'partHowItWorks' => PromptAnswer::VARIANT_HOW_IT_WORKS->value,
            'partTestimonials' => PromptAnswer::VARIANT_TESTIMONIALS->value,
            'partSubscriptionPlans' => PromptAnswer::VARIANT_PRICING_SUBSCRIPTION->value,
            'partNewsletterSubscription' => PromptAnswer::VARIANT_NEWSLETTER->value,
            'partReasonsToUse' => PromptAnswer::VARIANT_REASONS_TO_USE->value,
            'partPartners' => PromptAnswer::VARIANT_PARTNERS->value,
            'partProductPrice' => PromptAnswer::VARIANT_PRODUCT_PRICE->value,
            'partWhoUseIt' => PromptAnswer::VARIANT_WHO_USE_IT->value,
            'partWorkExample' => PromptAnswer::VARIANT_WORK_EXAMPLE->value,
        ];

        $activeParts = [];
        foreach ($parts as $partName => $isActive) {
            $partName = 'part' . ucfirst($partName);
            $activeParts[$partName] = $isActive;
        }

        $mandatoryParts = [
            'role' => true,
            'inputData' => true,
            'instructions' => true,
            'headline' => true,
            'callToAction' => true,
            'answerRequest' => true,
        ];

        $activeParts = array_merge($activeParts, $mandatoryParts);

        foreach ($activeParts as $partName => $isActive) {
            if (!$isActive) {
                $activeParts[$partName] = '';
                continue;
            }

            $promptTemplatePath = sprintf('%s/%s.txt', $promptTemplateDirPath, $partName);

            if (!$this->filesystem->exists($promptTemplatePath)) {
                throw new FileNotFoundException($promptTemplate . '/' . $partName);
            }

            $partPromptText = trim(file_get_contents($promptTemplatePath));

            if (!empty($jsonStructures[$partName])) {
                $partPromptText = str_replace('{{jsonStructure}}', $jsonStructures[$partName], $partPromptText);
            } else {
                $partPromptText = str_replace('{{jsonStructure}}', '{"data":{"headline":<string>,"subheadline":<string>, ...}}', $partPromptText);
            }

            if (empty($partPromptText)) {
                $partPromptText = sprintf('<section name="%s"></section>', lcfirst(str_replace('part', '', $partName)));
            }

            $activeParts[$partName] = $partPromptText;
        }

        foreach ($activeParts as $partName => $partPromptText) {
            $indexPromptText = str_replace('{{' . $partName . '}}', $partPromptText, $indexPromptText);
        }

        $indexPromptText = str_replace(
            [
                '{{productShortDescription}}',
                '{{productDescription}}',
                '{{targetAudience}}',
                '{{targetAudienceGender}}',
                '{{tone}}',
                '{{style}}',
                '{{proposal}}',
                '{{productValue}}',
                '{{competitors}}',
            ],
            [
                $promptMetaDto->productShortDescription,
                $promptMetaDto->productDescription,
                $promptMetaDto->targetAudience,
                'men and women',
                implode(' and ', array_keys($promptMetaDto->tone ?? ['creative' => true])),
                implode(' and ', array_keys($promptMetaDto->style ?? ['humor' => true])),
                $promptMetaDto->proposal,
                $promptMetaDto->value,
                implode(' and ', explode("\r\n", $promptMetaDto->competitors ?? ['none']))
            ],
            $indexPromptText
        );

        $indexPromptText = preg_replace('/\n{2,}/m', "\n", $indexPromptText);

        $activeParts = array_keys(array_filter($parts, function ($isActive) {
            return $isActive === true;
        }));

        $dto = new PromptDto(
            $indexPromptText,
            $promptTemplate,
            $activeParts
        );

        if (in_array($this->projectEnvironment, ['local', 'dev'])) {
            $this->filesystem->dumpFile(
                sprintf( '%s/var/prompts/%s-%s-%s.txt', $this->projectDir, date('d-m-Y'), hash('md5', $dto->text) . '-text', date('H-i-s')),
                $dto->text
            );
        }

        return $dto;
    }
}
