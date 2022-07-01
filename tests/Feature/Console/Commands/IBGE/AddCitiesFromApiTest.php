<?php

namespace Tests\Feature\Console\Commands\IBGE;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AddCitiesFromApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_true_after_populate_database()
    {
        $this->artisan('cities:add-from-api')->assertSuccessful();
    }

    /** @test */
    public function it_should_return_true_after_populate_database_with_one_uf_ibge_id()
    {
        $this->artisan('cities:add-from-api 35')->assertSuccessful();
    }

    /** @test */
    public function it_should_return_true_after_populate_database_with_many_uf_ibge_id()
    {
        $this->artisan('cities:add-from-api 35 41 11')->assertSuccessful();
    }

    /** @test */
    public function it_should_not_duplicate_data_of_cities()
    {
        Artisan::call('cities:add-from-api 41');
        $citiesPR = City::get()->count();

        Artisan::call('cities:add-from-api 41');
        $citiesPR2 = City::get()->count();

        $this->assertEquals($citiesPR, $citiesPR2);
    }

    /** @test */
    public function it_should_clear_database_before_save_cities()
    {
        Artisan::call('cities:add-from-api 41 11');
        $citiesPR = City::get()->count();

        Artisan::call('cities:add-from-api 41 35 --fresh');
        $citiesSP = City::get()->count();

        $this->assertNotEquals($citiesPR, $citiesSP);
    }

    /** @test */
    public function it_should_return_error_while_uf_ibge_id_not_exists()
    {
        $this->artisan('cities:add-from-api 100')->assertExitCode(1);
    }

    /** @test */
    public function it_should_return_error_while_one_or_more_uf_ibge_id_not_exists()
    {
        $this->artisan('cities:add-from-api 11 100 41 102')->assertExitCode(1);
    }
}
