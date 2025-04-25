<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\OrderData;
use App\NameInterface\RouteInterface;
use App\Service\Api\OrderService;
use App\Util\Trait\ApiExceptionTrait;
use App\Util\Trait\ApiSecurityTrait;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

#[Route(RouteInterface::API_ORDER_ROUTE)]
final class OrderController extends AbstractController
{
    use ApiExceptionTrait;
    use ApiSecurityTrait;

    #[Route(RouteInterface::API_ORDER_GET_SINGLE_ROUTE, name: RouteInterface::API_ORDER_GET_SINGLE_NAME,  methods: [Request::METHOD_GET])]
    public function getSingle(
        Uuid            $id,
        Request         $request,
        OrderService    $orderService,
    ): JsonResponse
    {
        try {
            $this->verifyAccess($request);

            $orderData = $orderService->fetchOrderData($id);

            return $this->json($orderData, Response::HTTP_OK);
        } catch (Throwable $throwable) {
            return $this->printError($throwable);
        }
    }

    #[Route(RouteInterface::API_ORDER_POST_SINGLE_ROUTE, name: RouteInterface::API_ORDER_POST_SINGLE_NAME, methods: [Request::METHOD_POST])]
    public function createSingle(
        Request             $request,
        SerializerInterface $serializer,
        OrderService        $orderService,
        LoggerInterface     $apiLogger,
    ): JsonResponse {
        try {
            $this->verifyAccess($request);

            /** @var OrderData $data */
            $data = $serializer->deserialize($request->getContent(), OrderData::class, 'json');

            $orderData = $orderService->saveOrder($data, true);

            return $this->json($orderData, Response::HTTP_CREATED);
        } catch (Throwable $throwable) {
            $apiLogger->error($throwable->getMessage(), $this->logDetails($throwable));

            return $this->printError($throwable);
        }
    }

    #[Route(RouteInterface::API_ORDER_PATCH_SINGLE_ROUTE, name: RouteInterface::API_ORDER_PATCH_SINGLE_NAME, methods: [Request::METHOD_PATCH])]
    public function updateStatus(
        Uuid            $id,
        Request         $request,
        OrderService    $orderService,
    ): JsonResponse {
        try {
            $this->verifyAccess($request);

            $orderData = $orderService->updateStatus($id, $request);

            return $this->json($orderData, Response::HTTP_CREATED);
        } catch (Throwable $throwable) {
            return $this->printError($throwable);
        }
    }
}
