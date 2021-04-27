<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

/**
 * CORS middleware.
 */
final class CorsMiddleware extends MiddlewareAbstract
{
    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @return ResponseInterface The response
     */
    public function doProcess(): Response
    {

        $routeContext   = RouteContext::fromRequest($this->request);
        $routingResults = $routeContext->getRoutingResults();
        $methods        = $routingResults->getAllowedMethods();
        $requestHeaders = $this->request->getHeaderLine('Access-Control-Request-Headers');

        $response = $this->handler->handle($this->request);

        $response = $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $methods))
            ->withHeader('Access-Control-Allow-Headers', $requestHeaders ?: '*');

        // Optional: Allow Ajax CORS requests with Authorization header
        $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
