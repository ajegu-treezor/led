<?php


namespace Commands;

use App\Commands\LedUpdateCommand;
use App\Exceptions\LedNotFoundException;
use App\Exceptions\ValidationException;
use App\Models\Led;
use App\Repositories\LedRepositoryInterface;
use Faker\Factory;
use TestCase;

class LedUpdateCommandTest extends TestCase
{
    public function testLedUpdateCommandShouldBeOk()
    {
        $faker = Factory::create();

        $request = new TestRequest();
        $request->setData(['name' => $faker->name]);

        $repo = $this->createMock(LedRepositoryInterface::class);

        $led = $this->createMock(Led::class);
        $repo->method('get')
            ->willReturn($led);

        $repo->expects($this->once())
            ->method('update');

        $command = new LedUpdateCommand($faker->uuid, $request, $repo);
        $command->execute();

        $this->assertTrue($request->isValidate());
    }

    public function testLedUpdateCommandWithUnknownLedShouldThrowException()
    {
        $faker = Factory::create();

        $request = new TestRequest();
        $request->setData(['name' => $faker->name]);

        $repo = $this->createMock(LedRepositoryInterface::class);

        $repo->method('get')
            ->willThrowException(new LedNotFoundException());

        $repo->expects($this->never())
            ->method('update');

        $command = new LedUpdateCommand($faker->uuid, $request, $repo);

        $thrException = false;
        try {
            $command->execute();
        } catch (ValidationException $exception) {
            $thrException = true;
        }

        $this->assertTrue($thrException, "Update an unknown led should throw an exception.");
    }
}
