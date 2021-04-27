<?php

namespace App\Factory;

use App\Assets\Dotenv;
use App\Classes\WkHtml2Image;
use App\Classes\WkHtml2Pdf;
use App\Exceptions\RessourceException;

class WkHtml2PdfFactory
{
    /**
     * Create WkHtml2Pdf object
     *
     * @param string $type pdf or image
     *
     * @return WkHtml2Pdf|WkHtml2Image
     */
    public static function create(?string $type = 'pdf')
    {
        $wk = null;
        switch (\strtolower($type)) {
            case 'pdf':
                $wk        = new WkHtml2Pdf();
                $wk_binary = Dotenv::getenv('WK_PDF');
                break;
            case 'image':
                $wk        = new WkHtml2Image();
                $wk_binary = Dotenv::getenv('WK_IMAGE');
                break;

            default:
                throw new \Exception("Bad output type. Only Pdf or Image", 403);
                break;
        }

        $wkIsUrl = \filter_var($wk_binary, FILTER_VALIDATE_URL);

        if (false === $wkIsUrl) {
            if (!\file_exists($wk_binary)) {
                throw new RessourceException("$wk_binary binary not exists");
            }
            $wk->setBinary($wk_binary);
        } else {
            throw new \Exception(__CLASS__ . "bad parameter");
        }

        return $wk;
    }
}
