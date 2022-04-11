<?php

declare(strict_types=1);

namespace SmartAssert\DoctrineInspectors;

use Doctrine\DBAL\Driver\Exception as DbalDriverException;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\ORM\EntityManagerInterface;
use SmartAssert\ServiceStatusInspector\ComponentInspectorInterface;

class QueryInspector implements ComponentInspectorInterface
{
    public const DEFAULT_IDENTIFIER = 'database_connection';

    /**
     * @param array<string, scalar> $queryParameters
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private readonly string $identifier = self::DEFAULT_IDENTIFIER,
        private string $query = 'SELECT 1',
        private array $queryParameters = []
    ) {
    }

    /**
     * @throws DbalDriverException
     * @throws DbalException
     */
    public function isAvailable(): bool
    {
        $connection = $this->entityManager->getConnection();

        $statement = $connection->prepare($this->query);
        $statement->executeQuery($this->queryParameters);

        return true;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
