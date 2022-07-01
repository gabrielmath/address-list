<?php

namespace App\Integrations;

interface InstituteInterface
{
    public function getStates(): array;

    public function getCities(int $ufIbgeId): array;
}
