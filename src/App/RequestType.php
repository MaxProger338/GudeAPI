<?php

declare(strict_types=1);

namespace GudeAPI;

use GudeAPI\API\Classes\RequestProperties;
use GudeAPI\API\Enums\RequestTypeNames;

enum RequestType
{
    public static function PATH(string | null $identifierInRequestPath = null, bool $removeFileName = true): RequestProperties
    {
        return new RequestProperties(
            [
                "RequestType"             => RequestTypeNames::PATH,
                "IdentifierInRequestPath" => $identifierInRequestPath,
                "RemoveFileName"          => $removeFileName,
            ]
        );
    }
};
