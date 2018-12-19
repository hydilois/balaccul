<?php

namespace AccountBundle\Service;

use Spipu\Html2Pdf\Html2Pdf;

/**
 * Class Html2PdfService
 *
 * @package AccountBundle\Service
 */
class Html2PdfService
{
    /**
     * @var
     */
    private $pdf;

    /**
     * @var
     */
    private $targetDir;

    /**
     * Html2PdfService constructor.
     * @param $targetDir
     */
    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param null $orientation
     * @param null $format
     * @param null $lang
     * @param null $unicode
     * @param null $encoding
     * @param null $margin
     */
    public function create ($orientation = null, $format = null, $lang = null, $unicode = null, $encoding = null, $margin = null)
    {
        $this->pdf = new Html2Pdf(
            $orientation ? $orientation : $this->orientation,
            $format ? $format : $this->format,
            $lang ? $lang : $this->lang,
            $unicode ? $unicode : $this->unicode,
            $encoding ? $encoding : $this->encoding,
            $margin ? $margin : $this->margin
        );
    }

    /**
     * @param $template
     * @param $name
     * @param null $directoryName
     * @param $title
     * @param $destination
     * @return mixed
     */
    public function generatePdf($template, $name, $directoryName = NULL, $title, $destination)
    {
        $this->pdf->writeHTML($template);
        $this->pdf->pdf->SetTitle($title);
        return $this->pdf->Output($this->getTargetDir().'/'.$directoryName.'/'.$name, $destination);
    }

    /**
     * @return mixed
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
}