<?php

namespace Tests\Feature\Integrations\IBGE;

use App\Integrations\IBGE\IBGEInstitute;
use Tests\TestCase;

class IBGEInstituteTest extends TestCase
{
    /** @test */
    public function it_should_return_list_of_states()
    {
        $states = (new IBGEInstitute())->getStates();

        $this->assertNotCount(0, $states);
    }

    /** @test */
    public function it_should_return_list_of_cities()
    {
        $cities = (new IBGEInstitute())->getCities(41);

        $this->assertNotCount(0, $cities);
    }
}
