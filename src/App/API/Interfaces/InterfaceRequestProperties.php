<?php

namespace GudeAPI\API\Interfaces;

use GudeAPI\API\Enums\RequestTypeNames;

interface InterfaceRequestProperties
{
    public function __construct(array $properties);

    // Получить IdentifierInRequestPath (геттер)
    public function getIdentifierInRequestPath(): string | null;

    // Получить RequestType (геттер)
    public function getRequestType(): RequestTypeNames;
};
