<?php

declare(strict_types=1);

namespace App\NameInterface;

interface ParameterInterface
{
    public const string STRING = 'string';
    public const string INT = 'integer';
    public const string DECIMAL = 'decimal';
    public const string DATETIME = 'datetime';
    public const string FLOAT = 'float';
}