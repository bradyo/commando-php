<?php
namespace SampleApi\Rest;

class ResourceConfig
{
    private $path;
    private $class;
    private $fields;
    private $relationClassMap;
    private $repository;

    public function __construct(
        $path,
        $class,
        EntityRepository $repository,
        array $fields,
        array $relationClassMap = []
    ) {
        $this->path = $path;
        $this->class = $class;
        $this->fields = $fields;
        $this->relationClassMap = $relationClassMap;
        $this->repository = $repository;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getRelations()
    {
        return array_keys($this->relationClassMap);
    }

    public function getRelationClass($relation)
    {
        return $this->relationClassMap[$relation];
    }

    public function getRepository()
    {
        return $this->repository;
    }
}