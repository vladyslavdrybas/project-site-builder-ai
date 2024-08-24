<?php
declare(strict_types=1);

namespace App\Builder;

use App\Entity\Media;
use App\Entity\Tag;
use App\Entity\Variant;
use App\Repository\MediaRepository;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly TagRepository $tagRepository,
        protected readonly MediaRepository $mediaRepository
    ) {}

    public function mediaForVariant(
        UploadedFile $file,
        Variant $variant,
        array $tags = []
    ): Media {
        $tags[] = 'variant';
        $tags = array_unique($tags);

        $media = new Media();

        $content = file_get_contents($file->getRealPath());
        $size = $file->getSize();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();

        $media->setContent($content);
        $media->setSize($size);
        $media->setExtension($extension);
        $media->setMimeType($mimeType);
        $media->setOwner($variant->getProject()->getOwner());
        $media->generateId();

        $existedMedia = $this->mediaRepository->find($media->getId());

        if ($existedMedia instanceof Media) {
            $media = $existedMedia;
        }

        foreach ($tags as $tag) {
            if ($media->hasTagByKey($tag)) {
                continue;
            }

            $existedTag = $this->tagRepository->find($tag);
            if (!$existedTag instanceof Tag) {
                $tag = new Tag($tag);
                $this->tagRepository->add($tag);
                $this->tagRepository->save();
            } else {
                $tag = $existedTag;
            }

            $media->addTag($tag);
        }

        return $media;
    }
}
