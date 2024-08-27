<?php

namespace GudeAPI\API\Interfaces;

use GudeAPI\API\Classes\RequestProperties;
use GudeAPI\API\Enums\RequestTypeNames;
use GudeAPI\HTTPMethod;

interface InterfaceGudeAPI
{
    public function __construct(RequestProperties | null $requestProperties = null);

    // Если не указали RequestProperties в конструкторе, его можно указать здесь (сеттер)
    public function setRequestProperties(RequestProperties $requestProperties): void;

    // Получить RequestProperties (геттер)
    public function getRequestType(): RequestTypeNames | null;

    // Если не указали IdentifierInRequestPath в конструкторе, его можно указать здесь (сеттер)
    public function setIdentifierInRequestPath(string $identifierInRequestPath): void;

    // Получить IdentifierInRequestPath (геттер)
    public function getIdentifierInRequestPath(): string | null;

    // Получить RemoveFileName (геттер)
    public function getRemoveFileName(): bool;

    // Функция для работы в режиме PATH
    public function PATH(HTTPMethod $method, string $postfixInRequestPath, callable | null $action = null): bool;
};
