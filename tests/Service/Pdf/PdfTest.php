<?php

namespace App\Service\Pdf\Tests;

use App\Service\Pdf\PdfUtility;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    public function testPdfUtility()
    {
        vfsStream::setup('root');
        $dest = vfsStream::url('root');

        $pdf = new PdfUtility();
        $pdf->render('Title', 'content', $dest.'/file');

        $this->assertTrue(is_file($dest.'/file.pdf'));
    }
}
