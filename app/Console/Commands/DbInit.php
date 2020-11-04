<?php


namespace App\Console\Commands;


use App\Repositories\DynamoDb\EntityManager;
use App\Repositories\Entity\LedEntity;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Faker\Factory;
use Illuminate\Console\Command;

class DbInit extends Command
{
    protected $signature = "db:init {--seed}";

    protected $description = "Create database and populate data";

    /** @var DynamoDbClient $client */
    private $client;

    /** @var string */
    private $table;

    /** @var EntityManager  */
    private $manager;

    /**
     * DbInit constructor.
     * @param DynamoDbClient $client
     * @param EntityManager $manager
     */
    public function __construct(DynamoDbClient $client, EntityManager $manager)
    {
        parent::__construct();
        $this->client = $client;
        $this->table = getenv('DATABASE_NAME');
        $this->manager = $manager;
    }


    public function handle()
    {
        try {
            $this->client->deleteTable([
                'TableName' => $this->table,
            ]);
        } catch (DynamoDbException $e) {
            // nothing
        }

        $table = [
            'TableName' => $this->table,
            'AttributeDefinitions' => [
                [
                    'AttributeName' => EntityManager::PK,
                    'AttributeType' => 'S',
                ]
            ],
            'KeySchema' => [
                [
                    'AttributeName' => EntityManager::PK,
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
            for ($i = 0; $i < 10; $i++) {
                $led = new LedEntity();
                $led->setId($faker->uuid)
                    ->setName($faker->name)
                    ->setLastUpdate($faker->dateTime->getTimestamp());

                $this->manager->create($led);
            }
        }
    }
}
