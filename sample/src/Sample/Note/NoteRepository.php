<?php
namespace Sample\Note;

use Sample\Rest\EntityRepository;

class NoteRepository implements EntityRepository
{
    private $notesById;

    public function __construct()
    {
        $this->notesById = [
            1 => new Note(1, 1, 'hello there'),
            2 => new Note(2, 1, 'hello again'),
            3 => new Note(3, 2, 'oh hai')
        ];
    }

    public function find($id)
    {
        if (isset($this->notesById[$id])) {
            return $this->notesById[$id];
        } else {
            return null;
        }
    }

    public function findAll()
    {
        return array_values($this->notesById);
    }

    public function count()
    {
        return count($this->notesById);
    }

    /**
     * @param Note $note
     * @return Note
     */
    public function save($note)
    {
        if ($note->getId() === null) {
            $nextId = max(array_keys($this->notesById)) + 1;
            $newNote = new Note($nextId, $note->getAuthorId(), $note->getContent());
            $this->notesById[$note->getId()] = $newNote;
            return $newNote;
        } else {
            $updateNote = new Note($note->getId(), $note->getAuthorId(), $note->getContent());
            $this->notesById[$note->getId()] = $updateNote;
            return $updateNote;
        }
    }

    public function remove($id)
    {
        unset($this->notesById[$id]);
    }
}