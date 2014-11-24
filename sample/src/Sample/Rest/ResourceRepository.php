<?php
namespace Sample\Rest;

interface ResourceRepository
{
    /**
     * @param string $id
     * @return Resource
     */
    public function find($id);

    /**
     * @return Resource[]
     */
    public function findAll();

    /**
     * @return int
     */
    public function count();

    /**
     * @param Resource $resource
     * @return mixed
     */
    public function save(Resource $resource);

    /**
     * @param string $id
     */
    public function remove($id);
}