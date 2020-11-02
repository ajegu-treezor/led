<?php


namespace Repositories\DynamoDb;


use App\Models\Led;
use App\Repositories\DynamoDb\LedRepository;
use \TestCase;

class LedRepositoryAllTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testAllShouldBeOk()
    {
        $this->artisan('db:init --seed');
        $repo = $this->app->make(LedRepository::class);
        $leds = $repo->all();

        $this->assertNotEmpty($leds);

        foreach ($leds as $led) {
            $this->assertInstanceOf(Led::class, $led);
        }
    }

    public function testAllWithNoDataShouldBeOk()
    {
        $this->artisan('db:init');
        $repo = $this->app->make(LedRepository::class);
        $leds = $repo->all();
        $this->assertEmpty($leds);
        $this->assertNotNull($leds);
    }
}
