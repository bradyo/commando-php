<?php
namespace Sample\Rest;

class ResourceConfig
{
    private $path;
    private $className;
    private $fields;
    private $relations;

    public function __construct($path, $className, $fields, $relations)
    {
        $this->path = $path;
        $this->className = $className;
        $this->fields = $fields;
        $this->relations = $relations;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getRelations()
    {
        return $this->relations;
    }
}