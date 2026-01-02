<?php

namespace App\Service;

use Nucleos\DompdfBundle\Factory\DompdfFactoryInterface;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfGeneratorService
{

    public function __construct(
        private readonly DompdfFactoryInterface $factory,
        private readonly DompdfWrapperInterface $wrapper
        )
    {
    }

    //Pour ne pas personnaliser les options
    public function getPdfGenerate(string $html): string
    {
        return $this->wrapper->getPdf($html);
    }

    //Pour personnaliser les options
    public function outputPdf(string $html): string
    {
        $domPdf = $this->factory->create(['isRemoteEnabled' => 'true']);

        $domPdf->loadHtml($html);
        $domPdf->render();

        return $domPdf->output();
    }

    public function getStreamPdf(string $html, string $filename): StreamedResponse
    {
        return $this->wrapper->getStreamResponse($html, $filename);
    }
}