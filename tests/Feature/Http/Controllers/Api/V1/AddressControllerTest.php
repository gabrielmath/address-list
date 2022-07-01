<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Address;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test * */
    public function it_should_return_a_list_addresses()
    {
        Artisan::call('cities:add-from-api 41');

        $city = City::find(209);
        $addresses = Address::factory(30)
            ->create(['city_id' => $city->id]);

        $this
            ->getJson(route('api.v1.addresses.index'))
            ->assertSuccessful()
            ->assertJsonCount(30);
    }

    /** @test */
    public function it_should_create_a_address()
    {
        Artisan::call('cities:add-from-api 41');

        $street = $this->faker->streetName;
        $number = $this->faker->numberBetween(1, 10000);
        $neighborhood = $this->faker->name;
        $city = City::find(209);


        $this
            ->postJson(route('api.v1.addresses.store'), [
                'cidade_id'  => $city->id,
                'logradouro' => $street,
                'numero'     => $number,
                'bairro'     => $neighborhood
            ])
            ->assertJson([
                'address' => [
                    'id'                => 1,
                    'cidade_id'         => $city->id,
                    'endereco_completo' => "Endereço: {$street}, Nº: {$number}, {$neighborhood} - {$city->name}/{$city->uf}",
                    'logradouro'        => $street,
                    'numero'            => $number,
                    'bairro'            => $neighborhood,
                    'cidade'            => "{$city->name}/{$city->uf}"
                    //                    'cidade'            => new CityResource($city)
                ]
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_return_a_unprocessable_content_error_when_form_is_empty()
    {
        Artisan::call('cities:add-from-api 41');

        $street = '';
        $number = '';
        $neighborhood = '';
        $city = City::find(209);


        $this
            ->postJson(route('api.v1.addresses.store'), [
                'cidade_id'  => $city->id,
                'logradouro' => $street,
                'numero'     => $number,
                'bairro'     => $neighborhood
            ])
            ->assertUnprocessable();
    }

    /** @test */
    public function it_should_show_a_unique_address()
    {
        Artisan::call('cities:add-from-api 41');

        $city = City::find(209);

        Address::factory(1)
            ->create(['city_id' => $city->id]);

        $address = Address::first();

        $this
            ->getJson(route('api.v1.addresses.show', ['address' => $address->id]))
            ->assertJson([
                'address' => [
                    'id'                => $address->id,
                    'cidade_id'         => $address->city->id,
                    'endereco_completo' => "Endereço: {$address->street}, Nº: {$address->number}, {$address->neighborhood} - {$address->city->name}/{$address->city->uf}",
                    'logradouro'        => $address->street,
                    'numero'            => $address->number,
                    'bairro'            => $address->neighborhood,
                    'cidade'            => "{$address->city->name}/{$address->city->uf}"
                    //                    'cidade'            => new CityResource($city)
                ]
            ])
            ->assertSuccessful()
            ->assertJsonCount(1);
    }

    /** @test */
    public function it_should_return_not_found_error_when_address_not_exists()
    {
        Artisan::call('cities:add-from-api 41');

        $city = City::find(209);

        Address::factory(3)
            ->create(['city_id' => $city->id]);

        $address = Address::first();

        $this
            ->getJson(route('api.v1.addresses.show', ['address' => 10]))
            ->assertNotFound();
    }

    /** @test */
    public function it_should_update_a_address()
    {
        Artisan::call('cities:add-from-api 41');

        $city = City::find(209);

        Address::factory(3)
            ->create(['city_id' => $city->id]);

        $address = Address::first();

        $street = $this->faker->streetName;
        $number = $this->faker->numberBetween(1, 10000);
        $neighborhood = $this->faker->name;


        $this
            ->putJson(route('api.v1.addresses.update', ['address' => $address->id]), [
                'cidade_id'  => $city->id,
                'logradouro' => $street,
                'numero'     => $number,
                'bairro'     => $neighborhood
            ])
            ->assertJson([
                'address' => [
                    'id'                => 1,
                    'cidade_id'         => $city->id,
                    'endereco_completo' => "Endereço: {$street}, Nº: {$number}, {$neighborhood} - {$city->name}/{$city->uf}",
                    'logradouro'        => $street,
                    'numero'            => $number,
                    'bairro'            => $neighborhood,
                    'cidade'            => "{$city->name}/{$city->uf}"
                    //                    'cidade'            => new CityResource($city)
                ]
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_return_unprocessable_content_error_when_update_address_with_form_empty()
    {
        Artisan::call('cities:add-from-api 41');

        $city = City::find(209);

        Address::factory(3)
            ->create(['city_id' => $city->id]);

        $address = Address::first();

        $street = '';
        $number = $this->faker->numberBetween(1, 10000);
        $neighborhood = $this->faker->name;


        $this
            ->putJson(route('api.v1.addresses.update', ['address' => $address->id]), [
                'cidade_id'  => $city->id,
                'logradouro' => $street,
                'numero'     => $number,
                'bairro'     => $neighborhood
            ])
            ->assertUnprocessable();
    }

    /** @test */
    public function it_should_delete_a_address()
    {
        Artisan::call('cities:add-from-api 41');

        $city = City::find(209);

        Address::factory(3)
            ->create(['city_id' => $city->id]);

        $address = Address::first();

        $this
            ->deleteJson(route('api.v1.addresses.destroy', ['address' => $address->id]))
            ->assertNoContent();
    }

    /** @test */
    public function it_should_return_not_found_error_when_delete_a_address_not_exists()
    {
        Artisan::call('cities:add-from-api 41');

        $city = City::find(209);

        Address::factory(3)
            ->create(['city_id' => $city->id]);

        $address = Address::first();

        $this
            ->deleteJson(route('api.v1.addresses.destroy', ['address' => 7]))
            ->assertNotFound();
    }
}
