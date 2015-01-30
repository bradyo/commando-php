<?php
namespace SampleApi\Rest;

interface EntityRepository
{
    public function find($id);

    public function findAll();

    public function count();

    public function save($entity);

    public function remove($id);
}