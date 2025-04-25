<?php

declare(strict_types=1);

namespace App\Util\Trait;

use App\Exception\ApiException;
use App\NameInterface\ExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

trait ApiExceptionTrait
{
    final public function printError(Throwable $throwable): JsonResponse
    {
        if ($throwable instanceof ApiException) {
            return new JsonResponse($throwable->getMessage(), $throwable->getCode());
        }

        return new JsonResponse(ExceptionInterface::API_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    final public function logDetails(Throwable $throwable): array
    {
        return [
            'message'  => $throwable->getMessage(),
            'class'    => get_class($throwable),
            'file'     => $throwable->getFile(),
            'trace'    => $throwable->getTraceAsString(),
            'line'     => $throwable->getLine(),
        ];
    }
}
