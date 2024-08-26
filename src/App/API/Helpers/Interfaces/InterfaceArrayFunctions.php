<?php

namespace GudeAPI\API\Helpers\Interfaces;

interface InterfaceArrayFunctions
{
    /*
        Функция удаляет каждый элемент массива, 
        который соответствует условию в переданной пользователем callback-функцией, СОРТИРУЯ ИХ
        Callback-Функция принимает два аргумента: $key, $value

        [ "1" => "a", "2" => "b", "3" => "c" ] -> [ "1" => "a", "3" => "c" ]
        если в callback-функция: return ( $key === "2" ? true : false );

        ИЛИ ЕСЛИ ЭТО НЕ ААСОЦИАТИВНЫЙ МАССИВ:

        [ 0 => "a", 1 => "b", 2 => "c" ] -> [ 0 => "a", 1 => "c" ]
    */
    public static function unsetEveryElements(array $array, callable $condition): array;

    /*
        Функция удаляет каждый элемент массива, 
        который соответствует условию в переданной пользователем callback-функцией, БЕС СОРТИРОВКИ
        Callback-Функция принимает два аргумента: $key, $value

        [ "1" => "a", "2" => "b", "3" => "c" ] -> [ "1" => "a", "3" => "c" ]
        если в callback-функция: return ( $key === "2" ? true : false );

        ИЛИ ЕСЛИ ЭТО НЕ ААСОЦИАТИВНЫЙ МАССИВ:

        [ 0 => "a", 1 => "b", 2 => "c" ] -> [ 0 => "a", 2 => "c" ]
    */
    public static function unsetEveryElementsNotSort(array $array, callable $condition): array;

    /*
        Функция сравнивает два переданных ей массива, и если в первом массиве полностью есть второй массив,
        тогда возвращает true

        arr1 = [ "1" => "a", "2" => "b", "3" => "lol" ]
        arr2 = [ "3" => "lol" ]
        Результат: false

        arr1 = [ "1" => "a", "2" => "b", "3" => "lol" ]
        arr2 = [ "1" => "a", "2" => "b", "3" => "lol" ]
        Результат: true
        
        arr1 = [ "1" => "a", "2" => "b", "3" => "lol" ]
        arr2 = [ "1" => "a", "2" => "b", "3" => "lol", "DISCORD" => "fazber338"]
        Результат: false

        arr1 = [ "1" => "a", "2" => "b", "3" => "lol", "DISCORD" => "fazber338" ]
        arr2 = [ "1" => "a", "2" => "b", "3" => "lol" ]
        Результат: true
    */
    public static function compareArrays(array $array1, array $array2): bool;

    /*
        Функция принимает два массива, и в случае если в первом массиве полностью есть второй массив, 
        тогда она удаляет его из первого массива, в противном случае возвращает null

        arr1 = [ "1" => "a", "2" => "b", "3" => "c" ] 
        arr2 = [ "1" => "a", "2" => "b" ]
        Результат: [ "3" => "c" ]

        arr1 = [ "1" => "a", "2" => "b", "3" => "c" ] 
        arr2 = [ "1" => "a", "2" => "lol" ]
        Результат: null
    */
    public static function unsetExclusiveElements(array $array1, array $array2): array | null;

    /*
        Функция принимает массив, и располагает индексы так, 
        чтоб каждый следующий иднекс был на 1 больше предыдущего

        [ 1 => "a", 13 => "b", 6 => "c" ] -> [ 1 => "a", 2 => "c", 3 => "b" ]
    */
    public static function decorateIndexesArray(array $array): array;
};
