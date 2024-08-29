<?php
declare(strict_types=1);

namespace App\OpenAi\Business;

use App\DataTransferObject\Ai\PromptDto;
use App\DataTransferObject\Ai\PromptResponseDto;
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
        protected readonly string $projectDir
    ) {}

    public function requestPrompt(
        PromptDto $prompt
    ): array {
        $answer = $this->client->prompt($prompt->text);

        return $answer;
    }

    public function convertVariantPromptMetaToPrompt(
        VariantPromptMetaDto $promptMetaDto,
        string               $promptTemplate
    ): PromptDto {
        $promptTemplatePath = sprintf('%s/src/OpenAi/config/prompts/%s.txt', $this->projectDir, $promptTemplate);

        if (!$this->filesystem->exists($promptTemplatePath)) {
            throw new FileNotFoundException($promptTemplate);
        }

        $promptText = file_get_contents($promptTemplatePath);

        $promptText = str_replace(
            [
                '{{answerJson}}',
                '{{productShortDescription}}',
                '{{productDescription}}',
                '{{keywords}}',
                '{{targetAudience}}',
                '{{targetAudienceGender}}',
                '{{tone}}',
                '{{style}}',
                '{{proposal}}',
                '{{productValue}}',
                '{{competitors}}',
            ],
            [
                PromptAnswer::VARIANT_FULL->value,
                $promptMetaDto->productShortDescription,
                $promptMetaDto->productDescription,
                'take from our competitors or provide yours',
                $promptMetaDto->targetAudience,
                'men and women',
                implode(' and ', array_keys($promptMetaDto->tone ?? ['creative' => true])),
                implode(' and ', array_keys($promptMetaDto->style ?? ['humor' => true])),
                $promptMetaDto->proposal,
                $promptMetaDto->value,
                implode(' and ', explode("\r\n", $promptMetaDto->competitors ?? ['none']))
            ],
            $promptText
        );

        $dto = new PromptDto(
            $promptText,
            $promptTemplate
        );

        return $dto;
    }
}
