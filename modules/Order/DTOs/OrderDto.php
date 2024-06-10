<?php

namespace Modules\Order\DTOs;

use Modules\Order\Models\Order;

readonly class OrderDto
{
    /**
     *
     * @param integer $id
     * @param integer $totalInPiasters
     * @param string $localizedTotal
     * @param string $url
     * @param OrderLineDto[] $lines
     */
    public function __construct(
        public int $id,
        public int $totalInPiasters,
        public string $localizedTotal,
        public string $url,
        public array $lines,
    ) {
    }

    public static function fromEloquentModel(Order $order): self
    {
        return new OrderDto(
            $order->id,
            $order->total_in_piasters,
            $order->localizedTotal(),
            $order->url(),
            OrderLineDto::fromEloquentCollection($order->lines),
        );
    }
}
