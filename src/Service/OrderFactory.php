<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Model\OrderData;
use DateTime;
use Symfony\Component\Uid\Uuid;

final readonly class OrderFactory
{
    public function make(OrderData $orderData): Order
    {
        $order = new Order();
        $order->setCreatedAt(new DateTime());
        $order->setStatus(OrderStatus::tryFrom($orderData->getStatus()));
        $total = 0;

        foreach ($orderData->items as $itemData) {
            $item = (new OrderItem())
                ->setId(Uuid::v4())
                ->setProductId($itemData->productId)
                ->setProductName($itemData->productName)
                ->setPrice($itemData->price)
                ->setQuantity($itemData->quantity)
                ->setOrder($order);

            $order->addItem($item);
            $total += $itemData->price * $itemData->quantity;
        }

        $order->setTotal($total);

        return $order;
    }
}
