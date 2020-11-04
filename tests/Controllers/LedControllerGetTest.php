<?php


namespace Controllers;

use App\Repositories\DynamoDb\LedRepository;
use App\Response\Mapper\LedDTOMapper;
use TestCase;

class LedControllerGetTest extends TestCase
{
    public function testGetShouldBeOk()
    {
        $this->artisan('db:init --seed');

        $repo = $this->app->make(LedRepository::class);
        $mapper = $this->app->make(LedDTOMapper::class);
        $leds = $repo->all();

        $ledExpected = $leds[0];

        $this->get("/led/{$ledExpected->getId()}")
            ->seeStatusCode(200)
            ->seeJson($dto = get_object_vars($mapper->map($ledExpected)));
    }
}
