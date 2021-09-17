<?php

declare(strict_types=1);

namespace App\Traits;

use App\Domain\AccountScope\Account\Account;
use Symfony\Component\VarDumper\VarDumper;

trait GetterSetter
{
    /**
     * Retourne la valeur
     * @return mixed
     */
    public function __get(string $name)
    {
        if (!property_exists(self::class, $name)) {
            throw new \Exception("Property $name does not exists in " . self::class);
        }
        return $this->$name;
    }

    /**
     * Initialise une valeur
     *
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public function __set(string $name, $value): self
    {
        if (!property_exists(self::class, $name)) {
            throw new \Exception("Property $name does not exists in " . self::class);
        }
        $this->$name = $value;
        return $this;
    }

    /**
     * call method to set/get value
     *
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     */
    public function __call(string $name, $arguments = null)
    {
        /**
         * Method get($key) : string
         */
        if ($name == 'get') {
            if (!\is_array($arguments) || count($arguments) == 0) {
                throw new \Exception("Method $name need a string parameter in " . self::class);
            }
            return $this->__get($arguments[0]);
        }

        /**
         * Method set($key, $value) : self
         */
        if ($name == 'set') {
            if (!\is_array($arguments) || count($arguments) != 2) {
                throw new \Exception("Method $name need a string parameter and value parameter in " . self::class);
            }
            return $this->__set($arguments[0], $arguments[1]);
        }

        if (!property_exists(self::class, $name)) {
            throw new \Exception("Property $name does not exists in " . self::class);
        }
        $this->$name = $arguments;
        return $this;
    }

    /**
     * Retourne le nom de la classe
     *
     * @return string
     */
    public function getClassShortname(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Retourne le nom de la classe avec son namespace
     *
     * @return string
     */
    public function getClassName(): string
    {
        return (new \ReflectionClass($this))->getName();
    }
}
