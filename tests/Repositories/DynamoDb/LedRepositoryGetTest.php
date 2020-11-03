<?php


namespace Repositories\DynamoDb;


use App\Exceptions\LedNotFoundException;
use App\Repositories\DynamoDb\LedRepository;
use App\Repositories\LedRepositoryInterface;
use Faker\Factory;
use TestCase;

class LedRepositoryGetTest extends TestCase
{
    public function testGetShouldBeOk()
    {
        $this->artisan('db:init --seed');

        /** @var LedRepositoryInterface $repo */
        $repo = $this->app->make(LedRepository::class);

        $leds = $repo->all();

        $expectedLed = $leds[0];
        $led = $repo->get($expectedLed->getId());

        $this->assertEquals($expectedLed->getId(), $led->getId());
        $this->assertEquals($expectedLed->getName(), $led->getName());
        $this->assertEquals($expectedLed->getLastUpdate(), $led->getLastUpdate());
    }

    public function testGetWithUnknownIdShouldThrowException()
    {
        $faker = Factory::create();

        /** @var LedRepositoryInterface $repo */
        $repo = $this->app->make(LedRepository::class);

        $thrException = false;
        try {
            $repo->get($faker->uuid);
        } catch (LedNotFoundException $exception) {
            $thrException = true;
        }

        $this->assertTrue($thrException, "Get led with unknown id should throw an exception");
    }
}
