<?php

declare(strict_types=1);

namespace SmartAssert\DoctrineInspectors;

use Doctrine\DBAL\Driver\Exception as DbalDriverException;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\ORM\EntityManagerInterface;
use SmartAssert\ServiceStatusInspector\ComponentStatusInspectorInterface;

readonly class QueryInspector implements ComponentStatusInspectorInterface
{
    public const DEFAULT_IDENTIFIER = 'database_connection';

    /**
     * @param array<string, scalar> $queryParameters
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private string $query = 'SELECT 1',
        private array $queryParameters = [],
        private string $identifier = self::DEFAULT_IDENTIFIER,
    ) {}

    /**
     * @throws DbalDriverException
     * @throws DbalException
     */
    public function getStatus(): bool
    {
        $connection = $this->entityManager->getConnection();

        $statement = $connection->prepare($this->query);
        foreach ($this->queryParameters as $param => $value) {
            $statement->bindValue($param, $value);
        }

        $statement->executeQuery();

        return true;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
