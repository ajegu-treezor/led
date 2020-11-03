<?php


namespace App\Console\Commands;


use App\Repositories\Entity\LedEntity;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Faker\Factory;
use Illuminate\Console\Command;

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
                'TableName' => LedEntity::TABLE_NAME,
            ]);
        } catch (DynamoDbException $e) {
            // nothing
        }

        $table = [
            'TableName' => LedEntity::TABLE_NAME,
            'AttributeDefinitions' => [
                [
                    'AttributeName' => LedEntity::PRIMARY_KEY,
                    'AttributeType' => 'S',
                ]
            ],
            'KeySchema' => [
                [
                    'AttributeName' => LedEntity::PRIMARY_KEY,
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
                $led = new LedEntity();
                $led->setId($faker->uuid)
                    ->setName($faker->name)
                    ->setLastUpdate($faker->dateTime->getTimestamp());

                $this->client->putItem([
                    'TableName' => 'led',
                    'Item' => $marshaller->marshalItem($led->toArray())
                ]);
            }
        }
    }
}
