<?php


namespace Repositories\DynamoDb;


use App\Exceptions\LedInvalidException;
use App\Exceptions\LedNotFoundException;
use App\Repositories\DynamoDb\LedRepository;
use App\Repositories\LedRepositoryInterface;
use Faker\Factory;
use TestCase;

class LedRepositoryDeleteTest extends TestCase
{
    public function testAllShouldBeOk()
    {
        $this->artisan('db:init --seed');

        /** @var LedRepositoryInterface $repo */
        $repo = $this->app->make(LedRepository::class);

        $leds = $repo->all();

        $deletedLed = $leds[0];

        $repo->delete($deletedLed->getId());

        $thrException = false;
        try {
            $repo->get($deletedLed->getId());
        } catch (LedNotFoundException $exception) {
            $thrException = true;
        }

        $this->assertTrue($thrException, "Get a deleted led should be throw an exception.");
    }

    public function testDeleteWithUnknownLedShouldThrowException()
    {
        $this->artisan('db:init');

        $faker = Factory::create();

        /** @var LedRepositoryInterface $repo */
        $repo = $this->app->make(LedRepository::class);

        $thrException = false;
        try {
            $repo->delete($faker->uuid);
        } catch (LedInvalidException $exception) {
            $thrException = true;
        }

        $this->assertTrue($thrException, "Delete an unknown led should be throw an exception.");
    }
}
