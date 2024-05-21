<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;

class EntityFieldHelper
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getEntityFields(string $entityClass): array
    {
        $metadata = $this->entityManager->getClassMetadata($entityClass);
        return $metadata->getFieldNames();
    }
}
