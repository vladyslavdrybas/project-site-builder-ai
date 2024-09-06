<?php
declare(strict_types=1);

namespace App\Service\ImageAiCreator;

use App\DataTransferObject\Variant\AI\Prompt\VariantPromptMetaDto;
use App\DataTransferObject\Variant\MediaDto;
use App\Service\AiMl\AiMlFacade;
use App\Service\OpenAi\Business\OpenAiPromptManager;

class ImageAiCreatorFacade
{
    public function __construct(
        protected readonly OpenAiPromptManager $openAiPromptManager,
        protected readonly AiMlFacade $aiMlFacade
    ) {}

    //TODO check limits, add other models, use multiple models based on limitations and usage
    public function findOneRandom(
        VariantPromptMetaDto $variantPromptMeta,
        array $tags = []
    ): ?MediaDto {

        // TODO for avatar use at first api that will get realistic face from specific AI. fe: random fake face
        $concreteImagePosition = match(true) {
            in_array('logo', $tags) => '/logo',
            in_array('hero', $tags) => '/hero',
            in_array('avatar', $tags) => '/avatar',
            default => '',
        };

        $promptToCreatePrompt = $this->openAiPromptManager->createPromptToGenerateImage(
            $variantPromptMeta,
            'aiml/flux/schnell' . $concreteImagePosition,
            $tags
        );
        dump($promptToCreatePrompt);

        $promptAnswer = $this->openAiPromptManager->requestPrompt($promptToCreatePrompt);
        dump($promptAnswer);

        $prompt = $this->openAiPromptManager->convertPromptPlainTextAnswerToText($promptAnswer);
        dump($prompt);

        return $this->aiMlFacade->findOneRandom($prompt, $tags);
    }
}
