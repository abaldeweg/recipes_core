<?php

namespace App\Service\Pdf;

class TcpdfUtility extends \TCPDF
{
    public function Header(): void
    {
        $this->writeHTMLCell(0, 0, 0, 0, null, 0, 0, false, true, 'top', true);
    }

    public function Footer(): void
    {
        $this->writeHTMLCell(0, 0, 0, 0, null, 0, 0, false, true, 'top', true);
    }
}
