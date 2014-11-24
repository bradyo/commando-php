<?php
namespace Sample\Note;

use Sample\Rest\Resource;

class Note extends Resource
{
    private $id;

    private $authorId;

    private $content;

    public function __construct($id, $authorId, $content)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->content = $content;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function getContent()
    {
        return $this->content;
    }
}