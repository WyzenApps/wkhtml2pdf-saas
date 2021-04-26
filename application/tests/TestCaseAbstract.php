<?php

declare(strict_types=1);

namespace Tests;

use DI\ContainerBuilder;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

abstract class TestCaseAbstract extends TestCase
{

    /** @var App */
    protected static $app = null;

    /** @var ContainerInterface */
    protected static $container = null;

    protected static $cache = null;

    public function __construct($name = null, array $data = [], $dataName = '', ?ContainerInterface $container = null)
    {
        parent::__construct($name, $data, $dataName);
        self::$container = $container;
    }

    /**
     * Retourne le container
     *
     * @return ContainerInterface
     */
    public static function getContainer(): ContainerInterface
    {
        return self::$container;
    }

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$app       = self::getAppInstance();
        self::$container = self::$app->getContainer();
        self::$cache     = self::$container->get('cache');
    }

    /**
     * @return App
     * @throws Exception
     */
    protected static function getAppInstance(): App
    {
        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        // Container intentionally not compiled for tests.
        require_once __DIR__ . '/../config/bootstrap.php';

        // Set up settings
        $settings = require __DIR__ . '/../app/settings.php';
        $settings($containerBuilder);

        // Set up dependencies
        $dependencies = require __DIR__ . '/../app/dependencies.php';
        $dependencies($containerBuilder);

        // Build PHP-DI Container instance
        $container = $containerBuilder->build();

        // Instantiate the app
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        // Register middleware
        $middleware = require __DIR__ . '/../app/middleware.php';
        $middleware($app);
        // Register routes
        $routes = require __DIR__ . '/../app/routes.php';
        $routes($app);

        // toujours etre sur le sqlite en test
        $_ENV['DATA_TYPE'] = 'MEMORY';

        return $app;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $headers
     * @param array  $serverParams
     * @param array  $cookies
     * @return Request
     */
    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $serverParams = [],
        array $cookies = []
    ): Request
    {
        $uri    = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }
}
