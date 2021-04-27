<?Php

namespace App\Traits;

use Knp\Snappy\Image;
use Knp\Snappy\Pdf;

trait ConfigTrait
{

    /**
     * Retourne la config suivant les clés
     *
     * @param [type] ...$keys
     *
     * @return mixed
     */
    public function getConfig(...$keys)
    {
        return $this->config->getValue(...$keys);
    }

    /**
     * Set les options suivant Pdf ou Image
     *
     * @param Pdf|Image  $wk
     * @param string $type pdf|image
     *
     * @return void
     */
    public function setDefaultOptions($wk, string $type = 'pdf')
    {
        $wk->setOptions($this->getConfig('wk', 'common'));

        switch (\strtolower($type)) {
            case 'pdf':
            case 'image':
                $wk->setOptions($this->getConfig('wk', $type));
                break;
            default:
                throw new \Exception("Bad type default option : $type");
                break;
        }
        return $this;
    }
}
