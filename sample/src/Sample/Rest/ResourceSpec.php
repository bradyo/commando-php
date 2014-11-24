<?php
namespace Sample\Rest;

class ResourceSpec
{
    private $path;
    private $fields;
    private $relations;

    public function __construct($path, $fields, $relations)
    {
        $this->path = $path;
        $this->fields = $fields;
        $this->relations = $relations;
    }

    public function getPath()
    {
        return $this->path;
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