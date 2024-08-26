<?php

namespace GudeAPI\API\Exceptions;

class InvalidProperty extends \Exception
{
    public function __construct(string $property)
    {
        parent::__construct("$property пути не указан");
    }
}
