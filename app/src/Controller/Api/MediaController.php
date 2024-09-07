<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Constants\RouteRequirements;
use App\Controller\AbstractController;
use App\Entity\Media;
use App\Entity\Tag;
use App\Exceptions\AccessDenied;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            ];
        }

        dump($data);

        return $this->json([
            'type' => 'media',
            'data' => $data,
        ]);
    }
}
