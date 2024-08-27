<?php

declare(strict_types=1);

namespace GudeAPI\API\Classes;

use GudeAPI\API\Interfaces\InterfaceRequestProperties;
use GudeAPI\API\Enums\RequestTypeNames;
use GudeAPI\API\Helpers\Classes\AttributeFunctions;

// Exception'ы
use GudeAPI\API\Exceptions\NotFoundKeyInArray;
use GudeAPI\API\Exceptions\InvalidTypeOfAttribute;

class RequestProperties implements InterfaceRequestProperties
{
    private RequestTypeNames | null $_requestType             = null;
    private string           | null $_identifierInRequestPath = null;

    public function __construct(array $properties)
    {
        if ( !key_exists("IdentifierInRequestPath", $properties) ) 
            throw new NotFoundKeyInArray("IdentifierInRequestPath");
        
        if ( !AttributeFunctions::checkType($properties["IdentifierInRequestPath"], ["string", "NULL"]) ) 
            throw new InvalidTypeOfAttribute("IdentifierInRequestPath");
        
        if ( !key_exists("RequestType", $properties) ) 
            throw new NotFoundKeyInArray("RequestType");
    
        if ( !($properties["RequestType"] instanceof RequestTypeNames) ) 
            throw new InvalidTypeOfAttribute("RequestType");
    
        $this->_identifierInRequestPath = $properties["IdentifierInRequestPath"];
        $this->_requestType             = $properties["RequestType"];
    }

    public function getIdentifierInRequestPath(): string | null
    {
        return $this->_identifierInRequestPath;
    }

    public function getRequestType(): RequestTypeNames
    {
        return $this->_requestType;
    }
};
