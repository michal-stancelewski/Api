<?php
declare(strict_types=1);

namespace App\Tests\Api;

use App\Tests\DatabaseDependantTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTest extends DatabaseDependantTestCase
{
    final public function test_404_handled_correctly(): void
    {
        $client = static::createClient();

        $testUri = '/fake-route/';

        $client->request(
            Request::METHOD_GET,
            $testUri,
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

}
