<?php


namespace App\Console\Commands;


use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use DateTime;
use Faker\Factory;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class DbInit extends Command
{
    protected $signature = "db:init {--seed}";

    protected $description = "Create database and populate data";

    /** @var DynamoDbClient $client */
    private $client;

    /**
     * DbInit constructor.
     * @param DynamoDbClient $client
     */
    public function __construct(DynamoDbClient $client)
    {
        parent::__construct();
        $this->client = $client;
    }


    public function handle()
    {
        try {
            $this->client->deleteTable([
                'TableName' => 'led',
            ]);
        } catch (DynamoDbException $e) {
            // nothing
        }

        $table = [
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
        ];
        $this->client->createTable($table);

        if ($this->option('seed')) {
            $faker = Factory::create();
            $marshaller = new Marshaler();
            for ($i = 0; $i < 10; $i++) {
                $led = [
                    'id' => Uuid::uuid4()->toString(),
                    'name' => $faker->name,
                    'lastUpdate' => (new DateTime())->getTimestamp()
                ];

                $this->client->putItem([
                    'TableName' => 'led',
                    'Item' => $marshaller->marshalItem($led)
                ]);
            }
        }
    }
}
