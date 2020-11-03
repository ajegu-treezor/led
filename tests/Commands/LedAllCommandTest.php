<?php


namespace Commands;


use App\Commands\LedAllCommand;
use App\Models\Led;
use App\Repositories\LedRepositoryInterface;
use App\Response\DTO\LedDTO;
use App\Response\Mapper\LedDTOMapper;
use Faker\Factory;
use TestCase;

class LedAllCommandTest extends TestCase
{
    public function testLedAllCommandShouldBeOk()
    {
        $faker = Factory::create();

        $led = new Led($faker->uuid, $faker->name, $faker->dateTime->getTimestamp());

        $repo = $this->createMock(LedRepositoryInterface::class);
        $repo->method('all')
            ->willReturn([$led]);

        $mapper = new LedDTOMapper();
        $response = new TestResponse();

        $command = new LedAllCommand($repo, $response, $mapper);
        $command->execute();

        $this->assertNotEmpty($response->getData());
        foreach ($response->getData() as $dto) {
            $this->assertInstanceOf(LedDTO::class, $dto);
        }
    }
}
