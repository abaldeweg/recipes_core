<?php

namespace App\Service\Plan\Tests;

use App\Service\Plan\Plan;
use PHPUnit\Framework\TestCase;

class PlanTest extends TestCase
{
    public function testPlan()
    {
        $twig = $this->getMockBuilder('\\Twig\\Environment')
            ->disableOriginalConstructor()
            ->getMock();

        $fs = $this->getMockBuilder('\\Symfony\\Component\\Filesystem\\Filesystem')
            ->disableOriginalConstructor()
            ->getMock();

        $pdf = $this->getMockBuilder('\\App\\Service\\Pdf\\PdfUtility')
            ->disableOriginalConstructor()
            ->getMock();
        $pdf->method('render')
            ->willReturn('content');

        $plan = new Plan($twig, $fs, $pdf);
        $response = $plan->create('./', 'file', ['data' => [], 'startDate' => '01.01.2021']);

        $this->assertTrue($response);
    }
}
