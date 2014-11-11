<?php
namespace Commando\Validator;

use InvalidArgumentException;

class MinLengthValidator
{
    private $minLength;

    /**
     * @param int $minLength
     */
    public function __construct($minLength)
    {
        if (! is_int($minLength)) {
            throw new InvalidArgumentException();
        }
        if ($minLength < 0) {
            throw new InvalidArgumentException();
        }
        $this->minLength = $minLength;
    }

    /**
     * @param string $value
     * @return boolean true if value is valid
     */
    public function isValid($value)
    {
        return strlen($value) >= $this->minLength;
    }
}