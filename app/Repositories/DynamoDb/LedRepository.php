<?php


namespace App\Repositories\DynamoDb;


use App\Models\Led;
use App\Repositories\LedRepositoryInterface;
use Aws\DynamoDb\DynamoDbClient;

class LedRepository implements LedRepositoryInterface
{
    /** @var DynamoDbClient $client */
    private $client;

    /**
     * LedRepository constructor.
     * @param DynamoDbClient $client
     */
    public function __construct(DynamoDbClient $client)
    {
        $this->client = $client;
    }


    public function all(): array
    {
        $result = $this->client->scan([
            'TableName' => 'led'
        ]);

        $leds = [];
        foreach ($result->get('Items') as $data) {
            $leds[] = new Led(
                $data['name']['S'],
                $data['lastUpdate']['N'],
                $data['id']['S']
            );
        }

        return $leds;
    }

}
