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
            ->with($queryParameters)
        ;

        $connection = \Mockery::mock(Connection::class);
        $connection
            ->shouldReceive('prepare')
            ->with($query)
            ->andReturn($statement)
        ;

        $entityManager = \Mockery::mock(EntityManagerInterface::class);
        $entityManager
            ->shouldReceive('getConnection')
            ->andReturn($connection)
        ;

        $inspector = new QueryInspector(
            $entityManager,
            QueryInspector::DEFAULT_IDENTIFIER,
            $query,
            $queryParameters
        );

        $inspector->isAvailable();
    }

    /**
     * @return array<mixed>
     */
    public function invokeDataProvider(): array
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
