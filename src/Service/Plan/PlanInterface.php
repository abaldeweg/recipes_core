<?php

namespace App\Service\Plan;

interface PlanInterface
{
    public function create(string $dest, string $filename, array $data): bool;
}
