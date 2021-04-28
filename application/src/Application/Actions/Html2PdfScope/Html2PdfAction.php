<?php

declare(strict_types=1);

namespace App\Application\Actions\Html2PdfScope;

use App\Application\Actions\ActionAbstract;
use Psr\Http\Message\ResponseInterface as Response;
use Wyzen\Php\Helper;

class Html2PdfAction extends ActionAbstract
{
    /**
     * Création des datas pour generer doc api
     *
     * @return Response
     */
    protected function action(): Response
    {
        $data           = $this->getFormData();
        $url            = Helper::findInArrayByTag($data, 'url');
        $html           = Helper::findInArrayByTag($data, 'html');
        $options_common = Helper::findInArrayByKeys($data, 'options', 'common') ?: [] ;
        $options_type   = Helper::findInArrayByKeys($data, 'options', 'pdf') ?: [];

        $content = $url ?: $html ?: null;
        if (!$content) {
            throw new \Exception("Bad parameter. Need url or html parameter");
        }

        /**
         * [
         *   url: <url>
         *   html: <html>
         *   params: [<params>]
         * ]
         */

        try {
            if ($url) {
                $uc = new \App\UseCases\Html2Pdf\GenerateUriToPdf($this->getContainer());
            } elseif ($html) {
                $uc = new \App\UseCases\Html2Pdf\GenerateHtmlToPdf($this->getContainer());
            }
            $result = $uc($content, \array_merge($options_common, $options_type));

            $uc->wk->removeTemporaryFiles();
        } catch (\Exception $ex) {
            die($ex->getMessage());
        }
        return $this->respondPdf($result);
    }
}
