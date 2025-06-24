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

    public function testNormalItemBeforeSellDate(): void
    {
        $items = [new Item('Normal Item', 5, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Normal Item', $items[0]->name);
        $this->assertSame(4, $items[0]->sellIn);
        $this->assertSame(9, $items[0]->quality); // Quality decreases by 1
    }

    public function testNormalItemAfterSellDate(): void
    {
        $items = [new Item('Normal Item', -1, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Normal Item', $items[0]->name);
        $this->assertSame(-2, $items[0]->sellIn);
        $this->assertSame(8, $items[0]->quality); // Quality decreases by 2 after sell date
    }

    public function testAgedBrieBeforeSellDate(): void
    {
        $items = [new Item('Aged Brie', 2, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Aged Brie', $items[0]->name);
        $this->assertSame(1, $items[0]->sellIn);
        $this->assertSame(1, $items[0]->quality); // Aged Brie increases in quality
    }

    public function testAgedBrieAfterSellDate(): void
    {
        $items = [new Item('Aged Brie', -1, 1)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Aged Brie', $items[0]->name);
        $this->assertSame(-2, $items[0]->sellIn);
        $this->assertSame(3, $items[0]->quality); // Aged Brie increases by 2 after sell date
    }

    public function testAgedBrieMaxQuality(): void
    {
        $items = [new Item('Aged Brie', 2, 50)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Aged Brie', $items[0]->name);
        $this->assertSame(1, $items[0]->sellIn);
        $this->assertSame(50, $items[0]->quality); // Quality cannot exceed 50
    }

    public function testSulfurasNeverChanges(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 0, 80)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Sulfuras, Hand of Ragnaros', $items[0]->name);
        $this->assertSame(0, $items[0]->sellIn); // sellIn doesn't change
        $this->assertSame(80, $items[0]->quality); // Quality doesn't change
    }

    public function testSulfurasWithNegativeSellIn(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', -1, 80)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Sulfuras, Hand of Ragnaros', $items[0]->name);
        $this->assertSame(-1, $items[0]->sellIn); // sellIn doesn't change
        $this->assertSame(80, $items[0]->quality); // Quality doesn't change
    }

    public function testBackstagePassesLongBeforeConcert(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(14, $items[0]->sellIn);
        $this->assertSame(21, $items[0]->quality); // Quality increases by 1
    }

    public function testBackstagePassesMediumBeforeConcert(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 10, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(9, $items[0]->sellIn);
        $this->assertSame(22, $items[0]->quality); // Quality increases by 2 when 10 days or less
    }

    public function testBackstagePassesShortBeforeConcert(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 5, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(4, $items[0]->sellIn);
        $this->assertSame(23, $items[0]->quality); // Quality increases by 3 when 5 days or less
    }

    public function testBackstagePassesAfterConcert(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', -1, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(-2, $items[0]->sellIn);
        $this->assertSame(0, $items[0]->quality); // Quality drops to 0 after concert
    }

    public function testBackstagePassesMaxQuality(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $items[0]->name);
        $this->assertSame(4, $items[0]->sellIn);
        $this->assertSame(50, $items[0]->quality); // Quality cannot exceed 50
    }

    public function testConjuredItemBeforeSellDate(): void
    {
        // Conjured items now correctly degrade twice as fast as normal items
        $items = [new Item('Conjured Mana Cake', 3, 6)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Conjured Mana Cake', $items[0]->name);
        $this->assertSame(2, $items[0]->sellIn);
        $this->assertSame(4, $items[0]->quality); // Degrades by 2 instead of 1
    }

    public function testConjuredItemAfterSellDate(): void
    {
        // Conjured items degrade 4x as fast after sell date (2x base * 2x expired)
        $items = [new Item('Conjured Mana Cake', -1, 6)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Conjured Mana Cake', $items[0]->name);
        $this->assertSame(-2, $items[0]->sellIn);
        $this->assertSame(2, $items[0]->quality); // Degrades by 4 after sell date
    }

    public function testQualityNeverNegative(): void
    {
        $items = [new Item('Normal Item', 2, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        $this->assertSame('Normal Item', $items[0]->name);
        $this->assertSame(1, $items[0]->sellIn);
        $this->assertSame(0, $items[0]->quality); // Quality should never go below 0
    }

    public function testMultipleItems(): void
    {
        $items = [
            new Item('+5 Dexterity Vest', 10, 20),
            new Item('Aged Brie', 2, 0),
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        
        // Normal item
        $this->assertSame('+5 Dexterity Vest', $items[0]->name);
        $this->assertSame(9, $items[0]->sellIn);
        $this->assertSame(19, $items[0]->quality);
        
        // Aged Brie
        $this->assertSame('Aged Brie', $items[1]->name);
        $this->assertSame(1, $items[1]->sellIn);
        $this->assertSame(1, $items[1]->quality);
        
        // Sulfuras
        $this->assertSame('Sulfuras, Hand of Ragnaros', $items[2]->name);
        $this->assertSame(0, $items[2]->sellIn);
        $this->assertSame(80, $items[2]->quality);
    }
}
