<?php

declare(strict_types=1);

namespace App\Util\Trait;

use App\Exception\ApiException;
use App\NameInterface\ExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait ApiSecurityTrait
{
    /**
     * @throws ApiException
     */
    final public function verifyAccess(Request $request): void
    {

        //$authHeader = $request->headers->get('Authorization');
        $authHeader = 'to implement';

        if (empty($authHeader)) {
            throw new ApiException(ExceptionInterface::API_UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
        }

        if (!$this->checkPermission($authHeader)) {
            throw new ApiException(ExceptionInterface::API_ACCESS_DENIED, Response::HTTP_FORBIDDEN);
        }
    }

    private function checkPermission(string $authHeader): bool
    {
        /*
         * Verify user has access
         * (...)
         */

        return true;
    }
}
