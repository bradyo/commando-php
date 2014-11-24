<?php
namespace Sample\Note;

use Sample\Rest\ResourceSpec;
use Sample\Rest\RestModule;

class NoteModule extends RestModule
{
    public function __construct()
    {
        $spec = new ResourceSpec(
            'note',
            ['id', 'authorId', 'content'],
            ['author']
        );
        parent::__construct($spec);
    }
}