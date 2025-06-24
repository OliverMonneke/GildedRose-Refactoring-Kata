<?php

declare(strict_types=1);

namespace GildedRose;

final class SulfurasHandler implements ItemHandler
{
    public function handles(Item $item): bool
    {
        return $item->name === 'Sulfuras, Hand of Ragnaros';
    }
    
    public function updateQuality(Item $item): void
    {
        // Sulfuras never changes - legendary item
        // sellIn and quality remain constant
    }
} 