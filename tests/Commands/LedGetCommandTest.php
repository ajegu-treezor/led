<?php


namespace Commands;


use App\Commands\LedGetCommand;
use App\Models\Led;
use App\Repositories\LedRepositoryInterface;
use App\Response\DTO\LedDTO;
use App\Response\Mapper\LedDTOMapper;
use Faker\Factory;
use TestCase;

class LedGetCommandTest extends TestCase
{
    public function testLedGetCommandShouldBeOk()
    {
        $faker = Factory::create();

        $led = new Led($faker->uuid, $faker->name, $faker->dateTime->getTimestamp());

        $repo = $this->createMock(LedRepositoryInterface::class);
        $repo->method('get')
            ->withAnyParameters()
            ->willReturn($led);

        $mapper = new LedDTOMapper();
        $response = new TestResponse();

        $command = new LedGetCommand($faker->uuid, $repo, $mapper, $response);
        $command->execute();

        $this->assertInstanceOf(LedDTO::class, $response->getData());
    }
}
