<?php
namespace Sample\Rest;

use PDO;

class ResourceRepository
{
    private $pdo;
    private $entityRepositoryMap;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->entityRepositoryMap = [];
    }

    public function createQuery($resourceName, $expandRelations)
    {
        return new ResourceQuery($this, $resourceName, $expandRelations);
    }

    public function createListQuery($resourceName, array $criteria, $expandRelations)
    {
        return new ResourceListQuery($this, $resourceName, $expandRelations);
    }
}