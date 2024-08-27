<?php

declare(strict_types=1);

namespace GudeAPI;

use GudeAPI\API\Interfaces\InterfaceGudeAPI;
use GudeAPI\API\Classes\RequestProperties;
use GudeAPI\API\Enums\RequestTypeNames;
use GudeAPI\API\Classes\PATH;
use GudeAPI\HTTPMethod;

// Exception'ы
use GudeAPI\API\Exceptions\InvalidProperty;
use GudeAPI\API\Exceptions\NotCorrectRequestType;

class GudeAPI implements InterfaceGudeAPI
{
    // Здесь будет храниться объект RequestProperties, в котором лежат начальные параметры конфигурации API 
    private RequestProperties | null $_requestProperties       = null;
    private RequestTypeNames  | null $_requestType             = null;
    private string            | null $_identifierInRequestPath = null;

    public function __construct(RequestProperties | null $requestProperties = null)
    {
        if ( $requestProperties === null ) 
            return;
        
        $this->setRequestProperties($requestProperties);
    }

    public function setRequestProperties(RequestProperties $requestProperties): void
    {
        $this->_requestProperties = $requestProperties;

        switch ($requestProperties->getRequestType())
        {
            case RequestTypeNames::PATH:
                $this->_requestType             = $requestProperties->getRequestType();
                $this->_identifierInRequestPath = $requestProperties->getIdentifierInRequestPath();
                break;

            default:
        }
    }

    public function getRequestType(): RequestTypeNames | null
    {
        return $this->_requestType;
    }

    public function setIdentifierInRequestPath(string $identifierInRequestPath): void
    {
        if ( $this->_requestType === null ) 
            throw new InvalidProperty("RequestType");

        if ( $this->_requestType !== RequestTypeNames::PATH ) 
            throw new NotCorrectRequestType();

        $this->_identifierInRequestPath = $identifierInRequestPath;
    }

    public function getIdentifierInRequestPath(): string | null
    {
        if ( $this->_requestType === null ) 
            throw new InvalidProperty("RequestType");

        if ( $this->_requestType !== RequestTypeNames::PATH ) 
            throw new NotCorrectRequestType();

        return $this->_identifierInRequestPath;
    }

    public function PATH(HTTPMethod $method, string $postfixInRequestPath, callable | null $action = null): bool
    {
        if ( $this->_requestType === null ) 
            throw new InvalidProperty("RequestType");

        if ( $this->_identifierInRequestPath === null ) 
            throw new InvalidProperty("IdentifierInRequestPath");

        $returnedStatus = false;

        // Создаём и Инициализируем объект для работы с API в режиме PATH, передав в него параметры для его работы 
        $path = new PATH($method, $this->_identifierInRequestPath, $postfixInRequestPath);
        // Если пути в запросе совпадают с нашими IdentifierInRequestPath и PostfixInRequestPath, тогда идём дальше и радуемся :) 
        if ( $path->isCorrectPATH() ) 
        {
            $returnedStatus = true;
            
            if ( $action !== null ) 
            {
                // Вызываем callback-функцию пользователя и Передаём в неё массив с некоторыми параметрами для пользователя
                $action(
                    [
                        "HTTPMethod"                  => $path->getHTTPMethod()->name,
                        "IdentifierInRequestPath"     => $path->getIdentifier(),
                        "PostfixInRequestPath"        => $path->getPostfix(),
                        "DynamicElements"             => $path->getDynamicElements(),
                        "SpecialElements"             => $path->getSpecialElements(),
                        "ElementsAfterSpecialElement" => $path->getElementsAfterSpecialElement(),
                    ]
                );
            }
        }

        return $returnedStatus;
    }
};
