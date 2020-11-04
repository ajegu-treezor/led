<?php


namespace Controllers;

use App\Models\Led;
use App\Repositories\DynamoDb\LedRepository;
use App\Response\Mapper\LedDTOMapper;
use TestCase;

class LedControllerGetAllTest extends TestCase
{
    public function testGetAllShouldBeOk()
    {
        $this->artisan('db:init --seed');

        $request = $this->get('/led')
            ->seeStatusCode(200);

        $repo = $this->app->make(LedRepository::class);
        $mapper = $this->app->make(LedDTOMapper::class);
        $leds = $repo->all();

        array_walk($leds, function (Led $led) use ($mapper, $request) {
            $dto = get_object_vars($mapper->map($led));
            $request->seeJson($dto);
        });

    }

}
