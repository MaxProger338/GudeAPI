<?php

declare(strict_types=1);

namespace GudeAPI\API\Helpers\Classes;

use GudeAPI\API\Helpers\Interfaces\InterfaceAttributeFunctions;

class AttributeFunctions implements InterfaceAttributeFunctions
{
    public static function checkType(mixed $value, array $types): bool
    {
        if (in_array(gettype($value), $types) ) { 
            return true;
        }

        return false;
    }
};
