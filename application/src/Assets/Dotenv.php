<?php

/**
 * Lecture des variables d'environnement
 */

namespace App\Assets;

class Dotenv
{
    /**
     * Test les variables obligatoires
     *
     * @param array $vars
     * @return bool
     */
    public static function required(array $vars = [])
    {
        $missedVar = [];
        while (count($vars) != 0) {
            $var = \array_shift($vars);

            $content = self::getenv($var) ?: false;
            if ($content == false || empty($content)) {
                $missedVar[] = $var;
            }
        }
        if (count($missedVar)) {
            throw new \RuntimeException(\implode(',', $missedVar), 404);
            return false;
        }

        return true;
    }

    /**
     * Retourne la valeur de la clé d'environnement
     *
     * @param string $key
     * @param $defaultValue
     * @return mixed|null
     */
    public static function getenv(string $key, $defaultValue = null)
    {
        return isset($_ENV[$key]) ? $_ENV[$key] : $defaultValue;
    }

    /**
     * Retourne si le mode debug est actif ou non
     *
     * @return boolean
     */
    public static function isDebugMode()
    {
        return boolval(Dotenv::getenv('DEBUG', false));
    }
}
