<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Domain\DomainException\DomainException;
use App\ValueObjects\AuthUser;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

abstract class MiddlewareAbstract implements MiddlewareInterface
{
    /** @var ContainerInterface */
    protected $container = null;

    /** @var AuthUser */
    protected $authUser;

    protected $handler;
    protected $request;
    protected $params;
    protected $data;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Retourne le repository de $className
     *
     * @param string $className
     * @return Object
     * @throws \DI\DependencyException
     */
    public function getRepo(string $className): object
    {
        if ($this->container->has($className)) {
            $obj = $this->container->get($className);
            return $obj;
        }
        throw new \DI\DependencyException("$className does not exist.", 404);
    }

    /**
     * doProcess
     * use $this->request et $this->handler
     *
     * @return Response
     */
    abstract public function doProcess(): Response;

    /**
     * Pre process
     *
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $this->handler = $handler;
        $this->request = $request;
        $this->params  = $request->getQueryParams();
        $this->data    = $request->getParsedBody();

        try {
            return $this->doProcess();
        } catch (DomainException $e) {
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }

    /**
     * Return all params : URL query Params
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function getParams(): array
    {
        if (!isset($this->params)) {
            return [];
        }

        return $this->params;
    }

    /**
     * Return all data : POST/PUT/PATCH data
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function getData(): array
    {
        if (!isset($this->data)) {
            return [];
        }

        return $this->data;
    }

    /**
     * Return param from url
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveParam(string $name, $defaultValue = null)
    {
        if (!isset($this->params[$name])) {
            if (!\is_null($defaultValue)) {
                return $defaultValue;
            }
            return null;
        }

        return $this->params[$name];
    }

    /**
     * Return POST/PUT/PATCH data
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveData(string $name, $defaultValue = null)
    {
        if (!isset($this->data[$name])) {
            if (!\is_null($defaultValue)) {
                return $defaultValue;
            }
            return null;
        }
        return $this->data[$name];
    }

    /**
     * Retourne le container
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
