<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /** @var ItemHandler[] */
    private array $handlers;

    /**
     * @param Item[] $items
     * @param ItemHandler[]|null $handlers
     */
    public function __construct(
        private array $items,
        ?array $handlers = null
    ) {
        $this->handlers = $handlers ?? $this->getDefaultHandlers();
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $handler = $this->findHandlerForItem($item);
            $handler->updateQuality($item);
        }
    }

    private function findHandlerForItem(Item $item): ItemHandler
    {
        foreach ($this->handlers as $handler) {
            if ($handler->handles($item)) {
                return $handler;
            }
        }
        
        throw new \RuntimeException("No handler found for item: {$item->name}");
    }

    /**
     * @return ItemHandler[]
     */
    private function getDefaultHandlers(): array
    {
        return [
            new SulfurasHandler(),
            new AgedBrieHandler(),
            new BackstagePassHandler(),
            new ConjuredItemHandler(),
            new NormalItemHandler(), // Fallback handler - must be last
        ];
    }
}

