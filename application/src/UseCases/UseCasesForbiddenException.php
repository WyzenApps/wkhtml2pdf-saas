<?php

declare(strict_types=1);

namespace App\UseCases;

class UseCasesForbiddenException extends \Exception
{
    protected $message = 'core.message.forbidden';
    protected $code    = 403;

    // public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    // {
    // }
}
