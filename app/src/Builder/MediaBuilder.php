<?php
declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\Variant\MediaDto;
use App\Entity\Media;
use App\Entity\MediaAiPrompt;
use App\Entity\Tag;
use App\Entity\User;
use App\Service\ImageStocks\DataTransferObject\StockImageDto;
use App\Utility\MediaIdGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly EntityManagerInterface $em,
        protected readonly string $contentDir,
        protected readonly Filesystem $filesystem,
        protected readonly MediaIdGenerator $mediaIdGenerator,
    ) {}

    public function fromArray(
        array $data
    ): Media {
        if (
            empty($data['ownerId'])
            || empty($data['content'])
            || empty($data['extension'])
        ) {
            throw new \Exception('Media must have content and owner to create.');
        }
        $mediaRepository = $this->em->getRepository(Media::class);
        $tagRepository = $this->em->getRepository(Tag::class);

        $tags = array_unique($data['tags']);

        $media = new Media();

        $media->setSize($data['size']);
        $media->setExtension($data['extension']);
        $media->setMimeType($data['mimeType']);;
        $media->setId($data['id']);

        if (!empty($data['mediaAiPromptId'])) {
            $mediaAiPromptRepository = $this->em->getRepository(MediaAiPrompt::class);
            $mediaAiPrompt = $mediaAiPromptRepository->find($data['mediaAiPromptId']);
            $media->setMediaAiPrompt($mediaAiPrompt);
        }

        $existedMedia = $mediaRepository->find($media->getId());

        $content = base64_decode($data['content']);
        $filePath = $this->generateFilePath(
            $data['ownerId'],
            $data['id'],
            $data['extension']
        );
        $fileStored = false;

        if ($existedMedia instanceof Media) {
            $media = $existedMedia;
            if ($this->filesystem->exists($filePath)) {
                $fileStored = true;
            }
        }

        if (false === $fileStored) {
            if ($media->getServerAlias() === 'local') {
                // TODO decentralized filesystem
                $this->filesystem->dumpFile($filePath, $content);
            }
        }

        $media->setPath($filePath);

        foreach ($tags as $tag) {
            if ($media->hasTagByKey($tag)) {
                continue;
            }

            $existedTag = $tagRepository->find($tag);
            if (!$existedTag instanceof Tag) {
                $tag = new Tag($tag);
                $tagRepository->add($tag);
                $tagRepository->save();
            } else {
                $tag = $existedTag;
            }

            $media->addTag($tag);
        }

        return $media;
    }

    public function mediaDtoFromUploadedFile(
        User $owner,
        ?UploadedFile $file = null,
        array $tags = [],
    ): ?MediaDto {
        if (null === $file) {
            return null;
        }

        $content = file_get_contents($file->getRealPath());
        $size = $file->getSize();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $version = 0;


        $dto = new MediaDto(
            null,
            $mimeType,
            $extension,
            $size,
            $version,
            $tags,
            $owner->getRawId(),
            base64_encode($content)
        );

        $dto->id = $this->generateMediaId($dto);

        return $dto;
    }

    public function mediaDtoFromStockImage(StockImageDto $stockImage): MediaDto
    {
        return new MediaDto(
            null,
            $stockImage->mimeType,
            $stockImage->extension,
            $stockImage->size,
            $stockImage->version,
            $stockImage->tags,
            null,
            $stockImage->content,
        );
    }

    public function mediaDtoFromMedia(?string $id = null): ?MediaDto
    {
        if (null === $id) return null;
        $mediaRepository = $this->em->getRepository(Media::class);
        $media = $mediaRepository->find($id);

        if (null === $media) return null;

        $dto = new MediaDto();
        $dto->id = $media->getId();
        $dto->mimeType = $media->getMimeType();
        $dto->extension = $media->getExtension();
        $dto->size = $media->getSize();
        $dto->version = $media->getVersion();
        $dto->tags = $media->getTags()->map(fn(Tag $tag) => $tag->getRawId())->toArray();

        return $dto;
    }

    public function generateMediaId(MediaDto $media): string
    {
        return $this->mediaIdGenerator->generate($media->ownerId, $media->content, $media->version);
    }

    protected function generateFilePath(
        string $ownerId,
        string $fileId,
        string $fileExtension
    ): string {
        $dir = $this->contentDir;

        return sprintf(
            '%s/%s/%s.%s',
            $dir,
            str_replace('-', '/', $ownerId),
            $fileId,
            $fileExtension
        );
    }
}
