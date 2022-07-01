<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Integrations\IBGE\IBGEInstitute;
use App\Integrations\InstituteInterface;

class UfController extends Controller
{
    public function __construct(
        private readonly InstituteInterface $states = new IBGEInstitute()
    ) {
    }

    public function index()
    {
        return $this->states->getStates();
    }
}
