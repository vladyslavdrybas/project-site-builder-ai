<?php
declare(strict_types=1);

namespace App\Service\ImageStocks\Adapter;

use App\Service\ImageStocks\DataTransferObject\StockImageDto;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
        protected readonly string $projectDir,
        protected readonly string $projectEnvironment
    ) {}

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
                ]
            );

            if (Response::HTTP_OK === $response->getStatusCode()) {
                $content = $response->getContent();

                if (in_array($this->projectEnvironment, ['local', 'dev'])) {
                    $this->filesystem->dumpFile(
                        sprintf(
                            '%s/var/stockimages/%s-%s-%s.jpg',
                            $this->projectDir,
                            date('d-m-Y'),
                            date('H-i-s'),
                            hash('sha256', $content)
                        ),
                        $content
                    );
                }

                $result = new StockImageDto();
                $result->mimeType = 'image/jpeg';
                $result->extension = 'jpg';
                $result->content = base64_encode($content);
                $result->tags = $tags;
            }

        } catch (Exception $e) {
            dump($e);
        }

        return $result;
    }
}
