<?php


namespace Controllers;

use App\Repositories\DynamoDb\LedRepository;
use Faker\Factory;
use TestCase;

class LedControllerUpdateTest extends TestCase
{
    public function testUpdateShouldBeOk()
    {
        $this->artisan('db:init --seed');

        $repo = $this->app->make(LedRepository::class);
        $leds = $repo->all();
        $ledUpdated = $leds[0];

        $faker = Factory::create();

        $name = $faker->name;
        $this->json('PUT', "/led/{$ledUpdated->getId()}", [
            'name' => $name
        ])
            ->seeStatusCode(200);

        $led = $repo->get($ledUpdated->getId());
        $this->assertEquals($name, $led->getName());
        $this->assertGreaterThan($ledUpdated->getLastUpdate(), $led->getLastUpdate());
    }
}
