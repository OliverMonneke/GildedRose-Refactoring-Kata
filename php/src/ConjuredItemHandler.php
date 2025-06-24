<?php

declare(strict_types=1);

namespace GildedRose;

final class ConjuredItemHandler implements ItemHandler
{
    private const MIN_QUALITY = 0;

    public function handles(Item $item): bool
    {
        return str_starts_with($item->name, 'Conjured');
    }
    
    public function updateQuality(Item $item): void
    {
        $item->sellIn--;
        
        // Conjured items degrade twice as fast as normal items
        if ($item->quality > self::MIN_QUALITY) {
            $item->quality--;
        }
        if ($item->quality > self::MIN_QUALITY) {
            $item->quality--;
        }
        
        // After sell date, they degrade twice as fast again (4x total)
        if ($item->sellIn < 0) {
            if ($item->quality > self::MIN_QUALITY) {
                $item->quality--;
            }
            if ($item->quality > self::MIN_QUALITY) {
                $item->quality--;
            }
        }
    }
} 