<?php
namespace SampleApi\Note;

use SampleApi\Rest\EntityRepository;
use SampleApi\Rest\ResourceConfig;

class NoteResourceConfig extends ResourceConfig
{
    public function __construct(EntityRepository $repository)
    {
        $uri = 'notes';
        $class = '\Sample\Note\Note';
        $fields = [
            'id',
            'authorId',
            'content'
        ];
        $relationClassMap = [
            'author' => '\Sample\User\User'
        ];
        parent::__construct($uri, $class, $repository, $fields, $relationClassMap);
    }
}