<?php

namespace App\Integrations\IBGE;

use App\Integrations\InstituteInterface;
use Illuminate\Support\Facades\Http;

class IBGEInstitute implements InstituteInterface
{
    private const URL = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';

    public function getCities(int $ufIbgeId): array
    {
        $urlGetCities = self::URL . "/{$ufIbgeId}/municipios";
        $cities = Http::get($urlGetCities);

        return $this->minimumCitiesData($cities->json());
    }

    public function getStates(): array
    {
        $states = Http::get(self::URL);

        return $this->minimumStatesData($states->json());
    }

    private function minimumStatesData(array $completeStateArrayObject): array
    {
        $simpleStatesArrayObject = [];

        foreach ($completeStateArrayObject as $state) {
            $simpleStatesArrayObject[] = [
                'ibge_id' => (int)$state['id'],
                'sigla'   => $state['sigla'],
                'nome'    => $state['nome'],
            ];
        }

        return $simpleStatesArrayObject;
    }

    private function minimumCitiesData(array $completeCitiesArrayObject): array
    {
        if (empty($completeCitiesArrayObject)) {
            return [];
        }

        $simpleCitiesArrayObject = [];

        foreach ($completeCitiesArrayObject as $city) {
            $simpleCitiesArrayObject[] = [
                'ibge_id'      => (int)$city['id'],
                'name'         => $city['nome'],
                'uf_ibge_id'   => (int)$city['microrregiao']['mesorregiao']['UF']['id'],
                'uf'           => $city['microrregiao']['mesorregiao']['UF']['sigla'],
                'uf_full_name' => $city['microrregiao']['mesorregiao']['UF']['nome'],
            ];
        }

        return $simpleCitiesArrayObject;
    }
}
