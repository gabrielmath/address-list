<?php

namespace Database\Factories;

use App\Integrations\IBGE\IBGEInstitute;
use App\Integrations\InstituteInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $ibgeDataIDs = $this->getShuffleArrayCities(new IBGEInstitute());
        return [
            'ibge_id'      => $ibgeDataIDs['city'],
            'name'         => $this->faker->city,
            'uf_ibge_id'   => $ibgeDataIDs['state'],
            'uf'           => $this->faker->streetSuffix,
            'uf_full_name' => $this->faker->name
        ];
    }

    private function getShuffleArrayCities(InstituteInterface $institute)
    {
        $statesList = $institute->getStates();

        $states = [];
        foreach ($statesList as $state) {
            $states[] = $state['ibge_id'];
        }

        shuffle($states);

        $cities = [];
        foreach ($institute->getCities($states[0]) as $city) {
            $cities[] = $city['ibge_id'];
        }

        shuffle($cities);
        return ['city' => $cities[0], 'state' => $states[0]];
    }

}
