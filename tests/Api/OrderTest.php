<?php
declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Model\OrderData;
use App\Model\OrderItemData;
use App\NameInterface\OrderDataNameInterface;
use App\NameInterface\RouteInterface;
use App\Service\Api\OrderService;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderTest extends DatabaseDependantTestCase
{
    final public function test_post_new_order(): void
    {
        $client = static::createClient();

        $testUri = RouteInterface::API_ORDER_ROUTE . RouteInterface::API_ORDER_POST_SINGLE_ROUTE;

        $items[] = (new OrderItem())
            ->setProductId('1')
            ->setProductName('Product A')
            ->setPrice(100)
            ->setQuantity(2);

        $items[] = (new OrderItem())
            ->setProductId('2')
            ->setProductName('Product B')
            ->setPrice(50)
            ->setQuantity(1);

        $itemsTotal = 0;
        foreach ($items as $item) {
            $itemsTotal += $item->getPrice() * $item->getQuantity();
        }

        $client->request(
                     Request::METHOD_POST,
                     $testUri,
            server:  ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            content: $this->serializer->serialize([OrderDataNameInterface::ITEMS => $items], 'json'),
        );

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());

        /** @var OrderData $responseOrderData */
        $responseOrderData = $this->serializer->deserialize($response->getContent(), OrderData::class, 'json');

        $this->assertNotEmpty($responseOrderData?->getId());
        $this->assertNotEmpty($responseOrderData?->getCreatedAt());
        $this->assertSame(OrderStatus::NEW->value, $responseOrderData?->getStatus());
        $this->assertEquals($itemsTotal, $responseOrderData->getTotal());
        $this->assertCount(count($items), $responseOrderData->getItems());

        foreach ($items as $index => $item) {
            $this->assertEquals($item->getProductId(), $responseOrderData->getItems()[$index]->getProductId());
            $this->assertEquals($item->getProductName(), $responseOrderData->getItems()[$index]->getProductName());
            $this->assertEquals($item->getPrice(), $responseOrderData->getItems()[$index]->getPrice());
            $this->assertEquals($item->getQuantity(), $responseOrderData->getItems()[$index]->getQuantity());
        }
    }

    final public function test_get_one_order(): void
    {
        $client = static::createClient();
        $orderService = self::getContainer()->get(OrderService::class);

        $testUri = RouteInterface::API_ORDER_ROUTE . RouteInterface::API_ORDER_GET_SINGLE_ROUTE;

        $items[] = (new OrderItemData())
            ->setProductId('1')
            ->setProductName('Product A1')
            ->setPrice(100)
            ->setQuantity(2);

        $items[] = (new OrderItemData())
            ->setProductId('2')
            ->setProductName('Product B1')
            ->setPrice(50)
            ->setQuantity(10);

        $itemsTotal = 0;
        foreach ($items as $item) {
            $itemsTotal += $item->getPrice() * $item->getQuantity();
        }

        $orderData = (new OrderData())->setItems($items);
        $order = $orderService->saveOrder($orderData, true);

        $testUri = str_replace('{id}', $order->getId(), $testUri);

        $client->request(
                     Request::METHOD_GET,
                     $testUri,
            server:  ['Accept' => 'application/json'],
        );

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        /** @var OrderData $responseOrderData */
        $responseOrderData = $this->serializer->deserialize($response->getContent(), OrderData::class, 'json');

        $this->assertNotEmpty($responseOrderData?->getId());
        $this->assertNotEmpty($responseOrderData?->getCreatedAt());
        $this->assertSame(OrderStatus::NEW->value, $responseOrderData?->getStatus());
        $this->assertEquals($itemsTotal, $responseOrderData->getTotal());
        $this->assertCount(count($items), $responseOrderData->getItems());

        foreach ($items as $index => $item) {
            $this->assertEquals($item->getProductId(), $responseOrderData->getItems()[$index]->getProductId());
            $this->assertEquals($item->getProductName(), $responseOrderData->getItems()[$index]->getProductName());
            $this->assertEquals($item->getPrice(), $responseOrderData->getItems()[$index]->getPrice());
            $this->assertEquals($item->getQuantity(), $responseOrderData->getItems()[$index]->getQuantity());
        }
    }

    final public function test_update_order_status(): void
    {
        $client = static::createClient();
        $orderService = self::getContainer()->get(OrderService::class);

        $testUri = RouteInterface::API_ORDER_ROUTE . RouteInterface::API_ORDER_PATCH_SINGLE_ROUTE;

        $items[] = (new OrderItemData())
            ->setProductId('1')
            ->setProductName('Product AA')
            ->setPrice(100)
            ->setQuantity(2);

        $items[] = (new OrderItemData())
            ->setProductId('2')
            ->setProductName('Product BB')
            ->setPrice(20)
            ->setQuantity(3);

        $itemsTotal = 0;
        foreach ($items as $item) {
            $itemsTotal += $item->getPrice() * $item->getQuantity();
        }

        $orderData = (new OrderData())->setItems($items);
        $order = $orderService->saveOrder($orderData, true);

        $testUri = str_replace('{id}', $order->getId(), $testUri);
        $newStatus = OrderStatus::PAID->value;

        $client->request(
                     Request::METHOD_PATCH,
                     $testUri,
            server:  ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            content: json_encode([OrderDataNameInterface::STATUS => $newStatus]),
        );

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());

        /** @var OrderData $responseOrderData */
        $responseOrderData = $this->serializer->deserialize($response->getContent(), OrderData::class, 'json');

        $this->assertNotEmpty($responseOrderData?->getId());
        $this->assertNotEmpty($responseOrderData?->getCreatedAt());
        $this->assertSame($newStatus, $responseOrderData?->getStatus());
        $this->assertEquals($itemsTotal, $responseOrderData->getTotal());
        $this->assertCount(count($items), $responseOrderData->getItems());

        foreach ($items as $index => $item) {
            $this->assertEquals($item->getProductId(), $responseOrderData->getItems()[$index]->getProductId());
            $this->assertEquals($item->getProductName(), $responseOrderData->getItems()[$index]->getProductName());
            $this->assertEquals($item->getPrice(), $responseOrderData->getItems()[$index]->getPrice());
            $this->assertEquals($item->getQuantity(), $responseOrderData->getItems()[$index]->getQuantity());
        }
    }

}
