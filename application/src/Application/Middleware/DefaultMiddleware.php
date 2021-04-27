<?php

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;

final class DefaultMiddleware extends MiddlewareAbstract
{
    /**
     * {@inheritdoc}
     */
    public function doProcess(): Response
    {
        // Laisser passer les requêtes HTTP OPTIONS
        if ($this->request->getMethod() === "OPTIONS") {
            return $this->handler->handle($this->request);
        }

        return $this->handler->handle($this->request);
    }
}
