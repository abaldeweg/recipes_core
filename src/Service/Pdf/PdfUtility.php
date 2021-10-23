<?php

namespace App\Service\Pdf;

class PdfUtility
{
    public function render(string $title, string $content, string $filename): string
    {
        $pdf = new TcpdfUtility();
        $pdf->SetCreator('baldeweg/recipes_core');
        $pdf->SetTitle($title);
        $pdf->SetMargins(20, 5, 20, true);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->AddPage();
        $pdf->writeHTML($content, true, false, true, false, '');
        $pdf->lastPage();

        return $pdf->Output($filename.'.pdf', 'F');
    }
}
