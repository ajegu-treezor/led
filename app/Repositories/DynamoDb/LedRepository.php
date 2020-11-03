<?php


namespace App\Repositories\DynamoDb;


use App\Exceptions\LedInvalidException;
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

    public function create(Led $led): void
    {
        $ledExists = true;
        try {
            $this->get($led->getId());
        } catch (LedNotFoundException $exception) {
            $ledExists = false;
        }

        if ($ledExists) {
            throw new LedInvalidException("The led already exists with ID {$led->getId()}");
        }

        $marshaller = new Marshaler();
        $this->client->putItem([
            'TableName' => 'led',
            'Item' => $marshaller->marshalItem($led->toArray())
        ]);
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
