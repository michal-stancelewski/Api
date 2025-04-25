<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Model\OrderData;
use App\Model\OrderItemData;

final readonly class OrderDataFactory
{
    public function make(Order $order): OrderData
    {
        $items = [];

        /** @var OrderItem $item */
        foreach ($order->getItems() as $item) {
            $items[] = (new OrderItemData())
                ->setProductId($item->getProductId())
                ->setProductName($item->getProductName())
                ->setPrice($item->getPrice())
                ->setQuantity($item->getQuantity());
        }

        return (new OrderData())
            ->setId($order->getId()->toRfc4122())
            ->setCreatedAt($order->getCreatedAt()->format('Y-m-d H:i:s'))
            ->setStatus($order->getStatus()->value)
            ->setTotal($order->getTotal())
            ->setItems($items);
    }
}
