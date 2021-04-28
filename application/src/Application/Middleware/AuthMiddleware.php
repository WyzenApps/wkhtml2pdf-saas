<?php

namespace App\Application\Middleware;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpException;
use Slim\Routing\RouteContext;
use Throwable;
use Wyzen\Php\Helper;

final class AuthMiddleware extends MiddlewareAbstract
{
    /**
     * {@inheritdoc}
     */
    public function doProcess(): Response
    {
        $time = time();

        try {
            $routeContext = RouteContext::fromRequest($this->request);
            $route        = $routeContext->getRoute();

        // return NotFound for non existent route
            if (empty($route)) {
                throw new \Exception("Route not found");
            }

            $name = $route->getName();


            $Authorization = $this->resolveParam('Authorization', null) ?? $this->request->getHeader("Authorization")[0] ?? $_COOKIE['Authorization'] ?? false;

            $Authorization = \is_array($Authorization) ? $Authorization[0] : $Authorization;

        /**
         * Si pas de header Authorization
         */
            if (empty("$Authorization")) {
                throw new \Exception("Authorization not found");
            }

            $jwtData = \str_replace('Bearer ', '', $Authorization);

            /** @var Token */
            $token = (new Parser())->parse((string) $jwtData); // Parses from a string sans signature

            $token_account = $token->claims()->get('account');
            $account       = $this->getConfig('account', $token_account);
            if (!$account) {
                throw new \Exception("Unknown account");
            }

            // Recup de la signature dans le fichier de conf
            $account_name        = Helper::findInArrayByKeys($account, 'name');
            $account_private_key = Helper::findInArrayByKeys($account, 'private-key');
            $account_enable      = Helper::findInArrayByKeys($account, 'enable') ?? true;

            $signer      = new Sha256();
            $private_key = InMemory::plainText($account_private_key);
            dd($token->signature());
            dd($signer->verify((string) $token->signature(), $token->payload(), $private_key));

            // Creation de la configuration jwt
            $config = Configuration::forSymmetricSigner(
                // You may use any HMAC variations (256, 384, and 512)
                $signer,
                // replace the value below with a key of your own!
                $private_key
                // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
            );

            // On relit le token mais avec la configuration
            $token = $config->parser()->parse((string) $jwtData);

            $config->setValidationConstraints(new SignedWith($signer, $private_key));

            dd($signer->verify((string) $token->signature(), $token->getPayload(), $private_key));

            dd($token->signature(), $token->getPayload());

            $config->validator()->validate($token, ...$config->validationConstraints());
            // if (! $config->validator()->validate($token, ...$config->validationConstraints())) {
            //     throw new \Exception("Bad signature");
            // }

            return $this->handler->handle($this->request);
        } catch (Throwable $ex) {
            dd("Access denied", 403, $ex->getMessage());
            throw new HttpException($this->request, "Access denied", 403);
        }

        return $this->handler->handle($this->request);
    }
}
