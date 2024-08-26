<?php

namespace GudeAPI\API\Exceptions;

class SpecialParameterInIdentifier extends \Exception
{
    public function __construct()
    {
        parent::__construct("Нельзя писать специальный параметр в идентификатор API");
    }
}
