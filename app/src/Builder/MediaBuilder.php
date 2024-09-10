<?php
declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\Variant\MediaDto;
use App\Entity\Media;
use App\Entity\MediaAiPrompt;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\MediaRepository;
use App\Repository\TagRepository;
use App\Service\ImageStocks\DataTransferObject\StockImageDto;
use App\Service\ImageStocks\ImageStocksFacade;
use App\Utility\MediaIdGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MediaBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly EntityManagerInterface $em,
        protected readonly string $contentDir,
        protected readonly Filesystem $filesystem,
        protected readonly MediaIdGenerator $mediaIdGenerator,
        protected readonly ImageStocksFacade $imageStocksFacade,
        protected readonly UrlGeneratorInterface $urlGenerator,
        protected readonly MediaRepository $mediaRepository,
        protected readonly TagRepository $tagRepository
    ) {}

    public function fromArray(
        array $data
    ): Media {
        dump([
            __METHOD__,
            $data
        ]);

        $isAlreadyExist = $data['id'] !== null && $data['url'] !== null;
        $isStoreFromContent = !$isAlreadyExist && $data['content'] !== null;
        $isStoreFromExternalResource = !$isAlreadyExist && $data['url'] !== null;
        $mediaTag = $data['mediaTag'] ?? 'user';
        $tags = array_unique($data['tags'] ?? []);

        dump([
            $isAlreadyExist,
            $isStoreFromContent,
            $isStoreFromExternalResource
        ]);

        $media = null;
        if ($isAlreadyExist) {
            $existedMedia = $this->mediaRepository->find($data['id']);
            if (null === $existedMedia) {
                throw new EntityNotFoundException(Media::class);
            }

            $media = $existedMedia;
        }

        if ($isStoreFromExternalResource) {
            dump([
                __METHOD__,
                'STORE FROM EXTERNAL RESOURCE',
            ]);

            if (!empty($data['url'])) {
                $host = parse_url($data['url'])['host'];
                dump([
                    'url' => $host,
                    'host' => $data['url'],
                ]);

                if (!in_array(
                    $host,
                    [
                        'prototyper.localhost',
                    ]
                )) {
                    $mediaTag = 'stock';
                    $imageStock = $this->imageStocksFacade->loadByUrl($data['url']);
                    dump($imageStock);
                    $data['content'] = $imageStock->content;
                    $data['extension'] = $imageStock->extension;
                    $data['mimeType'] = $imageStock->mimeType;
                    $data['size'] = $imageStock->size;
                    dump($data);

                    $isStoreFromContent = true;
                }
            }
        }

        if ($isStoreFromContent) {
            $dto = $this->mediaDtoByContent($data['content'], $tags);
            $dto->extension = $data['extension'];
            $dto->mimeType = $data['mimeType'];
            $dto->size = $data['size'] ?? 0;

            if (null === $media) {
                $media = new Media();;
            }

            $media->setId($dto->id);
            $media->setMimetype($dto->mimeType);
            $media->setExtension($dto->extension);
            $media->setSize($dto->size);

            if ($dto === null) {
                dump([
                    $isAlreadyExist,
                    $isStoreFromContent,
                    $isStoreFromExternalResource
                ]);
                throw new \Exception('No content found to create media.');
            }

            $existedMedia = $this->mediaRepository->find($media->getId());
            if ($existedMedia instanceof Media) {
                $media = $existedMedia;
            } else {
                $filePath = $this->generateFilePath(
                    $media->getRawId(),
                    $media->getExtension(),
                    $mediaTag
                );

                if (false === $this->filesystem->exists($filePath) && !empty($dto->content)) {
                    $content = base64_decode($dto->content);
                    if ($media->getServerAlias() === 'local') {
                        // TODO decentralized filesystem
                        $this->filesystem->dumpFile($filePath, $content);
                    }
                }

                $media->setPath($filePath);

                foreach ($dto->tags as $tag) {
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
            }
        }

        if (empty($media?->getMediaAiPrompt()) && !empty($data['mediaAiPromptId'])) {
            $mediaAiPromptRepository = $this->em->getRepository(MediaAiPrompt::class);
            $mediaAiPrompt = $mediaAiPromptRepository->find($data['mediaAiPromptId']);
            $media->setMediaAiPrompt($mediaAiPrompt);
        }

        dump($media);

        return $media;
    }

    public function mediaDtoByUploadedFile(
        User $owner,
        ?UploadedFile $file = null,
        array $tags = [],
    ): ?MediaDto {
        dump($file);
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
        $dto->url = null;
        $dto->mediaAiPromptId = null;


        $media = $this->mediaRepository->find($dto->id);

        if (!$media instanceof Media) {
            $filePath = $this->generateFilePath(
                $dto->id,
                $dto->extension,
            );

            if (!$this->filesystem->exists($filePath)) {
                $this->filesystem->dumpFile($filePath, $content);
            }

            $media = $this->mediaByMediaDto($dto);
            $media->setOwner($owner);
            $media->setMediaTag('user');
            $media->setPath($filePath);

            $this->mediaRepository->add($media);
            $this->mediaRepository->save();
        }

        return $this->mediaDtoByMedia($media);
    }

    public function mediaByMediaDto(MediaDto $dto): Media
    {
        $media = new Media();
        $media->setId($dto->id);
        $media->setExtension($dto->extension);
        $media->setMimeType($dto->mimeType);
        $media->setSize($dto->size);
        $media->setMediaAiPrompt($dto->mediaAiPromptId);

        foreach ($dto->tags as $tag) {
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

    public function mediaDtoByStockImage(StockImageDto $stockImage): MediaDto
    {
        $media = new MediaDto(
            null,
            $stockImage->mimeType,
            $stockImage->extension,
            $stockImage->size,
            $stockImage->version,
            $stockImage->tags,
            $stockImage->ownerId,
            $stockImage->content,
            $stockImage->url,
        );
        $media->id = $this->generateMediaId($media);

        return $media;
    }

    public function mediaDtoByMediaId(?string $id = null): ?MediaDto
    {
        if (null === $id) return null;
        $media = $this->mediaRepository->find($id);

        if (null === $media) return null;

        return $this->mediaDtoByMedia($media);
    }

    public function mediaDtoByMedia(Media $media): MediaDto
    {
        $dto = new MediaDto();
        $dto->id = $media->getId();
        $dto->ownerId = $media->getOwner()->getRawId();
        $dto->mimeType = $media->getMimeType();
        $dto->extension = $media->getExtension();
        $dto->size = $media->getSize();
        $dto->version = $media->getVersion();
        $dto->ownerId = $media->getOwner()->getRawId();
        $dto->tags = $media->getTags()->map(fn(Tag $tag) => $tag->getRawId())->toArray();
        $dto->mediaAiPromptId = $media->getMediaAiPrompt()?->getRawId();

        $dto->url = $this->urlGenerator->generate('app_media_show', ['media' => $media->getRawId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return $dto;
    }

    public function mediaDtoByContent(
        ?string $content = null,
        array $tags = []
    ): ?MediaDto {
        dump($content);
        if (null === $content) return null;

        $dto = new MediaDto();
        $dto->tags = $tags;

        $contentData = explode('|||', trim($content));
        dump($contentData);
        if (count($contentData) < 2) {
            $dto->content = $content;
        } else {
            $dto->content = $contentData[2];
            $dto->mimeType = $contentData[1];
            $dto->extension = $contentData[0];
        }

        $dto->id = $this->generateMediaId($dto);

        return $dto;
    }

    public function generateMediaId(MediaDto $media): string
    {
        return $this->mediaIdGenerator->generate($media->content, $media->version);
    }

    public function generateFilePath(
        string $fileId,
        string $fileExtension,
        string $prefix = 'user',
        int $nameSplitIndex = 6
    ): string {
        $fileId = implode('/', str_split($fileId, $nameSplitIndex));

        return sprintf(
            '%s/%s/%s.%s',
            $this->contentDir,
            $prefix,
            $fileId,
            $fileExtension
        );
    }
}
