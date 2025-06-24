<?php

declare(strict_types=1);

namespace GildedRose;

interface ItemHandler
{
    public function handles(Item $item): bool;
    
    public function updateQuality(Item $item): void;
} 