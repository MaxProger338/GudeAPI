<?php

namespace GudeAPI\API\Interfaces;

use GudeAPI\HTTPMethod;

interface InterfacePATH
{
    public function __construct(
        HTTPMethod | null $method     = null, 
        string     | null $identifier = null, 
        string     | null $postfix    = null
    );

    // Если не указали HTTPMethod в конструкторе, его можно указать здесь (сеттер)
    public function setHTTPMethod(HTTPMethod $method): void;

    // Получить HTTPMethod (геттер)
    public function getHTTPMethod(): HTTPMethod | null;

    // Если не указали Identifier в конструкторе, его можно указать здесь (сеттер)
    public function setIdentifier(string $identifier): void;

    // Получить Identifier (геттер)
    public function getIdentifier(): string | null;

    // Если не указали Postfix в конструкторе, его можно указать здесь (сеттер)

    public function setPostfix(string $postfix): void;

    // Получить Postfix (геттер)
    public function getPostfix(): string | null;

    // Получить RemoveFileName (геттер)
    public function getRemoveFileName(): bool;

    // Получить массив со всеми динамическими элементами (геттер)
    public function getDynamicElements(): array;

    // Получить массив со всеми специальными элементами (геттер)
    public function getSpecialElements(): array;

    // Получить массив со всеми распарсенными специальными элементами (геттер)
    public function getElementsAfterSpecialElement(): array;

    // Функция для проверки на правильность идентификатора и постфикма по отношению к путю в запросе
    public function isCorrectPATH(): bool;
};
