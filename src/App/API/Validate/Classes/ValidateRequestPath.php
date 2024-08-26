<?php

declare(strict_types=1);

namespace GudeAPI\API\Validate\Classes;

use GudeAPI\API\Validate\Interfaces\InterfaceValidateRequestPath;
use GudeAPI\API\Helpers\Classes\ArrayFunctions;

class ValidateRequestPath implements InterfaceValidateRequestPath
{
    public static function removeEmptyElements(array $path): array
    {    
        // Удаляем пустые элементы ( "/PHP/func"/ -> ["", "PHP", "func", ""] => ["PHP", "func"] )
        $validatePath = ArrayFunctions::unsetEveryElements(
            $path,
            function ($key, $value) {
                return !$value;
            }
        );
        // Выравниваем индексы
        $validatePath = ArrayFunctions::decorateIndexesArray($validatePath);

        return $validatePath;
    }
};
