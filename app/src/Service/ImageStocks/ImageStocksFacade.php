<?php
declare(strict_types=1);

namespace App\Service\ImageStocks;

use App\Service\ImageStocks\Adapter\PicsumAdapter;
use App\Service\ImageStocks\DataTransferObject\StockImageDto;

class ImageStocksFacade
{
    public function __construct(
        protected readonly PicsumAdapter $picsumAdapter
    ) {}

    public function findOneByTags(array $tags)
    {

    }
    public function findOneRandom(
        array $tags = [],
        array $size = [512,512]
    ): ?StockImageDto {
        return $this->picsumAdapter->findOneRandom($tags, $size);
    }
}
