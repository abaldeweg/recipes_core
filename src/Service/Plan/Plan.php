<?php

namespace App\Service\Plan;

use App\Service\Pdf\PdfUtility;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class Plan implements PlanInterface
{
    public function __construct(protected Environment $twig, protected Filesystem $fs, protected PdfUtility $pdf)
    {
    }

    public function create(string $dest, string $filename, array $data): bool
    {
        if (!is_dir($dest)) {
            $this->fs->mkdir($dest);
        }

        $data['data'] = $this->processData($data['data'], new \DateTime($data['startDate']));

        // pdf
        $this->pdf->render(
            'Plan',
            $this->twig->render(
                'plan.html.twig',
                $data
            ),
            $dest.'/'.$filename
        );

        // json
        \file_put_contents($dest.'/'.$filename.'.json', json_encode($data['data']));

        return true;
    }

    protected function processData(array $data, \DateTime|\DateTimeImmutable $date): array
    {
        $menu = [
            // monday
            '0' => [
                'date' => $date->format('d.m.Y'),
                '1' => null,
                '2' => null,
                '3' => null,
                '4' => null,
            ],
            // tuesday
            '1' => [
                'date' => $date->modify('+1 day')->format('d.m.Y'),
                '1' => null,
                '2' => null,
                '3' => null,
                '4' => null,
            ],
            // wednesday
            '2' => [
                'date' => $date->modify('+1 day')->format('d.m.Y'),
                '1' => null,
                '2' => null,
                '3' => null,
                '4' => null,
            ],
            // thursday
            '3' => [
                'date' => $date->modify('+1 day')->format('d.m.Y'),
                '1' => null,
                '2' => null,
                '3' => null,
                '4' => null,
            ],
            // friday
            '4' => [
                'date' => $date->modify('+1 day')->format('d.m.Y'),
                '1' => null,
                '2' => null,
                '3' => null,
                '4' => null,
            ],
        ];

        foreach ($data as $item) {
            $menu[$item->getDay()][$item->getCourse()] = $item;
        }

        return $menu;
    }
}
