<?php
declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Serializer\SerializerInterface;

class DatabaseDependantTestCase extends WebTestCase
{
    protected ?EntityManagerInterface $entityManager = null;
    protected ?SerializerInterface $serializer = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->serializer = self::getContainer()->get(SerializerInterface::class);

        $schemaTool = new SchemaTool($this->entityManager);
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->updateSchema($classes);

        $application = new Application(self::bootKernel());
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--env' => 'test',
            '--no-interaction' => true,
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);

        $input = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--env' => 'test',
            '--no-interaction' => true,
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);

        static::ensureKernelShutdown();
    }

    final public static function deleteTestObject(object $object): void
    {
        $container = static::getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
        $entityManager->remove($object);
        $entityManager->flush();
    }

    final public static function insertTestObject(object $object): void
    {
        $container = static::getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
        $entityManager->persist($object);
        $entityManager->flush();
    }
}
