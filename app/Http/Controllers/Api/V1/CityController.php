<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;

class CityController extends Controller
{

    public function index(int $ufIbgeId)
    {
        $cities = City::whereUfIbgeId($ufIbgeId)->get();

        return CityResource::collection($cities);
    }
}
