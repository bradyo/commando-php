<?php
namespace Sample\User;

use Sample\Rest\EntityRepository;
use Sample\Rest\ResourceConfig;

class UserResourceConfig extends ResourceConfig
{
    public function __construct(EntityRepository $repository)
    {
        $uri = 'users';
        $class = '\Sample\User\User';
        $fields = [
            'id',
            'email',
        ];
        $relationClassMap = [];
        parent::__construct($uri, $class, $repository, $fields, $relationClassMap);
    }
}