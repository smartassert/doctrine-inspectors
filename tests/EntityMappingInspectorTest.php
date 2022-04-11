<?php

declare(strict_types=1);

namespace SmartAssert\Tests\DoctrineInspectors;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SmartAssert\DoctrineInspectors\EntityMappingInspector;

class EntityMappingInspectorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testIsAvailableSuccess(): void
    {
        $entityClassNames = [
            'Example\Foo',
            'Example\Bar',
        ];

        $classMetadataCollection = [];
        foreach ($entityClassNames as $entityClassName) {
            $classMetadata = \Mockery::mock(ClassMetadata::class);
            $classMetadata
                ->shouldReceive('getName')
                ->andReturn($entityClassName)
            ;

            $classMetadataCollection[] = $classMetadata;
        }

        $metadataFactory = \Mockery::mock(ClassMetadataFactory::class);
        $metadataFactory
            ->shouldReceive('getAllMetadata')
            ->once()
            ->andReturn($classMetadataCollection)
        ;

        $entityManager = \Mockery::mock(EntityManagerInterface::class);
        $entityManager
            ->shouldReceive('getMetadataFactory')
            ->once()
            ->andReturn($metadataFactory)
        ;

        foreach ($entityClassNames as $entityClassName) {
            $entityManager
                ->shouldReceive('getRepository')
                ->once()
                ->with($entityClassName)
            ;
        }

        $inspector = new EntityMappingInspector($entityManager);

        $inspector->isAvailable();
    }

    public function testIsAvailableFailure(): void
    {
        $exception = new \Exception();

        $classMetadata = \Mockery::mock(ClassMetadata::class);
        $classMetadata
            ->shouldReceive('getName')
            ->andReturn('Example\Foo')
        ;

        $metadataFactory = \Mockery::mock(ClassMetadataFactory::class);
        $metadataFactory
            ->shouldReceive('getAllMetadata')
            ->andReturn([$classMetadata])
        ;

        $entityManager = \Mockery::mock(EntityManagerInterface::class);
        $entityManager
            ->shouldReceive('getMetadataFactory')
            ->andReturn($metadataFactory)
        ;

        $entityManager
            ->shouldReceive('getRepository')
            ->andThrow($exception)
        ;

        $inspector = new EntityMappingInspector($entityManager);

        self::expectExceptionObject($exception);

        $inspector->isAvailable();
    }
}
