<?php

/**
 * Class permettant de mettre en cache des objets depuis des clés
 */

declare(strict_types=1);

namespace App\Assets;

class CacheResults
{
    private static $cache   = [];
    private static $enabled = true;


    public function disable()
    {
        self::$enabled = false;
    }

    public function enable()
    {
        self::$enabled = true;
    }

    /**
     * Construit le nom de la clé
     *
     * @param array|string $keys
     * @return string
     */
    private function buildKey($keys): string
    {
        if (\is_array($keys)) {
            $key = \implode('_', $keys);
        } else {
            $key = $keys;
        }
        return $key;
    }
    /**
     * Test si la clé existe
     *
     * @param array|string $key
     * @return boolean
     */
    public function hasKey($keys): bool
    {
        $key = $this->buildKey($keys);
        return array_key_exists($key, self::$cache);
    }

    /**
     * Ajout un item au cache et le retourne
     * Si l'item est déjà dans le cache il est retourné
     *
     * @param array|string $keys
     * @param mixed $mixed
     * @return mixed L'item ajouté
     */
    public function addItem($keys, $mixed)
    {
        $key = $this->buildKey($keys);

        /**
         * Déjà dans le cache ?
         */

        if ($this->hasKey($key) && self::$enabled) {
            return self::$cache[$key];
        }

        if (\is_callable($mixed)) {
            self::$cache[$key] = call_user_func($mixed);
        } else {
            self::$cache[$key] = $mixed;
        }

        return self::$cache[$key];
    }

    /**
     * Retourne un item
     *
     * @param array|string $keys
     * @return mixed|null
     */
    public function getItem($keys)
    {
        $key = $this->buildKey($keys);
        if ($this->hasKey($key) && self::$enabled) {
            return self::$cache[$key];
        }
        return null;
    }

    /**
     * Clear cache
     *
     * @return void
     */
    public function clear($keys = null)
    {
        if (!\is_null($keys)) {
            $key = $this->buildKey($keys);
            unset(self::$cache[$key]);
        } else {
            self::$cache = [];
        }

        return $this;
    }
}
