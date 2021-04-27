<?php

declare(strict_types=1);

namespace App\Exceptions;

class RessourceException extends \Exception
{
    protected $code = 503;
}
