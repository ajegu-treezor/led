<?php


namespace Response\Mapper;


use App\Models\Led;
use App\Response\Mapper\LedDTOMapper;
use Faker\Factory;
use TestCase;

class LedDTOMapperTest extends TestCase
{
    public function testMapShouldBeOk()
    {
        $faker = Factory::create();

        $led = new Led($faker->uuid, $faker->name, $faker->dateTime->getTimestamp());

        $mapper = new LedDTOMapper();
        $dto = $mapper->map($led);

        $this->assertEquals($led->getId(), $dto->id);
        $this->assertEquals($led->getName(), $dto->name);
        $this->assertEquals($led->getLastUpdate(), $dto->lastUpdate);
    }
}
