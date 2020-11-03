<?php


namespace Repositories\DynamoDb;


use App\Exceptions\LedInvalidException;
use App\Exceptions\LedNotFoundException;
use App\Models\Led;
use App\Repositories\DynamoDb\LedRepository;
use App\Repositories\LedRepositoryInterface;
use DateTime;
use Faker\Factory;
use TestCase;

class LedRepositoryCreateTest extends TestCase
{
    public function testCreateShouldBeOk()
    {
        $this->artisan('db:init');

        /** @var LedRepositoryInterface $repo */
        $repo = $this->app->make(LedRepository::class);

        $faker = Factory::create();
        $led = new Led(
            $faker->name,
            (new DateTime())->getTimestamp()
        );
        $repo->create($led);

        $thrException = false;
        try {
            $repo->get($led->getId());
        } catch (LedNotFoundException $exception) {
            $thrException = true;
        }

        $this->assertFalse($thrException, "Get a led after to create it should be OK.");
    }

    public function testCreateWithBadValueShouldThrowException()
    {
        $this->artisan('db:init --seed');

        /** @var LedRepositoryInterface $repo */
        $repo = $this->app->make(LedRepository::class);

        $leds = $repo->all();
        $existingLed = $leds[0];

        $faker = Factory::create();
        $led = new Led(
            $faker->name,
            (new DateTime())->getTimestamp(),
            $existingLed->getId()
        );


        $thrException = false;
        try {
            $repo->create($led);
        } catch (LedInvalidException $exception) {
            $thrException = true;
        }

        $this->assertTrue($thrException, "Create led with an existing ID should throw an exception.");
    }
}
