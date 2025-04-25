<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class ApiException extends Exception
{
    public function __construct(
        string    $errorMessage,
        int       $code = 0,
        Throwable $previousException = null,
    )
    {
        parent::__construct($errorMessage, $code, $previousException);
    }

}
