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
    public function __construct(
        protected readonly HttpClientInterface $picsumClient,
        protected readonly Filesystem $filesystem,
        protected readonly string $projectDir,
        protected readonly string $projectEnvironment
    ) {}

    public function findOneRandom(
        array $tags = [],
        array $size = [512,512]
    ): ?StockImageDto
    {
        dump(__METHOD__);
        $query = [
            'random' => time(),
        ];

        if (in_array('blur', $tags)) {
            $query['blur'] = 5;
        }

        if (in_array('grayscale', $tags)) {
            $query['grayscale'] = 1;
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
