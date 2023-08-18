<?php

declare(strict_types=1);

namespace SmartAssert\DoctrineInspectors;

use Doctrine\DBAL\Driver\Exception as DbalDriverException;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\ORM\EntityManagerInterface;
use SmartAssert\ServiceStatusInspector\ComponentStatusInspectorInterface;

class QueryInspector implements ComponentStatusInspectorInterface
{
    /**
     * @param array<string, scalar> $queryParameters
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private string $query = 'SELECT 1',
        private array $queryParameters = []
    ) {
    }

    /**
     * @throws DbalDriverException
     * @throws DbalException
     */
    public function getStatus(): bool
    {
        $connection = $this->entityManager->getConnection();

        $statement = $connection->prepare($this->query);
        $statement->executeQuery($this->queryParameters);

        return true;
    }
}
