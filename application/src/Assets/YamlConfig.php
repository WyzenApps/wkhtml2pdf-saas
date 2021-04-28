<?php

/**
 * Lecture des variables d'environnement
 */

namespace App\Assets;

use Wyzen\Php\Helper;

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
     * Retourne la config total ou un élément de la config
     *
     * @param [array] ...$keys liste des clés de la config
     *
     * @return mixed
     */
    public function getValue(...$keys)
    {
        return Helper::findInArrayByKeys($this->config, ...$keys);
    }
}
