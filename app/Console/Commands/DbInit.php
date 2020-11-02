<?php


namespace App\Console\Commands;


use Aws\DynamoDb\Exception\DynamoDbException;
use BaoPham\DynamoDb\Facades\DynamoDb;
use DateTime;
use Faker\Factory;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class DbInit extends Command
{
    protected $signature = "db:init";

    protected $description = "Create database and populate data";

    public function handle()
    {
        try {
            DynamoDb::client()->deleteTable([
                'TableName' => 'led'
            ]);
        } catch (DynamoDbException $e) {
            // nothing
        }

        DynamoDb::client()->createTable([
            'TableName' => 'led',
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'id',
                    'AttributeType' => 'S',
                ]
            ],
            'KeySchema' => [
                [
                    'AttributeName' => 'id',
                    'KeyType' => 'HASH',
                ]
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 20,
                'OnDemand' => false,
            ],
        ]);

        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $led = [
                'id' => Uuid::uuid4()->toString(),
                'name' => $faker->name,
                'lastUpdate' => (new DateTime())->getTimestamp()
            ];

            DynamoDb::table('led')
                ->setItem(DynamoDb::marshalItem($led))
                ->prepare()
                ->putItem();
        }
    }
}
