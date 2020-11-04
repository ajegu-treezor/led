<?php


namespace Controllers;

use App\Exceptions\LedNotFoundException;
use App\Repositories\DynamoDb\LedRepository;
use Faker\Factory;
use TestCase;

class LedControllerDeleteTest extends TestCase
{
    public function testDeleteShouldBeOk()
    {
        $this->artisan('db:init --seed');

        $repo = $this->app->make(LedRepository::class);
        $leds = $repo->all();
        $ledDeleted = $leds[0];

        $this->json('DELETE', "/led/{$ledDeleted->getId()}")
            ->seeStatusCode(204);

        $thrException = false;
        try {
            $repo->get($ledDeleted->getId());
        } catch (LedNotFoundException $exception) {
            $thrException = true;
        }

        $this->assertTrue($thrException, "Get a deleted led should throw an exception.");
    }

    public function testDeleteWithUnknownLedShouldBeOk()
    {
        $this->artisan('db:init');

        $faker = Factory::create();
        $this->json('DELETE', "/led/{$faker->uuid}")
            ->seeStatusCode(204);
    }
}
