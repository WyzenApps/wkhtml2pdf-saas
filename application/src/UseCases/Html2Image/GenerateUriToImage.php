<?php

namespace App\UseCases\Html2Image;

use App\Classes\WkHtml2Image;
use App\Factory\WkHtml2PdfFactory;
use App\UseCases\UseCasesAbstract;

class GenerateUriToImage extends UseCasesAbstract
{
    /** @var WkHtml2Image */
    public $wk = null;

    /**
     * Undocumented function
     *
     * @param string $uri URL or HTML
     * @param array $options Options of Snappy Pdf
     *
     * @return mixed|null
     */
    public function __invoke(string $uri, array $options = [])
    {
        /** @var WkHtml2Image */
        $this->wk = WkHtml2PdfFactory::create('image');
        $this->setDefaultOptions($this->wk, 'image');
        if (count($options)) {
            $this->wk->setOptions($options);
        }

        if ($this->getConfig('general', 'debug') === true) {
            return '<pre>' . \print_r($this->wk->getOptions(), true) . '</pre>';
        }
        return $this->wk->getOutput($uri);
    }
}
