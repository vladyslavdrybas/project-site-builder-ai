<?php
declare(strict_types=1);

namespace App\Controller;

use App\Constants\RouteRequirements;
use App\Entity\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

#[\Symfony\Component\Routing\Attribute\Route(
    "/m",
    name: "app_media",
    requirements: [
        'media' => RouteRequirements::USER_SHA256->value
    ]
)]
class MediaController extends AbstractController
{
    #[Route("/{media}", name: "_show", methods: ["GET"])]
    public function show(
        Media $media
    ): BinaryFileResponse {
        return new BinaryFileResponse($media->getContent());
    }
}
