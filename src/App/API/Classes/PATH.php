<?php

declare(strict_types=1);

namespace GudeAPI\API\Classes;

use GudeAPI\API\Interfaces\InterfacePATH;
use GudeAPI\HTTPMethod;
use GudeAPI\API\Validate\Classes\ValidateRequestPath;
use GudeAPIConfigs\RequestConfig;
use GudeAPI\API\Helpers\Classes\ArrayFunctions;

// Exception'ы
use GudeAPI\API\Exceptions\InvalidProperty;
use GudeAPI\API\Exceptions\SpecialParameterInIdentifier;

class PATH implements InterfacePATH
{
    private HTTPMethod | null $_method                        = null; 

    private string     | null $_identifier                    = null;

    private string     | null $_postfix                       = null;

    // Массив в котором будем хранить индекс и значение динамических параметров
    // ( "его имя" => "его значение")
    private array $_dynamicElements                           = [];
    
    // Массив в котором будем хранить специальные параметры
    // ( "его индекс" => "{:его имя:}" )
    private array $_specialElements                           = [];

    // Массив в котором будем хранить распарсенные специальные параметры
    // ( "его индекс" => "{:его имя:}" )
    private array $_elementsAfterSpecialElement               = [];

    // Массив в котором будем хранить динамические параметры ИДЕНТИФИКАТОРА
    // ( "его индекс" => "{:его имя:}" )
    private array $_deletedDynamicElementsFromIdentifier      = [];

    // Массив в котором будем хранить индекс и значение динамических параметов в ИДЕНТИФИКАТОРЕ ЗАПРОСЕ
    // ( "его индекс в идентификаторе запроса" => "его значение" )
    private array $_deletedDynamicElementsFromQueryIdentifier = [];

    // Массив в котором будем хранить динамические параметры ПОСТФИКСА
    // ( "его индекс" => "{:его имя:}" )
    private array $_deletedDynamicElementsFromPostfix         = [];

    // Массив в котором будем хранить индекс и значение динамического параметра ПОСТФИКСА ЗАПРОСА
    // ( "его индекс в постфиксе запроса" => "его значение" )
    private array $_deletedDynamicElementsFromQueryPostfix    = [];

    public function __construct(
        HTTPMethod | null $method     = null, 
        string     | null $identifier = null, 
        string     | null $postfix    = null
    ) {
        $this->_method = $method;
        $this->_identifier = $identifier;
        $this->_postfix = $postfix;
    }

    public function setHTTPMethod(HTTPMethod $method): void
    {
        $this->_method = $method;
    }

    public function getHTTPMethod(): HTTPMethod | null
    {
        return $this->_method;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->_identifier = $identifier;
    }

    public function getIdentifier(): string | null
    {
        return $this->_identifier;
    }

    public function setPostfix(string $postfix): void
    {
        $this->_postfix = $postfix;
    }

    public function getPostfix(): string | null
    {
        return $this->_postfix;
    }

    public function getDynamicElements(): array
    {
        return $this->_dynamicElements;
    }

    public function getSpecialElements(): array
    {
        return $this->_specialElements;
    }

    public function getElementsAfterSpecialElement(): array
    {
        return $this->_elementsAfterSpecialElement;
    }

    // Функция, которая возвращает все динамические параметры из переданного ей массива
    private function _getDynamicElements(array $array, bool $trimSpecialSymbols = true, bool $decorateIndexes = true): array
    {
        $params = [];
        foreach ($array as $key => $value)
        {
            if ( strlen($value) > 4 ) 
            {
                if ( $value[0] === "{" 
                    && $value[1] === ":"  
                    && $value[-2] === ":"  
                    && $value[-1] === "}"
                ) {
                    $valueRes = $value;
                    if ( $trimSpecialSymbols ) 
                    {
                        $valueRes = substr_replace($valueRes, '', 0, 1);
                        $valueRes = substr_replace($valueRes, '', 0, 1);
                        $valueRes = substr_replace($valueRes, '', strlen($valueRes) - 1, 1);
                        $valueRes = substr_replace($valueRes, '', strlen($valueRes) - 1, 1);
                    }

                    $params[$key] = $valueRes;
                }
            }
            if ( $decorateIndexes ) 
                $params = ArrayFunctions::decorateIndexesArray($params);
        }
        return $params;
    }
    
    // Функция, которая возвращает все специальные параметры из переданного ей массива
    private function _getSpecialElements(array $array, bool $trimSpecialSymbols = true, bool $decorateIndexes = true): array
    {
        $params = [];
        foreach ($array as $key => $value)
        {
            if ( strlen($value) === 5 ) 
            {
                if ( $value[0] === "{" 
                    && $value[1] === "|"  
                    && $value[2] === "*"  
                    && $value[3] === "|"  
                    && $value[4] === "}"
                ) {
                    $valueRes = $value;
                    if ( $trimSpecialSymbols ) 
                    {
                        $valueRes = substr_replace($valueRes, '', 0, 1);
                        $valueRes = substr_replace($valueRes, '', 0, 1);
                        $valueRes = substr_replace($valueRes, '', strlen($valueRes) - 1, 1);
                        $valueRes = substr_replace($valueRes, '', strlen($valueRes) - 1, 1);
                    }

                    $params[$key] = $valueRes;
                }
            }

            if ( $decorateIndexes ) 
                $params = ArrayFunctions::decorateIndexesArray($params);
        }
        return $params;
    }

    // Функция проверяет правильно идентификатора
    private function _isCorrectIdentifier(array $splitQuery, array $splitIdentifier, $splitPostfix): bool
    {
        if ( !empty($this->_getSpecialElements($splitIdentifier)) ) 
            throw new SpecialParameterInIdentifier();

        // Если длинна запроса меньше чем у иденфикатора, выходим и дальше не идём
        // ( ["posts", "lang"] < ["posts", "lang", "PHP"] )
        if ( count($splitQuery) < count($splitIdentifier) ) 
            return false;

        // Перебираем наш иденфикатор
        foreach ($splitIdentifier as $index => $value)
        {
            if ( !empty($this->_getDynamicElements([$value])) ) 
            {
                // TODO: JUST THINK!!!
                if ( array_count_values($this->_getDynamicElements($splitIdentifier, false))[$this->_getDynamicElements([$value], false, false)[0]] > 1 ) 
                    return false;
                
                if ( in_array($this->_getDynamicElements([$value], false, false)[0], $splitPostfix) ) 
                {
                    if ( array_count_values($this->_getDynamicElements($splitPostfix, false))[$this->_getDynamicElements([$value], false, false)[0]] > 1 ) 
                        return false;
                }

                // Создаём копию
                $trimElement = $value;
                // Обрезаем её
                $trimElement = substr_replace($trimElement, '', 0, 1);
                $trimElement = substr_replace($trimElement, '', 0, 1);
                $trimElement = substr_replace($trimElement, '', strlen($trimElement) - 1, 1);
                $trimElement = substr_replace($trimElement, '', strlen($trimElement) - 1, 1);

                // Добавляем в массив динамический параметров наш динамический параметр
                $this->_dynamicElements[$trimElement]                     = $splitQuery[$index];
                // Добавляем в массив удаляемых елементов ИДЕНТИФИКАТОРА,
                // чтобы когда будем работать с постфиксом удалить их из иденфикатора
                $this->_deletedDynamicElementsFromIdentifier[$index]      = $value;
                // Добавляем в массив удаляемых елементов ЗАПРОСА,
                // чтобы когда будем работать с постфиксом удалить их из запроса
                $this->_deletedDynamicElementsFromQueryIdentifier[$index] = $splitQuery[$index];
            }
            else
            {
                // Если всё же параметр идентификатора статичен,
                // то сравниваем его с тем же элементом по индексу у запроса
                // Если он не равен ему, то выходим и не идём дальше
                if ( $value !== $splitQuery[$index]) 
                    return false;
            }
        }

        return true;
    }

    // Функция проверяет правильно постфикса
    private function _isCorrectPostfix(array $splitQueryPostfix, array $splitPostfix, array $splitIdentifier): bool
    {
        // Если длинна постфикса запроса меньше чем у постфикса, выходим и дальше не идём
        // ( ["posts", "lang"] < ["posts", "lang", "PHP"] )
        if ( count($splitQueryPostfix) < count($splitPostfix) ) 
        {
            if ( empty($this->_getSpecialElements($splitPostfix)) ) 
                return false;
        }

        // Если длинна постфикса запроса больше чем чем у постфикса,
        // нет параметра {|*|} и идентификатор и постфикс не пустые,
        // выходим и дальше не идём (возможно это надо регулировать)
        if ( count($splitQueryPostfix) > count($splitPostfix) ) 
        {
            if ( empty($this->_getSpecialElements($splitPostfix)) ) 
            {
                if ( !empty($splitIdentifier) && !empty($splitPostfix) ) 
                    return false;
            }
        }

        $specialElementIndex = null;
        foreach ($splitPostfix as $index => $value)
        {
            // Если это специальный параметр
            if ( !empty($this->_getSpecialElements([$value])) ) 
            {
                $specialElementIndex = $index;
                $this->_specialElements[$index] = $value;
                break;
            }

            // Если параметр динамичен
            else if ( !empty($this->_getDynamicElements([$value])) ) 
            {
                if ( !isset($splitQueryPostfix[$index]) ) 
                    return false;

                // Создаём копию
                $trimElement = $value;
                // Обрезаем её
                $trimElement = substr_replace($trimElement, '', 0, 1);
                $trimElement = substr_replace($trimElement, '', 0, 1);
                $trimElement = substr_replace($trimElement, '', strlen($trimElement) - 1, 1);
                $trimElement = substr_replace($trimElement, '', strlen($trimElement) - 1, 1);
                // Добавляем в массив динамический параметров наш динамический параметр
                $this->_dynamicElements[$trimElement] = $splitQueryPostfix[$index];
                // Добавляем в массив удаляемых елементов ПОСТФИКСА,
                $this->_deletedDynamicElementsFromPostfix[$index] = $value;
                // Добавляем в массив удаляемых елементов ПОСТФИКСА ЗАПРОСА,
                $this->_deletedDynamicElementsFromQueryPostfix[$index] = $splitQueryPostfix[$index];
            }
            else
            {
                // Если всё же параметр постфикса статичен,
                // то сравниваем его с тем же элементом по индексу у постфикса запроса
                // Если он не равен ему, то выходим и не идём дальше
                if ( !isset($splitQueryPostfix[$index]) ) 
                    return false;

                if ( $value !== $splitQueryPostfix[$index] ) 
                    return false;
            }
        }

        foreach ( $splitQueryPostfix as $index => $value )
        {
            if ( $index === $specialElementIndex || $index > $specialElementIndex ) 
                $this->_elementsAfterSpecialElement[] = $value;
        }

        return true;
    }
  
    // Функция проверяет правильно запроса
    public function isCorrectPATH(): bool
    {
        $this->_dynamicElements             = [];
        $this->_specialElements             = [];
        $this->_elementsAfterSpecialElement = [];

        if ( $this->_method === null ) 
            throw new InvalidProperty("HTTPMethod");

        if ( $this->_identifier === null ) 
            throw new InvalidProperty("Identifier");

        if ( $this->_postfix === null ) 
            throw new InvalidProperty("Postfix");

        $splitQuery = ValidateRequestPath::removeEmptyElements(explode("/", $_GET[RequestConfig::VARIABLE_QUERY_NAME()]));
        unset($splitQuery[0]);
        $splitQuery = ArrayFunctions::decorateIndexesArray($splitQuery);
        
        $splitIdentifier = ValidateRequestPath::removeEmptyElements(explode("/", $this->_identifier));
        $splitPostfix    = ValidateRequestPath::removeEmptyElements(explode("/", $this->_postfix));

        // =====================================================================
        // Переменная хранящая статус возвратимого состояния
        // (если все пути совпадают, то помещаем и возвразаем true, иначе false)
        $returnedStatus = false;

        if ( ($this->_method === HTTPMethod::ALL_METHODS) ? true : ($this->_method->value === $_SERVER["REQUEST_METHOD"]) ) 
        {
            if ( $this->_isCorrectIdentifier($splitQuery, $splitIdentifier, $splitPostfix) ) 
            {
                // ============= ДИНАМИЧЕСКИЕ ПАРАМЕТРЫ =============
                // Сначало решаем дела с динамическими параметрами ЗАПРОСА и ИДЕНТИФИКАТОРА

                // Удаляем динамические параметры из запроса и идентификатора (Если они конечно же есть)
                // и помещаем их в свои buf-массивы
                $splitQuery = ArrayFunctions::unsetEveryElements(
                    $splitQuery,
                    function ($key, $value) {
                        return in_array($key, array_keys($this->_deletedDynamicElementsFromQueryIdentifier));
                    }
                );

                $splitIdentifier = ArrayFunctions::unsetEveryElementsNotSort(
                    $splitIdentifier,
                    function ($key, $value) {
                        return in_array($key, array_keys($this->_deletedDynamicElementsFromIdentifier));
                    }
                );

                // ============= СТАТИЧНЫЕ ПАРАМЕТРЫ =============
                // Сдесь уже решаем дела с оставшимися параметрами ЗАПРОСА И ИДЕНТИФИКАТОРА

                // Если в нушем пути есть идентификатор
                if ( ArrayFunctions::compareArrays($splitQuery, $splitIdentifier) ) 
                {
                    // Вырезаем из нашего пути идентификатор ( "posts/langs/PHP" -> "PHP", где posts/langs - идентификатор, а PHP - постфикс )
                    $splitQueryPostfix = ArrayFunctions::unsetExclusiveElements($splitQuery, $splitIdentifier);
                    if ( $splitQueryPostfix !== null ) 
                    {
                        // Выравниваем индексы ( ["3" => "a", "5" => "b", "27" => "c"] -> ["1" => "a", "2" => "b", "3" => "c"] )
                        $splitQueryPostfix = ArrayFunctions::decorateIndexesArray($splitQueryPostfix);

                        // Разделяем наш постфикс
                        $splitPostfix = ValidateRequestPath::removeEmptyElements(explode("/", $this->_postfix));

                        // ============= ДИНАМИЧЕСКИЕ ПАРАМЕТРЫ =============
                        // Сдесь решаем дела с динамическими параметрами ПОСТФИКСА И ПОСТФИКСА ЗАПРОСА
                        
                        // Если всё ОК
                        if ( $this->_isCorrectPostfix($splitQueryPostfix, $splitPostfix, $splitIdentifier) ) 
                        {
                            // Удаляем динамические параметры из постфикса и постфикса запроса (Если они конечно же есть)
                            // и помещаем их в свои buf-массивы
                            $splitQueryPostfix = ArrayFunctions::unsetEveryElements(
                                $splitQueryPostfix,
                                function ($key, $value) {
                                    return in_array($key, array_keys($this->_deletedDynamicElementsFromQueryPostfix));
                                }
                            );

                            $splitPostfix = ArrayFunctions::unsetEveryElementsNotSort(
                                $splitPostfix,
                                function ($key, $value) {
                                    return in_array($key, array_keys($this->_deletedDynamicElementsFromPostfix));
                                }
                            );

                            // Если есть специальный параметр, тогда удаляем его в всё что идёт после его
                            $isSpecialParam = false;
                            $splitPostfix = ArrayFunctions::unsetEveryElementsNotSort(
                                $splitPostfix,
                                function ($key, $value) {
                                    global $isSpecialParam;

                                    if ( !empty($this->_getSpecialElements([$value])) ) 
                                        $isSpecialParam = true;

                                    return $isSpecialParam;
                                }
                            );

                            // ============= ФИНАЛ =============
                            // Если в нашем обрезанном пути есть постфикс
                            if ( ArrayFunctions::compareArrays($splitQueryPostfix, $splitPostfix) ) 
                            {
                                // Если всё совпадает, ставив в true
                                $returnedStatus = true;
                            }
                        }
                    }
                }
            }
        }
        // Очищаем массивы чтобы в будушем не возникало проблем
        $this->_deletedDynamicElementsFromIdentifier      = [];
        $this->_deletedDynamicElementsFromQueryIdentifier = [];
        $this->_deletedDynamicElementsFromPostfix         = [];
        $this->_deletedDynamicElementsFromQueryPostfix    = [];

        // Возвращаем статус ( true / false )
        return $returnedStatus;
    }
};
