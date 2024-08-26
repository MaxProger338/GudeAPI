<?php

namespace GudeAPI\API\Exceptions;

class NotCorrectRequestType extends \Exception
{
    public function __construct()
    {
        parent::__construct("Не верный режим RequestType");
    }
}
