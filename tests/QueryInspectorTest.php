<?php

declare(strict_types=1);

namespace SmartAssert\Tests\DoctrineInspectors;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManagerInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SmartAssert\DoctrineInspectors\QueryInspector;

class QueryInspectorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @dataProvider invokeDataProvider
     *
     * @param array<string, scalar> $queryParameters
     */
    public function testInvoke(string $query, array $queryParameters): void
    {
        $statement = \Mockery::mock(Statement::class);
        $statement
            ->shouldReceive('executeQuery')
            ->once()
            ->with($queryParameters)
        ;

        $connection = \Mockery::mock(Connection::class);
        $connection
            ->shouldReceive('prepare')
            ->once()
            ->with($query)
            ->andReturn($statement)
        ;

        $entityManager = \Mockery::mock(EntityManagerInterface::class);
        $entityManager
            ->shouldReceive('getConnection')
            ->once()
            ->andReturn($connection)
        ;

        $inspector = new QueryInspector($entityManager, $query, $queryParameters);

        $inspector->getStatus();
    }

    /**
     * @return array<mixed>
     */
    public static function invokeDataProvider(): array
    {
        return [
            'query, no parameters' => [
                'query' => 'SELECT 1',
                'queryParameters' => [],
            ],
            'query, has parameters' => [
                'query' => 'SELECT name FROM Entity WHERE id = :id',
                'queryParameters' => [
                    'id' => 123,
                ],
            ],
        ];
    }
}
