<?php

namespace GudeAPI;

enum HTTPMethod: string
{
    case GET         = "GET";
    case POST        = "POST";
    case PUT         = "PUT";
    case DELETE      = "DELETE";
    case ALL_METHODS = "ALL_METHODS";
};
