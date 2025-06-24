<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testNormalItemAtSellDate(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        // Name should remain unchanged
        $this->assertSame('foo', $items[0]->name);
        // sellIn should decrease by 1
        $this->assertSame(-1, $items[0]->sellIn);
        // quality should not go below 0
        $this->assertSame(0, $items[0]->quality);
    }
}
