<?php

namespace GudeAPI\API\Exceptions;

class NotFoundKeyInArray extends \Exception
{
    public function __construct(string $key)
    {
        parent::__construct("Не найден ключ $key");
    }
}
