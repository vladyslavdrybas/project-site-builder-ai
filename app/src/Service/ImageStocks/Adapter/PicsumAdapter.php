<?php
declare(strict_types=1);

namespace App\Service\ImageStocks\Adapter;

use App\Service\ImageStocks\DataTransferObject\StockImageDto;
use App\Utility\GetExtensionForMimeType;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PicsumAdapter implements IAdapter
{
    public const IMAGE_DEFAULT_WITH = 512;
    public const IMAGE_DEFAULT_HEIGHT = 512;
    public const IMAGE_MIN_WITH = 128;
    public const IMAGE_MIN_HEIGHT = 128;
    public const IMAGE_MAX_WITH = 2048;
    public const IMAGE_MAX_HEIGHT = 2048;

    public function __construct(
        protected readonly HttpClientInterface $picsumClient,
        protected readonly Filesystem $filesystem,
        protected readonly GetExtensionForMimeType $getExtensionForMimeType,
        protected readonly string $projectDir,
        protected readonly string $projectEnvironment
    ) {}

    public function loadByUrl(string $url): ?StockImageDto
    {
        $result = null;
        try {
            $response = $this->picsumClient->request(
                'GET',
                $url
            );

            if (Response::HTTP_OK === $response->getStatusCode()) {
                $result = $this->stockImageDtoFromResponse($response);
            }
        } catch (Exception $e) {
            dump($e);
        }

        return $result;
    }

    public function findOneRandom(
        array $tags = [],
        array $size = [self::IMAGE_DEFAULT_WITH, self::IMAGE_DEFAULT_HEIGHT]
    ): ?StockImageDto
    {
        dump([
            __METHOD__,
            'tags' => $tags,
        ]);
        $query = [
            'random' => time(),
        ];

        foreach ($tags as $tag) {
            switch($tag) {
                case str_starts_with($tag, 'blur'):
                    $blur = preg_replace('/[^0-9]/', '', $tag);
                    $blur = (int)$blur;
                    if ($blur < 0 || $blur > 10) {
                        $blur = 5;
                    }

                    $query['blur'] = $blur;

                    break;
                case str_starts_with($tag, 'width'):
                    $width = preg_replace('/[^0-9]/', '', $tag);
                    $width = (int) $width;
                    if ($width < self::IMAGE_MIN_WITH) {
                        $width = self::IMAGE_MIN_WITH;
                    } elseif ($width > self::IMAGE_MAX_WITH) {
                        $width = self::IMAGE_MAX_WITH;
                    }

                    $size[0] = $width;

                    break;
                case str_starts_with($tag, 'height'):
                    $height = preg_replace('/[^0-9]/', '', $tag);
                    $height = (int) $height;
                    if ($height < self::IMAGE_MIN_HEIGHT) {
                        $height = self::IMAGE_MIN_HEIGHT;
                    } elseif ($height > self::IMAGE_MAX_HEIGHT) {
                        $height = self::IMAGE_MAX_HEIGHT;
                    }

                    $size[1] = $height;

                    break;
                case 'grayscale':
                    $query['grayscale'] = 1;

                    break;
                default:
                    // do nothing
            }
        }

        $result = null;
        try {
            $response = $this->picsumClient->request(
                'GET',
                sprintf('/%s/%s.jpg', $size[0], $size[1]),
                [
                    'query' => $query,
                    'max_redirects' => 1,
                ]
            );

            dump($response->getInfo());
            dump($response->getStatusCode());

            if (Response::HTTP_OK === $response->getStatusCode()) {
                $result = $this->stockImageDtoFromResponse($response);
                $result->tags = $tags;

                if (in_array($this->projectEnvironment, ['local', 'dev'])) {
                    $content = base64_decode($result->content);
                    $this->filesystem->dumpFile(
                        sprintf(
                            '%s/var/stockimages/%s-%s-%s.jpg',
                            $this->projectDir,
                            date('d-m-Y'),
                            date('H-i-s'),
                            hash('sha256', $result->content)
                        ),
                        $content
                    );
                }
            }
        } catch (Exception $e) {
            dump($e);
        }

        return $result;
    }

    protected function stockImageDtoFromResponse(ResponseInterface $response): StockImageDto
    {
        $content = $response->getContent();
        $info = $response->getInfo();

        [
            $mimeType,
            $extension
        ] = $this->getExtensionForMimeType->get($info['content_type']);

        if (null === $mimeType || null === $extension) {
            throw new Exception('Cannot get content type for ' . $info['url']);
        }

        $result = new StockImageDto();
        $result->mimeType = $mimeType;
        $result->extension = $extension;
        $result->url = $info['url'];
        $result->content = base64_encode($content);

        return $result;
    }
}
