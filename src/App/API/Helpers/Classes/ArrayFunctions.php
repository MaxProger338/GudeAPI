<?php

declare(strict_types=1);

namespace GudeAPI\API\Helpers\Classes;

use GudeAPI\API\Helpers\Interfaces\InterfaceArrayFunctions;

class ArrayFunctions implements InterfaceArrayFunctions
{
    public static function unsetEveryElements(array $array, callable $condition): array
    {
        $returnedArray = [];
        foreach ($array as $key => $value) 
        {
            $returnedArray[$key] = $value;
        }

        foreach ($array as $key => $value)
        {
            if ( $condition($key, $value) ) 
                unset($returnedArray[$key]);
        }
        return $returnedArray;
    }

    public static function unsetEveryElementsNotSort(array $array, callable $condition): array
    {
        $arrayDeletedIndexes = [];
        foreach ($array as $key => $value)
        {
            if ( $condition($key, $value) ) 
                $arrayDeletedIndexes[] = $key;
        }

        $returnedArray = [];
        foreach ($array as $key => $value)
        {
            if ( !in_array($key, array_values($arrayDeletedIndexes)) )
                $returnedArray[$key] = $value;
        }

        return $returnedArray;
    }

    public static function compareArrays(array $array1, array $array2): bool
    {
        if ( count($array1) < count($array2) ) 
            return false;
            
        foreach ($array2 as $key => $value) 
        {
            if ( !isset($array1[$key]) ) 
                return false;
            
            if ( $value !== $array1[$key] ) 
                return false;
        }
    
        return true;
    }

    public static function unsetExclusiveElements(array $array1, array $array2): array | null
    {
        if ( count($array1) < count($array2) ) 
            return null;

        foreach ($array2 as $key => $value)
        {
            if ( !isset($array1[$key]) ) 
                return null;
            
            unset($array1[$key]);
        }

        return $array1;
    }

    public static function decorateIndexesArray(array $array): array
    {
        $returnedArray = [];
        $counter = 0;
        foreach ($array as $value)
        {
            $returnedArray[$counter++] = $value;
        }
        return $returnedArray;
    }
};
