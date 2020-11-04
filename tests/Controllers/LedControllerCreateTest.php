<?php


namespace Controllers;

use App\Repositories\DynamoDb\LedRepository;
use Faker\Factory;
use TestCase;

class LedControllerCreateTest extends TestCase
{
    public function testCreateShouldBeOk()
    {
        $this->artisan('db:init');

        $faker = Factory::create();

        $name = $faker->name;
        $this->json('POST', '/led', [
            'name' => $name
        ])
            ->seeStatusCode(201);

        $repo = $this->app->make(LedRepository::class);
        $leds = $repo->all();

        $this->assertCount(1, $leds);
        $this->assertEquals($name, $leds[0]->getName());
    }
}
