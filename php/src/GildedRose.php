<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    private const MAX_QUALITY = 50;
    private const MIN_QUALITY = 0;

    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->updateItemQuality($item);
        }
    }

    private function updateItemQuality(Item $item): void
    {
        if ($this->isAgedBrie($item) || $this->isBackstagePass($item)) {
            $this->handleSpecialItems($item);
        } else {
            $this->handleNormalItems($item);
        }

        if (!$this->isSulfuras($item)) {
            $item->sellIn = $item->sellIn - 1;
        }

        if ($item->sellIn < 0) {
            $this->handleExpiredItems($item);
        }
    }

    private function handleSpecialItems(Item $item): void
    {
        if ($this->canIncreaseQuality($item)) {
            $this->increaseQuality($item);
            
            if ($this->isBackstagePass($item)) {
                if ($item->sellIn < 11 && $this->canIncreaseQuality($item)) {
                    $this->increaseQuality($item);
                }
                if ($item->sellIn < 6 && $this->canIncreaseQuality($item)) {
                    $this->increaseQuality($item);
                }
            }
        }
    }

    private function handleNormalItems(Item $item): void
    {
        if ($this->canDecreaseQuality($item) && !$this->isSulfuras($item)) {
            $this->decreaseQuality($item);
        }
    }

    private function handleExpiredItems(Item $item): void
    {
        if ($this->isAgedBrie($item)) {
            if ($this->canIncreaseQuality($item)) {
                $this->increaseQuality($item);
            }
        } elseif ($this->isBackstagePass($item)) {
            $this->resetQuality($item);
        } else {
            if ($this->canDecreaseQuality($item) && !$this->isSulfuras($item)) {
                $this->decreaseQuality($item);
            }
        }
    }

    private function increaseQuality(Item $item): void
    {
        if ($item->quality < self::MAX_QUALITY) {
            $item->quality++;
        }
    }

    private function decreaseQuality(Item $item): void
    {
        if ($item->quality > self::MIN_QUALITY) {
            $item->quality--;
        }
    }

    private function resetQuality(Item $item): void
    {
        $item->quality = self::MIN_QUALITY;
    }

    private function canIncreaseQuality(Item $item): bool
    {
        return $item->quality < self::MAX_QUALITY;
    }

    private function canDecreaseQuality(Item $item): bool
    {
        return $item->quality > self::MIN_QUALITY;
    }

    private function isAgedBrie(Item $item): bool
    {
        return $item->name === 'Aged Brie';
    }

    private function isBackstagePass(Item $item): bool
    {
        return $item->name === 'Backstage passes to a TAFKAL80ETC concert';
    }

    private function isSulfuras(Item $item): bool
    {
        return $item->name === 'Sulfuras, Hand of Ragnaros';
    }
}
