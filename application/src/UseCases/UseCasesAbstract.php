<?php

namespace App\UseCases;

use App\Assets\YamlConfig;
use App\Traits\ConfigTrait;
use Psr\Container\ContainerInterface;

/**
 * Class commune aux useCases
 */
class UseCasesAbstract
{
    use ConfigTrait;

    private $container = null;
    /** @var YamlConfig */
    private $config = [];

    /**
     * constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config    = $this->container->get('config');
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
}
