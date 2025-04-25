<?php
declare(strict_types=1);

namespace App\Service\Api;

use App\Enum\OrderStatus;
use App\Exception\ApiException;
use App\Model\OrderData;
use App\NameInterface\ExceptionInterface;
use App\NameInterface\OrderDataNameInterface;
use App\Repository\OrderRepository;
use App\Service\OrderDataFactory;
use App\Service\OrderFactory;
use App\Service\ValidatorService;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final readonly class OrderService
{
    public function __construct(
        private OrderRepository  $orderRepository,
        private OrderDataFactory $orderDataFactory,
        private OrderFactory     $orderFactory,
        private ValidatorService $validator,
    )
    {
    }

    /**
     * @throws ApiException
     */
    final public function fetchOrderData(Uuid $orderId): OrderData
    {
        $order = $this->orderRepository->find($orderId);
        if (!$order) {
            throw new ApiException(ExceptionInterface::API_NOT_FOUND, Response::HTTP_NOT_FOUND);
        }

        return $this->orderDataFactory->make($order);
    }

    /**
     * @throws ApiException
     */
    final public function saveOrder(OrderData $orderData, bool $asNew = false): OrderData
    {
        if ($asNew) {
            $orderData
                ->setId(Uuid::v4()->toString())
                ->setCreatedAt((new DateTime())->format('Y-m-d H:i:s'))
                ->setStatus(OrderStatus::NEW->value)
                ->updateTotalFromItems();
        }

        $errors = $this->validator->validate($orderData);
        if (!empty($errors)) {
            throw new ApiException(json_encode($errors), Response::HTTP_BAD_REQUEST);
        }

        $order = $this->orderFactory->make($orderData);

        $this->orderRepository->save($order, true);
        $order = $this->orderRepository->refresh($order);

        return $this->orderDataFactory->make($order);
    }

    /**
     * @throws ApiException
     */
    final public function updateStatus(Uuid $id, Request $request): OrderData
    {
        $data = json_decode((string)$request->getContent(), true);

        if (empty($data) || !array_key_exists(OrderDataNameInterface::STATUS, $data)) {
            throw new ApiException(ExceptionInterface::API_BAD_REQUEST, Response::HTTP_BAD_REQUEST);
        }

        $status = $data[OrderDataNameInterface::STATUS];

        if (is_null(OrderStatus::tryFrom($status))) {
            throw new ApiException(ExceptionInterface::API_BAD_REQUEST, Response::HTTP_BAD_REQUEST);
        }

        $orderData = $this->fetchOrderData($id);
        $orderData->setStatus($status);

        return $this->saveOrder($orderData);
    }
}
