<?php


namespace Commands;

use App\Adapter\RamseyUuidAdapter;
use App\Commands\LedCreateCommand;
use App\Repositories\LedRepositoryInterface;
use Faker\Factory;
use TestCase;

class LedCreateCommandTest extends TestCase
{
    public function testLedCreateCommandShouldBeOk()
    {
        $faker = Factory::create();

        $request = new TestRequest();
        $request->setData(['name' => $faker->name]);

        $repo = $this->createMock(LedRepositoryInterface::class);

        $repo->expects($this->once())
            ->method('create');

        $uuidAdapter = new RamseyUuidAdapter();

        $command = new LedCreateCommand($request, $repo, $uuidAdapter);
        $command->execute();

        $this->assertTrue($request->isValidate());


    }
}
