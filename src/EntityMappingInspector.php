<?php

declare(strict_types=1);

namespace SmartAssert\DoctrineInspectors;

use Doctrine\ORM\EntityManagerInterface;

class EntityMappingInspector
{
    public function __construct(
        private EntityManagerInterface $entityManager,
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
}
