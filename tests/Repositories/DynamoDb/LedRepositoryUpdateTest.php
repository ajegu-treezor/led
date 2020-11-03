<?php


namespace Repositories\DynamoDb;


use App\Exceptions\LedInvalidException;
use App\Models\Led;
use App\Repositories\DynamoDb\LedRepository;
use App\Repositories\LedRepositoryInterface;
use Faker\Factory;
use TestCase;

class LedRepositoryUpdateTest extends TestCase
{
    public function testUpdateShouldBeOk()
    {
        $this->artisan('db:init --seed');

        /** @var LedRepositoryInterface $repo */
        $repo = $this->app->make(LedRepository::class);

        $leds = $repo->all();
        $existingLed = $leds[0];

        $faker = Factory::create();
        $led = new Led(
            $existingLed->getId(),
            $faker->name,
            $faker->dateTime->getTimestamp()
        );

        $repo->update($led);

        $ledUpdated = $repo->get($led->getId());

        $this->assertEquals($led->getName(), $ledUpdated->getName());
        $this->assertEquals($led->getLastUpdate(), $ledUpdated->getLastUpdate());
    }

    public function testUpdateWithUnknownLedShouldThrowException()
    {
        $this->artisan('db:init');

        /** @var LedRepositoryInterface $repo */
        $repo = $this->app->make(LedRepository::class);

        $faker = Factory::create();
        $led = new Led(
            $faker->uuid,
            $faker->name,
            $faker->dateTime->getTimestamp()
        );

        $thrException = false;
        try {
            $repo->update($led);
        } catch (LedInvalidException $exception) {
            $thrException = true;
        }

        $this->assertTrue($thrException, "Update with an unknown led should be throw an exception.");
    }
}
