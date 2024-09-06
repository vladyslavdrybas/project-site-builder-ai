<?php
declare(strict_types=1);

namespace App\Service\AiMl\Client;

use App\DataTransferObject\Variant\MediaDto;
use App\Entity\MediaAiPrompt;
use App\Repository\MediaAiPromptRepository;
use DateTime;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiMlClient
{
    public const IMAGE_DEFAULT_WITH = 256;
    public const IMAGE_DEFAULT_HEIGHT = 256;

    public function __construct(
        protected readonly Filesystem $filesystem,
        protected readonly HttpClientInterface $aimlApiClient,
        protected readonly ParameterBagInterface $parameterBag,
        protected readonly MediaAiPromptRepository $mediaAiPromptRepository,
        protected readonly Security $security,
        protected readonly string $projectDir,
        protected readonly string $projectEnvironment
    ) {}


    public function findOneRandom(
        string $prompt,
        array $tags = [],
        array $size = [self::IMAGE_DEFAULT_WITH, self::IMAGE_DEFAULT_HEIGHT]
    ): ?MediaDto
    {

        if (count($tags) > 0) {
            $prompt = $prompt . sprintf(
                    'To generate image use next keywords: %s',
                    implode(',', $tags)
                )
            ;
        }

        $imageSize = 'landscape_4_3';
        foreach ($tags as $tag) {
            if ('landscape_4_3' === $tag) {
                $imageSize = $tag;
                break;
            }

            if ('square' === $tag) {
                $imageSize = $tag;
                break;
            }
        }

        $apiKey = $this->parameterBag->get('aiml_api_key');
        $modelName = 'flux/schnell';
        $data = [
            'model' => $modelName,
            'prompt' => $prompt,
            'image_size' => $imageSize,
            'num_images' => 1,
        ];

        $result = null;
        try {
            $requestAt = new DateTime('now');
            $start = microtime(true);

            $user = $this->security->getUser();

            $response = $this->aimlApiClient->request(
                'POST',
                '/images/generations',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $data,
                ]
            );

            $content = $response->toArray();

            $end = microtime(true);
            $promptRequestExecutionTime = (int) ($end - $start) * 1000;

            dump($content);

            $mediaPrompt = new MediaAiPrompt();
            $mediaPrompt->setOwner($user);
            $mediaPrompt->setApiName('aiml');
            $mediaPrompt->setModelName($modelName);
            $mediaPrompt->setTags($tags);
            $mediaPrompt->setPromptMeta($data);
            $mediaPrompt->setPromptAnswer($content);
            $mediaPrompt->setIsDone(true);
            $mediaPrompt->setRequestAt($requestAt);
            $mediaPrompt->setExecutionMilliseconds($promptRequestExecutionTime);
            $mediaPrompt->setPromptHash(
                hash('sha256', $prompt)
            );

            $images = $content['images'] ?? [];
            $image = $images[0] ?? null;

            if (null !== $image) {
                $mediaPrompt->setWidth($image['width'] ?? null);
                $mediaPrompt->setHeight($image['height'] ?? null);
                $mediaPrompt->setMimeType($image['content_type'] ?? null);
                $mediaPrompt->setUrl($image['url'] ?? null);

                $extension = pathinfo(
                    parse_url($mediaPrompt->getUrl(), PHP_URL_PATH),
                    PATHINFO_EXTENSION
                );

                if (null !== $mediaPrompt->getUrl()) {
                    $response = $this->aimlApiClient->request(
                        'GET',
                        $mediaPrompt->getUrl()
                    );

                    if (Response::HTTP_OK === $response->getStatusCode()) {
                        $this->mediaAiPromptRepository->add($mediaPrompt);
                        $this->mediaAiPromptRepository->save();

                        $fileContent = $response->getContent();

                        $result = new MediaDto();
                        $result->mimeType = $mediaPrompt->getMimeType();
                        $result->extension = $extension;
                        $result->content = base64_encode($fileContent);
                        $result->tags = $tags;
                        $result->mediaAiPromptId = $mediaPrompt->getRawId();

                        if (in_array($this->projectEnvironment, ['local', 'dev'])) {
                            $this->filesystem->dumpFile(
                                sprintf(
                                    '%s/var/media-ai/%s/%s/%s-%s-%s.' . $extension,
                                    $this->projectDir,
                                    $mediaPrompt->getApiName(),
                                    $mediaPrompt->getModelName(),
                                    date('d-m-Y'),
                                    date('H-i-s'),
                                    hash('md5', $fileContent)
                                ),
                                $fileContent
                            );
                        }
                    }
                } else {
                    dump('Cannot find image url.');
                }
            } else {
                dump('Cannot find image.');
            }
        } catch (\Exception $e) {
            dump($e);
        }

        return $result;
    }
}
