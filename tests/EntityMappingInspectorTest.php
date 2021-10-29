<?php

declare(strict_types=1);

namespace SmartAssert\Tests\DoctrineInspectors;

use Doctrine\ORM\EntityManagerInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SmartAssert\DoctrineInspectors\EntityMappingInspector;

class EntityMappingInspectorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testInvokeSuccess(): void
    {
        $entityManager = \Mockery::mock(EntityManagerInterface::class);

        $inspector = new EntityMappingInspector($entityManager, []);

        ($inspector)();
        self::expectNotToPerformAssertions();
    }

    public function testInvokeFailure(): void
    {
        $exception = new \Exception();

        $entityManager = \Mockery::mock(EntityManagerInterface::class);
        $entityManager
            ->shouldReceive('getRepository')
            ->andThrow($exception)
        ;

        $inspector = new EntityMappingInspector($entityManager, [
            self::class,
        ]);

        self::expectExceptionObject($exception);

        ($inspector)();
    }
}
