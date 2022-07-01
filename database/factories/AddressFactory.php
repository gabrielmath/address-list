<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $cities = City::query()->with('addresses')->get()->shuffle();

        return [
            'city_id'      => $cities->first(),
            'street'       => $this->faker->streetName,
            'number'       => $this->faker->numberBetween(0, 10000),
            'neighborhood' => $this->faker->name,
        ];
    }


}
