<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Builder\MediaBuilder;
use App\Constants\RouteRequirements;
use App\Controller\AbstractController;
use App\DataTransferObject\Variant\MediaDto;
use App\Entity\Media;
use App\Entity\Tag;
use App\Entity\Variant;
use App\Exceptions\AccessDenied;
use App\Exceptions\AlreadyExists;
use App\Repository\MediaRepository;
use App\Service\AiMl\AiMlFacade;
use App\Service\ImageAiCreator\ImageAiCreatorFacade;
use App\Service\ImageStocks\DataTransferObject\StockImageDto;
use App\Service\ImageStocks\ImageStocksFacade;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[\Symfony\Component\Routing\Attribute\Route(
    "/api/m",
    name: "api_media",
    requirements: [
        'media' => RouteRequirements::USER_SHA256->value,
        'offset' => '^[0-9]+$',
        'limit' => '^[0-9]+$',
    ],
    defaults: [
        'offset' => 0,
        'limit' => 36,
    ]
)]
class MediaController extends AbstractController
{
    #[Route(
        "/l/{limit}/{offset}",
        name: "_list_my",
        methods: ["GET"]
    )]
    public function userLibraryAsJson(
        MediaRepository $mediaRepository,
        int $offset = 0,
        int $limit = 36
    ): JsonResponse {
        if ($this->getUser() === null) {
            throw new AccessDenied();
        }

        $medias = $mediaRepository->findBy(
            [
                'owner' => $this->getUser()->getRawId(),
            ],
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );

        $data = [];
        foreach ($medias as $key => $media) {
            $data[$media->getRawId()] = [
                'url' => $this->generateUrl('app_media_show', ['media' => $media->getRawId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'tags' => $media->getTags()->map(fn(Tag $tag) => $tag->__toString()),
                'order' => $key + $offset,
                'content' => null,
                'mimeType' => $media->getMimeType(),
                'extension' => $media->getExtension(),
            ];
        }

        dump($data);

        return $this->json([
            'type' => 'media',
            'data' => $data,
        ]);
    }

    // TODO save stock media on local storage. save data transfer
    #[Route(
        "/l/share-stock",
        name: "_list_share_stock",
        methods: ["GET"]
    )]
    public function shareStockAsJson(
        MediaRepository $mediaRepository,
        ImageStocksFacade $imageStocksFacade,
        MediaBuilder $mediaBuilder
    ): JsonResponse {
        $tags = [];
        $img = $imageStocksFacade->findOneRandom($tags);
        $stockImages = [];

        if ($img !== null) {
            $stockImages[] = $img;
        }
        $owner = $this->getUser();

        /** @var array<MediaDto> $mediasDto */
        $mediasDto = array_map(function(StockImageDto $dto) use ($mediaBuilder, $owner) {
            dump($dto);
            $result = $mediaBuilder->mediaDtoByStockImage($dto);
            $result->ownerId = $owner->getRawId();
            $result->tags = $dto->tags;
            $result->id = $mediaBuilder->generateMediaId($result);

            return $result;
        }, $stockImages);

        dump($mediasDto);

        $data = [];
        foreach ($mediasDto as $key => $dto) {
            dump([
               'mediasDtoKEY',
                $key
            ]);
            $order = strval(microtime(true) / 100000);
            dump($order);
            $order = explode('.', $order);
            dump($order);
            $order = (int) (floatval($order[1]));
            $order += $key;
            dump($order);

            // save transfer overload
            if (null !== $dto->url) $dto->content = null;

            $data[$dto->id] = [
                'url' => $dto->url,
                'tags' => $dto->tags,
                'order' => $order,
                'content' => $dto->content,
                'mimeType' => $dto->mimeType,
                'extension' => $dto->extension,
            ];
        }

        dump($data);

        return $this->json([
            'type' => 'media_share_stock',
            'data' => $data,
        ]);
    }

    // TODO save stock media on local storage. save data transfer
    #[Route(
        "/v/{variant}/generate/ai",
        name: "_variant_generate_ai",
        methods: ["GET"]
    )]
    public function aiAsJson(
        Request $request,
        ImageAiCreatorFacade $imageAiCreatorFacade,
        MediaBuilder $mediaBuilder,
        Variant $variant,
        MediaRepository $mediaRepository
    ): JsonResponse {
        // TODO GENERATE AI IMAGE HERE and immediately give it back.
        $tags = $request->query->get('tags') ?? [];

        $variantPromptMeta = $variant->getPromptMeta();

        $img = $imageAiCreatorFacade->findOneRandom(
            $variantPromptMeta,
            $tags
        );

        dump(['AiMl image', $img]);

        $mediasDto = [];
        if ($img !== null) {
            $mediasDto[] = $img;
        }

        $data = [];
        foreach ($mediasDto as $key => $dto) {
            $dto->tags += ['AI'];
            $mediaArray = [
                'mediaTag' => 'ai',
                'id' => $dto->id,
                'ownerId' => $dto->ownerId,
                'extension' => $dto->extension,
                'mimeType' => $dto->mimeType,
                'size' => $dto->size,
                'mediaAiPromptId' => $dto->mediaAiPromptId,
                'tags' => $dto->tags,
                'content' => $dto->content,
                'url' => $dto->url,
            ];
            $media = $mediaBuilder->fromArray($mediaArray);
            $media->setOwner($this->getUser());
            try {
                $mediaRepository->add($media);
                $mediaRepository->save();
            } catch (Exception $e) {
                dump($e);
                continue;
            }

            dump([
                'mediasDtoKEY',
                $key
            ]);
            $order =microtime(true) / 100000;
            $order += $media->getCreatedAt()->getTimestamp();
            dump($order);
            $order = explode('.',  strval($order));
            dump($order);
            $order = (int) (floatval($order[1]));
            $order += $key;
            dump($order);

            // save transfer overload
            if (null !== $dto->url) $dto->content = null;

            $data[$dto->id] = [
                'url' => $this->generateUrl(
                    'app_media_show',
                    ['media' => $media->getRawId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'tags' => $dto->tags,
                'order' => $order,
                'content' => $dto->content,
                'mimeType' => $dto->mimeType,
                'extension' => $dto->extension,
            ];
        }

        dump($data);

        return $this->json([
            'type' => 'media_ai',
            'data' => $data,
        ]);
    }
}
