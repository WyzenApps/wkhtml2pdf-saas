<?php

/**
 * Lecture des variables d'environnement
 */

namespace App\Assets;

use Symfony\Component\VarDumper\VarDumper;

class YamlConfig
{
    protected $config = null;

    public function __construct(?string $file = null)
    {
        if ($file) {
            $this->load($file);
        }
    }

    /**
     * Charge un fichier de config: config.yml
     *
     * @param string $file
     *
     * @return void
     */
    public function load(string $file)
    {
        if (! \file_exists($file) && ! \touch($file)) {
            throw new \Exception("File $file not exists or can not be created");
        }

        $yml_content = file_get_contents($file);

        if (false === $yml_content) {
            throw new \Exception("Error to load $file");
        }

        $parsed = \yaml_parse($yml_content);

        if (false === $parsed) {
            throw new \Exception("Bad content options parameter in $file");
        }

        $this->config = \array_merge([
            'general' => [],
            'account' => [],
            'wk' => [],
        ], $parsed);
        return $this->config;
    }

    /**
     * Retourne la config globale ou depuis une clé
     *
     * @param string|null $key
     *
     * @return array
     */
    public function getConfig(?string $key = null): array
    {
        if ($key) {
            if (\array_key_exists($key, $this->config)) {
                return $this->config[$key];
            }
            return [];
        }

        return $this->config;
    }

    /**
     * Retourne la config total ou un élément de la config
     *
     * @param [array] ...$keys liste des clés de la config
     *
     * @return mixed
     */
    public function getValue(...$keys)
    {
        if (\is_null($keys) || \count($keys) === 0) {
            return $this->config;
        }

        $data = $this->config;
        foreach ($keys as $key) {
            if (! \array_key_exists($key, $data)) {
                return null;
            }
            $data = $data[$key];
        }
        return $data;
    }
}
