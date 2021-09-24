<?php

namespace App\UseCases;

use App\Assets\Dotenv;
use App\Assets\YamlConfig;
use App\Dto\ServiceScope\ResponseService;
use App\Traits\ConfigTrait;
use App\Traits\GetterSetter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

/**
 * Class commune aux useCases
 */
class UseCasesAbstract
{
    use GetterSetter;
    use ConfigTrait;


    private $container = null;
    /** @var YamlConfig */
    private $config = [];

    protected $client = null;

    /**
     * constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config    = $this->container->get('config');
        $this->client    = new Client();
    }

    /**
     * Retourne le repository de $className
     *
     * @param string $className
     * @return Object
     * @throws \DI\DependencyException
     */
    public function getRepo(string $className = null, $param = null): object
    {
        if ($this->container->has($className)) {
            $obj = $this->container->get($className);
            if (\is_null($param)) {
                return $obj;
            }

            $newObj = new $obj($param);
            unset($obj);
            return $newObj;
            // return (\is_null($param)) ? $obj : new $obj($param);
        }

        throw new \DI\DependencyException("$className does not exist.", 500);
    }

    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Merge les options par défaut et ceux de l'appli externe
     *
     * @param array $options
     *
     * @return array
     */
    public function mergeOptions($options = []): array
    {
        $header = \file_get_contents(\__ROOT_APP__ . '/data/header.html');
        $footer = \file_get_contents(\__ROOT_APP__ . '/data/footer.html');

        $mergedOptions = [
            'form_params' => [
                'license' => Dotenv::getenv('PDF_LICENSE'),
                'unit' => 'mm',
                'top' => '10',
                'bottom' => '10',
                'left' => '10',
                'right' => '10',
                'page_size' => 'A4',
                'orientation' => 'Portrait',
                'javascript_time' => 500,
                'toc' => true,
                'header' => $header,
                'footer' => $footer,
            ]
        ];

        $mergedOptions['form_params'] = \array_merge($mergedOptions['form_params'], $options);

        return $mergedOptions;
    }

    /**
     * Requête Post
     *
     * @param string $uri
     * @param array $options
     * @return array
     */
    public function getPdf(string $url, array $options = []): array
    {
        $options['url'] = $url;
        $mergedOptions  = $this->mergeOptions($options);

        return $this->callApi($mergedOptions);
    }

    /**
     * Requête Post
     *
     * @param string $uri
     * @param array $options
     * @return array
     */
    public function getPdfFromHtml(string $html, array $options = []): array
    {
        $options['html'] = $html;
        $mergedOptions   = $this->mergeOptions($options);

        return $this->callApi($mergedOptions);
    }

    /**
     * Appel de l'api
     *
     * @param array $options
     *
     * @return array
     */
    public function callApi($options = []): array
    {
        try {
            /** @var ResponseInterface */
            $response = $this->client->post(Dotenv::getenv('PDF_HOST_API'), $options);

            return [
                "statusCode" => $response->getStatusCode(),
                "data" => $response->getBody()->getContents(),
                "message" => $response->getReasonPhrase(),
            ];
        } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
            return [
                "statusCode" => $exception->getCode(),
                "data" => $response->getBody()->getContents(),
                "message" => $exception->getMessage(),
            ];
        } catch (GuzzleException $ex) {
            return [
                "statusCode" => $ex->getCode(),
                "data" => "Error",
                "message" => $ex->getMessage(),
            ];
        }
    }
}
