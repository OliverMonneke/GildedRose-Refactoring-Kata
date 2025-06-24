<?php

declare(strict_types=1);

namespace GildedRose;

final class NormalItemHandler implements ItemHandler
{
    private const MIN_QUALITY = 0;

    public function handles(Item $item): bool
    {
        // Handle all items that don't have special handlers
        return !$this->isSpecialItem($item);
    }
    
    public function updateQuality(Item $item): void
    {
        $item->sellIn--;
        
        // Quality decreases by 1 each day
        if ($item->quality > self::MIN_QUALITY) {
            $item->quality--;
        }
        
        // After sell date, quality decreases twice as fast
        if ($item->sellIn < 0 && $item->quality > self::MIN_QUALITY) {
            $item->quality--;
        }
    }
    
    private function isSpecialItem(Item $item): bool
    {
        return $item->name === 'Aged Brie'
            || $item->name === 'Backstage passes to a TAFKAL80ETC concert'
            || $item->name === 'Sulfuras, Hand of Ragnaros'
            || str_starts_with($item->name, 'Conjured');
    }
} 