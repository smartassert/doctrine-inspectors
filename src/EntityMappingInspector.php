<?php

declare(strict_types=1);

namespace SmartAssert\DoctrineInspectors;

use Doctrine\ORM\EntityManagerInterface;
use SmartAssert\ServiceStatusInspector\ComponentInspectorInterface;

class EntityMappingInspector implements ComponentInspectorInterface
{
    public const DEFAULT_IDENTIFIER = 'database_entities';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private readonly string $identifier = self::DEFAULT_IDENTIFIER,
    ) {
    }

    public function isAvailable(): bool
    {
        $entityClassNames = [];
        foreach ($this->entityManager->getMetadataFactory()->getAllMetadata() as $metadata) {
            $entityClassNames[] = $metadata->getName();
        }

        foreach ($entityClassNames as $entityClassName) {
            $this->entityManager->getRepository($entityClassName);
        }

        return true;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
