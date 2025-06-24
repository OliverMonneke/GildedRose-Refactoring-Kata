<?php

declare(strict_types=1);

namespace GildedRose;

final class BackstagePassHandler implements ItemHandler
{
    private const MAX_QUALITY = 50;

    public function handles(Item $item): bool
    {
        return $item->name === 'Backstage passes to a TAFKAL80ETC concert';
    }
    
    public function updateQuality(Item $item): void
    {
        $item->sellIn--;
        
        if ($item->sellIn < 0) {
            // After concert, passes become worthless
            $item->quality = 0;
            return;
        }
        
        // Quality increases as concert approaches
        if ($item->quality < self::MAX_QUALITY) {
            $item->quality++; // Base increase
            
            if ($item->sellIn < 10 && $item->quality < self::MAX_QUALITY) {
                $item->quality++; // Additional increase when 10 days or less
            }
            
            if ($item->sellIn < 5 && $item->quality < self::MAX_QUALITY) {
                $item->quality++; // Another increase when 5 days or less
            }
        }
    }
} 