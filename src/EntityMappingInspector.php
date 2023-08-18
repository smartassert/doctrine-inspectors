<?php

declare(strict_types=1);

namespace SmartAssert\DoctrineInspectors;

use Doctrine\ORM\EntityManagerInterface;
use SmartAssert\ServiceStatusInspector\ComponentStatusInspectorInterface;

class EntityMappingInspector implements ComponentStatusInspectorInterface
{
    public const IDENTIFIER = 'database_entities';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private readonly string $identifier =
    ) {
    }

    public function __invoke(): void
    {
        $entityClassNames = [];
        foreach ($this->entityManager->getMetadataFactory()->getAllMetadata() as $metadata) {
            $entityClassNames[] = $metadata->getName();
        }

        foreach ($entityClassNames as $entityClassName) {
            $this->entityManager->getRepository($entityClassName);
        }
    }

    public function getIdentifier(): string
    {
        // TODO: Implement getIdentifier() method.
    }
}
