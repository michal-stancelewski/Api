<?php

declare(strict_types=1);

namespace App\NameInterface;

interface ExceptionInterface
{
    public const string API_SERVER_ERROR = 'Internal Server Error';
    public const string API_NOT_FOUND = 'Object Not Found';
    public const string API_UNAUTHORIZED = 'Missing or invalid token';
    public const string API_ACCESS_DENIED = 'Access denied';
    public const string API_BAD_REQUEST = 'Bad request';

}