<?php
namespace SampleApi\Rest;

class ResourceRepository
{
    /**
     * @var ResourceConfig[]
     */
    private $configMap = [];

    private $itemsPerPage = 10;

    /**
     * @param ResourceConfig[] $configs
     */
    public function __construct(array $configs)
    {
        foreach ($configs as $config) {
            $class = $config->getClass();
            $this->configMap[$class] = $config;
        }
    }

    public function find($class, $id, $expandRelations = [])
    {
        $config = $this->configMap[$class];
        $uri = $config->getPath() . '/' . $id;

        $entity = $config->getRepository()->find($id);
        if ($entity === null) {
            return null;
        }
        $data = $this->extractData($entity, $config, $expandRelations);
        $links = [
            $this->createLink('self', $uri)
        ];

        return new Resource($uri, $data, $links);
    }

    public function findAll($class, $expandRelations = [], $offset = 0)
    {
        $config = $this->configMap[$class];
        $uri = $config->getPath();

        $entities = $config->getRepository()->findAll();
        $itemsData = [];
        foreach ($entities as $entity) {
            $itemsData[] = $this->extractData($entity, $config, $expandRelations);
        }
        $data = [
            'total' => count($entities),
            'items' => $itemsData
        ];
        $links = [
            $this->createLink('self', $uri),
            $this->createLink('first', $uri . '?offset=0'),
            $this->createLink('next', $uri . '?offset=' . ($offset + $this->itemsPerPage)),
        ];

        return new Resource($uri, $data, $links);
    }

    public function save($class, $entity)
    {
        $config = $this->configMap[$class];
        $config->getRepository()->save($entity);
    }

    public function delete($class, $id)
    {
        $config = $this->configMap[$class];
        $config->getRepository()->remove($id);
    }

    private function extractData($entity, ResourceConfig $config, $expandRelations = [])
    {
        $data = [];
        foreach ($config->getFields() as $field) {
            $getter = 'get' . ucfirst($field);
            $data[$field] = $entity->$getter();
        }
        foreach ($config->getRelations() as $relation) {
            if (in_array($relation, $expandRelations)) {
                $data[$relation] = $this->extractRelationData($entity, $config, $relation);
            }
        }
        return $data;
    }

    private function extractRelationData($entity, ResourceConfig $config, $relation)
    {
        $relationClass = $config->getRelationClass($relation);
        $relationConfig = $this->configMap[$relationClass];

        $relationIdGetter = 'get' . ucfirst($relation) . 'Id';
        $relationId = $entity->$relationIdGetter();
        $relationEntity = $relationConfig->getRepository()->find($relationId);

        return $this->extractData($relationEntity, $relationConfig);
    }

    private function createLink($rel, $uri)
    {
        return [
            'rel' => $rel,
            'uri' => $uri,
        ];
    }
}