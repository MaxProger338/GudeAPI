<?php

namespace GudeAPI\API\Validate\Interfaces;

interface InterfaceValidateRequestPath
{
    // Функция для удаление пустых элементов в массиве
    public static function removeEmptyElements(array $path): array;
};
