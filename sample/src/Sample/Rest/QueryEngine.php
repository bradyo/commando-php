<?php
namespace Sample\Rest;

class QueryEngine
{
    private $repositoryMap;

    public function __construct(array $repositoryMap)
    {
        $this->repositoryMap = $repositoryMap;
    }

    public function fetch($entityName, $id, $expandedRelations)
    {
        $entity = $this->getRepository($entityName)->find($id);
        if ($entity === null) {
            return null;
        }

    }

    public function fetchList($resourceName, $criteria, $expandedRelations, $limit, $offset = 0)
    {
    }

    private function getRepository($resourceName)
    {
        return $this->repositoryMap[$resourceName];
    }
}
