<?php

declare(strict_types=1);

namespace GildedRose;

final class AgedBrieHandler implements ItemHandler
{
    private const MAX_QUALITY = 50;

    public function handles(Item $item): bool
    {
        return $item->name === 'Aged Brie';
    }
    
    public function updateQuality(Item $item): void
    {
        $item->sellIn--;
        
        // Aged Brie increases in quality as it gets older
        if ($item->quality < self::MAX_QUALITY) {
            $item->quality++;
        }
        
        // After sell date, quality increases twice as fast
        if ($item->sellIn < 0 && $item->quality < self::MAX_QUALITY) {
            $item->quality++;
        }
    }
} 