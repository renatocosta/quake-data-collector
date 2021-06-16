<?php

namespace Domains\Context\LogHandler\Application\UseCases\LogFile;

use Exception;
use Throwable;

class SelectLogFileException extends Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
