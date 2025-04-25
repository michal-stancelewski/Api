<?php
declare(strict_types=1);

namespace App\Tests;

class ConnectionTest extends DatabaseDependantTestCase
{
    final public function test_database_connection(): void
    {
        $client = static::createClient();

        $container = $client->getContainer();
        $connection = $container->get('doctrine.dbal.default_connection');

        $this->assertTrue($connection->connect(), 'Database connection is not established');
    }
}
