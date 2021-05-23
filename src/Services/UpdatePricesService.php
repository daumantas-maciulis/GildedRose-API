<?php


namespace App\Services;


use App\Entity\Item;
use App\Model\ItemModel;

class UpdatePricesService
{
    private $items;
    private $itemModel;

    private const BRIE = "Aged Brie";
    private const PASSES = "Backstage passes";
    private const SULFURAS = "Sulfuras";
    private const CONJURED = "Conjured";

    private const SPECIAL_ITEMS = [
        self::BRIE,
        self::PASSES,
        self::SULFURAS
    ];

    public function __construct(ItemModel $itemModel)
    {
        $this->items = $itemModel->getAllItems();
        $this->itemModel = $itemModel;
    }

    public function updateQuality(): void
    {
        /** @var Item $item */
        foreach ($this->items as $item) {
            if ($item->getCategoryName() != self::SULFURAS) {
                $item->setSellIn($item->getSellIn() - 1);
            }

            if (in_array($item->getCategoryName(), self::SPECIAL_ITEMS, true) == false && $item->getQuality() > 0) {
                $item->setQuality($item->getQuality() - 1);
                if ($item->getCategoryName() === self::CONJURED) {
                    $item->setQuality($item->getQuality() - 1);
                }
            }
            if (in_array($item->getCategoryName(), self::SPECIAL_ITEMS, true) && $item->getQuality() < 50) {
                $item->setQuality($item->getQuality() + 1);
                if ($item->getCategoryName() == self::PASSES) {
                    $item->setQuality($this->itemIsConcertPasses($item)) ;
                }
            }
            if ($item->getSellIn() < 0) {
                $item->setQuality($this->sellInLessThanZero($item));
            }

            $this->itemModel->updateItemFromCommand($item);
        }
    }

    private function sellInLessThanZero(Item $item): int
    {
        $itemCategoryName = $item->getCategoryName();
        if ($itemCategoryName = self::BRIE && $item->getQuality() < 50) {
            return $item->getQuality() + 1;
        }
        if ($itemCategoryName = self::PASSES) {
            return 0;
        }
        if ($item->getQuality() > 0 && $itemCategoryName != self::SULFURAS) {
            return $item->getQuality() - 1;
        }
    }

    private function itemIsConcertPasses(Item $item): int
    {
        if ($item->getSellIn() < 11 && $item->getQuality() < 50) {
            $item->setQuality($item->getQuality() + 1);
        }
        if ($item->getSellIn() < 6 && $item->getQuality() < 50) {
            $item->setQuality($item->getQuality() + 1);
        }

        return $item->getQuality();
    }
}