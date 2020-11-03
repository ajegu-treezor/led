<?php


namespace Commands;

use App\Commands\LedDeleteCommand;
use App\Exceptions\LedNotFoundException;
use App\Models\Led;
use App\Repositories\LedRepositoryInterface;
use Faker\Factory;
use TestCase;

class LedDeleteCommandTest extends TestCase
{
    public function testLedDeleteCommandShouldBeOk()
    {
        $faker = Factory::create();

        $led = $this->createMock(Led::class);
        $repo = $this->createMock(LedRepositoryInterface::class);

        $repo->method('get')
            ->willReturn($led);

        $repo->expects($this->once())
            ->method('delete');

        $command = new LedDeleteCommand($faker->uuid, $repo);
        $command->execute();
    }

    public function testLedDeleteCommandWithUnknownLedShouldBeOk()
    {
        $faker = Factory::create();

        $repo = $this->createMock(LedRepositoryInterface::class);

        $repo->method('get')
            ->willThrowException(new LedNotFoundException());

        $repo->expects($this->never())
            ->method('delete');

        $command = new LedDeleteCommand($faker->uuid, $repo);
        $command->execute();
    }
}
