<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Item;
use App\Model\ItemModel;

class UpdatePricesService
{
    private array $items;
    private ItemModel $itemModel;

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
            if ($item->getCategoryName() != self::BRIE && $item->getCategoryName() != self::PASSES && $item->getQuality() > 0) {
                if ($item->getCategoryName() != self::SULFURAS) {
                    $item->setQuality($item->getQuality() - 1);
                }
                if ($item->getCategoryName() == self::CONJURED) {
                    $item->setQuality($item->getQuality() - 1);
                }
            } else {
                if ($item->getQuality() < 50) {
                    $item->setQuality($item->getQuality() + 1);
                }
                if ($item->getCategoryName() == self::PASSES && $item->getQuality() < 50) {
                    $item->setQuality($this->updateConcertPassesQuality($item));
                }
            }

            if ($item->getCategoryName() != self::SULFURAS) {
                $item->setSellIn($item->getSellIn() - 1);
            }

            if ($item->getSellIn() < 0) {
                $item->setQuality($this->updateSellInDateIfZero($item));
            }
        }
    }

    private function updateConcertPassesQuality(Item $item): int
    {
        if ($item->getSellIn() < 11) {
            $item->setQuality($item->getQuality() + 1);
        }
        if ($item->getSellIn() < 6) {
            $item->setQuality($item->getQuality() + 1);
        }

        return $item->getQuality();
    }

    private function updateSellInDateIfZero(Item $item): int
    {
        if ($item->getCategoryName() == self::PASSES && $item->getCategoryName() != self::BRIE) {
            $item->setQuality(0);
        }
        if ($item->getName() == self::BRIE && $item->getQuality() < 50) {
            $item->setQuality($item->getQuality() + 1);
        }
        if (in_array($item->getCategoryName(), self::SPECIAL_ITEMS) == false && $item->getQuality() > 0) {
            $item->setQuality($item->getQuality() - 1);
        }
        if ($item->getCategoryName() == self::CONJURED) {
            $item->setQuality($item->getQuality() - 2);
        }

        return $item->getQuality();
    }
}

