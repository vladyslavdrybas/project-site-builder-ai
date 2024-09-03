<?php
declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\Variant\MediaDto;
use App\Entity\Media;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly EntityManagerInterface $em,
        protected readonly string $contentDir,
        protected readonly Filesystem $filesystem
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

        $id = $this->generateMediaId($owner->getRawId(), $content, $version);

        $dto = new MediaDto(
            $id,
            $mimeType,
            $extension,
            $size,
            $version,
            $tags,
            $owner->getRawId(),
            base64_encode($content)
        );

        return $dto;
    }

    protected function generateMediaId(string $ownerId, string $content, int $version): string
    {
        return hash('sha256', $ownerId .$content . $version);
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
