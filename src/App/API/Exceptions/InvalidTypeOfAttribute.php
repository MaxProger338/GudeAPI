<?php

namespace GudeAPI\API\Exceptions;

class InvalidTypeOfAttribute extends \Exception
{
    public function __construct(string $attribute)
    {
        parent::__construct("Не верный тип у $attribute");
    }
}
