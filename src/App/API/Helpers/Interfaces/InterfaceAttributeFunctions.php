<?php

namespace GudeAPI\API\Helpers\Interfaces;

interface InterfaceAttributeFunctions
{
    // Функция принимает два параметра: переменную $value и массив $types
    // Если тип данных переменной $value есть в массиве $types,
    // функция возвращает true, в противном случае false
    public static function checkType(mixed $value, array $types): bool;
};
