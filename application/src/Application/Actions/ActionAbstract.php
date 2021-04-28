<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Assets\Dotenv;
use App\Exceptions\DomainException;
use App\Exceptions\DomainRecordNotFoundException;
use App\Domain\Logger\Logger;
use App\Traits\ConfigTrait;
use DateTime;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use OpenApi\Annotations as OA;
use Symfony\Component\VarDumper\VarDumper;
use Wyzen\Php\Helper;

/**
 * @OA\Info(title="Project'In API", version="0.1")
 */
/**
 * @OA\Server(url="http://api.wyzen.projectin.docker.localhost")
 */


/**
 * @OA\Schema(
 *      schema="Auth",
 *      description="Auth",
 *
 *                  @OA\Property(
 *                  type="boolean",
 *                  property="canRead",
 *                  example=true
 *               ),
 *                 @OA\Property(
 *                  type="boolean",
 *                  property="canUpdate",
 *                  example=true
 *                  ),
 * )
 */

/**
 *
 * @OA\Parameter(
 *   name="phaseId",
 *   in="path",
 *   description="L'id de la phase à récupérer",
 *   required=true,
 *   @OA\Schema(type="integer")
 * ),
 *
 * @OA\Parameter(
 *   name="id",
 *   in="path",
 *   description="L'id de la ressource à récupérer",
 *   required=true,
 *   @OA\Schema(type="integer")
 * ),
 *
 * @OA\Response(
 *      response="NotFound",
 *      description="La ressource n'existe pas",
 *      @OA\JsonContent(
 *          @OA\Property(property="message", type="string", example="La ressource n'existe pas")
 *      )
 * )
 */
abstract class ActionAbstract
{
    use ConfigTrait;

    /**
     * Defini le type de retour de l'action
     *
     * @var string data data by default
     */
    protected $returnType = 'data'; // Default

    /**
     * @var ContainerInterface
     */
    protected $container;

    /** @var Logger */
    protected $logger;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * fields in url params
     * @var array
     */
    protected $args;

    /**
     * $_FILES
     * @var array
     */
    protected $files;

    /**
     * URL Params
     * @var array
     */
    protected $params;

    /**
     * POST/PUT/PATCH data
     * @var array
     */
    protected $data;

    private $startTime  = null;
    private $pagination = null;
    private $endTime    = null;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger    = $this->container->get('logger');
        $this->config    = $this->container->get('config');
        $this->startTime = new DateTime();
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        /** @var ActionPayload */
        $payload = new ActionPayload(200, "OK");

        $this->request  = $request;
        $this->response = $response;
        $this->args     = $args;
        $this->files    = $request->getUploadedFiles();
        $this->params   = $request->getQueryParams();
        $this->data     = $request->getParsedBody();

        try {
            /**
             * Exécute l'action à moins qu'une exception ait lieu
             */
            return $this->action();
        } catch (DomainException $ex) {
            $payload = new ActionPayload($ex->getCode(), null, new ActionError(Helper::classBasename($ex), $ex->getMessage()));
        } catch (DomainException $ex) {
            $payload = new ActionPayload($ex->getCode(), null, new ActionError(Helper::classBasename($ex), $ex->getMessage()));
        } catch (\TypeError $ex) {
            $payload = new ActionPayload($ex->getCode(), null, new ActionError(Helper::classBasename($ex), $ex->getMessage()));
        } catch (\InvalidArgumentException $ex) {
            $payload = new ActionPayload($ex->getCode(), null, new ActionError(Helper::classBasename($ex), $ex->getMessage()));
        } catch (\Exception $ex) {
            $payload = new ActionPayload($ex->getCode(), null, new ActionError(Helper::classBasename($ex), $ex->getMessage()));
        }

        /**
         * Ici si une exception a été générée
         */
        return $this->respond($payload)->withStatus($payload->getStatusCode());
    }

    /**
     * @return Response
     * @throws DomainRecordNotFoundException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return array
     * @throws HttpBadRequestException
     */
    protected function getFormData()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpBadRequestException($this->request, 'Malformed JSON input.');
        }

        return $input;
    }

    /**
     * Return all args
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function getArgs(): array
    {
        if (!isset($this->args)) {
            return [];
        }

        return $this->args;
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
    protected function getFiles(): array
    {
        if (!isset($this->files)) {
            return [];
        }

        return $this->files;
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
     * Return arg of url
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name, $exfaultValue = null)
    {
        if (!isset($this->args[$name])) {
            if (!\is_null($exfaultValue)) {
                return $exfaultValue;
            }

            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * Return param from url
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveParam(string $name, $exfaultValue = null)
    {
        if (!isset($this->params[$name])) {
            if (!\is_null($exfaultValue)) {
                return $exfaultValue;
            }

            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->params[$name];
    }

    /**
     * Return POST/PUT/PATCH data
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveData(string $name, $exfaultValue = null)
    {
        if (!isset($this->data[$name])) {
            if (!\is_null($exfaultValue)) {
                return $exfaultValue;
            }

            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->data[$name];
    }

    /**
     * Téléchargement d'un fichier
     *
     * @param [type] $stream
     * @param string $filename nom du fichier de sortie
     * @return Response
     */
    protected function downloadFile($stream, $filename): Response
    {

        $this->response->getBody()->write($stream);
        return $this->response->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader("Access-Control-Expose-Headers", "Content-Disposition")
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="' . Helper::utf8ToIso($filename) . '"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public');
    }

    /**
     * @param  array|object|null $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function generateYml($data = null): Response
    {
        $this->response->getBody()->write($data);
        return $this->respond($data)->withHeader('Content-Type', 'application/x-yaml');
    }

    /**
     * return HTML format
     *
     * @param string|null $content
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function respondHtml(?string $content = null): Response
    {
        $this->response->getBody()->write($content);
        return $this->response;
    }

    protected function respondPdf($content): Response
    {
        /**
         * Affiche la ligne de commande en cas de debug
         */
        if ($this->getConfig('general', 'debug') === true) {
            return $this->respondHtml("$content");
        }

        $this->response->getBody()->write($content);
        return $this->response->withAddedHeader('Content-Type', 'application/pdf');
    }

    protected function respondImage($content, string $type_image = null): Response
    {
        /**
         * Affiche la ligne de commande en cas de debug
         */
        if ($this->getConfig('general', 'debug') === true) {
            return $this->respondHtml("$content");
        }

        $this->response->getBody()->write($content);
        return $this->response->withAddedHeader('Content-Type', "image/$type_image");
    }

    /**
     * Retourne une réponse avec un code personnalisé
     * @param array|object|null $data
     * @param int|null $httpStatusCode
     * @return Response
     */
    protected function respondWithDataAndStatusCode($data = null, $httpStatusCode = 200): Response
    {
        $payload        = new ActionPayload($httpStatusCode, $data);
        $this->response = $this->response->withStatus($httpStatusCode);
        return $this->respond($payload);
    }

    /**
     * Retourne une réponse "ressource non trouvée"
     * @return Response
     */
    protected function respondsWith404(): Response
    {
        $payload = new ActionPayload(404, ['message' => 'Not found.']);
        return $this->respond($payload);
    }

    /**
     * @param  array|object|null $data
     * @return Response
     */
    protected function respondWithData($data = null): Response
    {
        $payload = new ActionPayload(200, $data);
        return $this->respond($payload);
    }

    /**
     * @param ActionPayload $payload
     * @return Response
     */
    protected function respond(ActionPayload $payload): Response
    {
        /** @var array */
        $arrayPayload = $payload->jsonSerialize();

        if (true) {
            $this->endTime = new DateTime();
            $interval      = $this->startTime->diff($this->endTime);

            $arrayPayload['debug'] = [
                'time_ms' => round($interval->format('%f') / 1000, 0),
                'args' => $this->getArgs(),
                'data' => $this->getData(),
                'params' => $this->getParams(),
            ];
        }

        $optionsJSON = Dotenv::isDebugMode() ? JSON_PRETTY_PRINT : 0;
        $json        = json_encode($arrayPayload, $optionsJSON);

        $this->response->getBody()->write($json);
        return $this->response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Facade Trans
     *
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param array       $parameters An array of parameters for the message
     * @param string|null $domain     The domain for the message or null to use the default
     * @param string|null $locale     The locale or null to use the default
     *
     * @return string The translated string
     */
    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $this->trans->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Retourne le chemin racine de App
     *
     * @return string
     */
    public function getAppPath(): string
    {
        return \realpath($this->container->get('settings')['appPath']);
    }

    /**
     * Positionne le type de retour de l'action sur DATA
     *
     * @return self
     */
    public function setDataReturnType()
    {
        $this->returnType = 'data';
        return $this;
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
     * Retourne le container
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
