<?php


namespace App\Repositories\DynamoDb;


use App\Exceptions\LedNotFoundException;
use App\Models\Led;
use App\Repositories\LedRepositoryInterface;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

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


    /**
     * {@inheritDoc}
     */
    public function all(): array
    {
        $result = $this->client->scan([
            'TableName' => 'led'
        ]);

        $leds = [];
        foreach ($result->get('Items') as $item) {
            $leds[] = $this->map($item);
        }

        return $leds;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $ledId): Led
    {
        $marshaller = new Marshaler();
        $result = $this->client->getItem([
            'TableName' => 'led',
            'Key' => $marshaller->marshalItem([
                'id' => $ledId
            ])
        ]);

        $item = $result->get('Item');

        if (null === $item) {
            throw new LedNotFoundException("Led could not be find with id {$ledId}");
        }

        return $this->map($item);
    }

    /**
     * @param array $item
     * @return Led
     */
    private function map(array $item): Led
    {
        return new Led(
            $item['name']['S'],
            $item['lastUpdate']['N'],
            $item['id']['S']
        );
    }
}
