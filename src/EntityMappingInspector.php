<?php

declare(strict_types=1);

namespace SmartAssert\DoctrineInspectors;

use Doctrine\ORM\EntityManagerInterface;

class EntityMappingInspector
{
    /**
     * @param array<class-string> $entityClassNames
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private array $entityClassNames,
    ) {
    }

    public function __invoke(): void
    {
        foreach ($this->entityClassNames as $entityClassName) {
            $this->entityManager->getRepository($entityClassName);
        }
    }
}
