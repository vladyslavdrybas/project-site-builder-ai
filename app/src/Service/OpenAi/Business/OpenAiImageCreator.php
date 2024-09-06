<?php
declare(strict_types=1);

namespace App\Service\OpenAi\Business;

use App\DataTransferObject\Variant\MediaDto;
use App\Service\OpenAi\Client\OpenAiClient;
use Exception;
use Symfony\Component\Filesystem\Filesystem;

class OpenAiImageCreator
{
    public const IMAGE_DEFAULT_WITH = 1024;
    public const IMAGE_DEFAULT_HEIGHT = 1024;

    public function __construct(
        protected readonly Filesystem $filesystem,
        protected readonly OpenAiClient $client,
        protected readonly string $projectDir,
        protected readonly string $projectEnvironment

    ) {}

    public function findOneRandom(
        array $tags = [],
        array $size = [self::IMAGE_DEFAULT_WITH, self::IMAGE_DEFAULT_HEIGHT]
    ): ?MediaDto
    {
        $result = null;
        try {
            $prompt = 'Create an image of a futuristic city skyline at sunset, with towering skyscrapers made of glass and metal reflecting the golden light. Flying cars zip between the buildings, and glowing neon signs in various languages illuminate the streets below. In the distance, a massive digital billboard displays an advertisement for space travel. The city is surrounded by lush, green hills, with a river running through the center. The style should be vibrant, detailed, and a mix of cyberpunk and utopian aesthetics.';

            if (count($tags) > 0) {
                $prompt = $prompt . sprintf(
                        'To generate image use next keywords: %s',
                        implode(',', $tags)
                    )
                ;
            }

            $response = $this->client->imageByPrompt($prompt);

            dump($response);

//            if (Response::HTTP_OK === $response->getStatusCode()) {
//                $content = $response->getContent();
//
//                if (in_array($this->projectEnvironment, ['local', 'dev'])) {
//                    $this->filesystem->dumpFile(
//                        sprintf(
//                            '%s/var/stockimages/%s-%s-%s.jpg',
//                            $this->projectDir,
//                            date('d-m-Y'),
//                            date('H-i-s'),
//                            hash('sha256', $content)
//                        ),
//                        $content
//                    );
//                }
//
//                $result = new MediaDto();
//                $result->mimeType = 'image/jpeg';
//                $result->extension = 'jpg';
//                $result->content = base64_encode($content);
//                $result->tags = $tags;
//            }

        } catch (Exception $e) {
            dump($e);
        }

        dump([
            __METHOD__,
            $result
        ]);

        return $result;
    }
}
