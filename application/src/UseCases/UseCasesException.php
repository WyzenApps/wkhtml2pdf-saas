<?php

declare(strict_types=1);

namespace App\UseCases;

class UseCasesException extends \Exception
{
    protected $message = 'This usecase does not exists.';

    // public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    // {
    // }
}
