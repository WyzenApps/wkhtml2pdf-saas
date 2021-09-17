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
     * Requête Post
     *
     * @param string $uri
     * @param array $options
     * @return array
     */
    public function getPdf(string $uri, array $options = []): array
    {
        $header = \file_get_contents(\__ROOT_APP__ . '/data/header.html');
        $footer = \file_get_contents(\__ROOT_APP__ . '/data/footer.html');

        $mergedOptions = [
            'form_params' => [
                'license' => Dotenv::getenv('LICENSE'),
                'url' => $uri,
                'unit' => 'mm',
                'top' => '20',
                'bottom' => '20',
                'left' => '10',
                'right' => '10',
                'title' => 'TEST PDF',
                'page_size' => 'A4',
                'orientation' => 'Portrait',
                'header' => $header,
                'footer' => $footer,
                'no_background' => true,
                'javascript_time' => 500,
                'toc' => true,
                'encryption_level' => 128,
                'no_print' => true,
                'no_copy' => true,
                'no_modify' => true,
                'owner_password' => 'test',
            ]
        ];

        $mergedOptions['form_params'] = \array_merge($mergedOptions['form_params'], $options);

        try {
            /** @var ResponseInterface */
            $response = $this->client->post(Dotenv::getenv('HOST_API'), $mergedOptions);

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

    /**
     * Requête Post
     *
     * @param string $uri
     * @param array $options
     * @return array
     */
    public function getPdfFromHtml(string $html, array $options = []): array
    {
        $header = <<<HTML
        <html><body style="border: 1px solid red;  height: 40px;">
<table style="border-bottom: 1px solid black; width: 100%">
    <tr>
        <td>%title</td>
        <td style="text-align:right">%DD %MM %YYYY</td>
    </tr>
</table>
</body></html>
HTML;

        $footer = <<<HTML
        <html><body style="border: 1px solid blue; height: 40px;">
<table style="border-top: 1px solid black; width: 100%">
    <tr>
        <td class=""></td>
        <td style="text-align:right">
            Page %page of %topage
        </td>
    </tr>
</table>
</body></html>
HTML;

        $options['form_params'] =
        [
            'license' => Dotenv::getenv('LICENSE'),
            'html' => $html,
            'unit' => 'mm',
            'top' => '20',
            'bottom' => '20',
            'left' => '10',
            'right' => '10',
            'title' => 'TEST PDF',
            'page_size' => 'A4',
            'orientation' => 'Portrait',
            'header' => $header,
            'footer' => $footer,
            'no_background' => true,
            'javascript_time' => 500,
            'toc' => true,
        ];

        try {
            /** @var ResponseInterface */
            $response = $this->client->post(Dotenv::getenv('HOST_API'), $options);

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
