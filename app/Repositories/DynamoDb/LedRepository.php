<?php


namespace App\Repositories\DynamoDb;


use App\Exceptions\LedInvalidException;
use App\Exceptions\LedNotFoundException;
use App\Models\Led;
use App\Repositories\Entity\LedEntity;
use App\Repositories\LedRepositoryInterface;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

class LedRepository implements LedRepositoryInterface
{
    /** @var DynamoDbClient $client */
    private $client;

    /** @var Marshaler */
    private $marshaler;

    /**
     * LedRepository constructor.
     * @param DynamoDbClient $client
     * @param Marshaler $marshaler
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler)
    {
        $this->client = $client;
        $this->marshaler = $marshaler;
    }


    /**
     * {@inheritDoc}
     */
    public function all(): array
    {
        $result = $this->client->scan([
            'TableName' => LedEntity::TABLE_NAME
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
        $result = $this->client->getItem([
            'TableName' => LedEntity::TABLE_NAME,
            'Key' => $this->marshaler->marshalItem([
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
     * {@inheritDoc}
     */
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

        $entity = LedEntity::map($led);
        $item = $this->marshaler->marshalItem($entity->toArray());

        $this->client->putItem([
            'TableName' => LedEntity::TABLE_NAME,
            'Item' => $item
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function update(Led $led): void
    {
        try {
            $this->get($led->getId());
        } catch (LedNotFoundException $exception) {
            throw new LedInvalidException("The led not exists with ID {$led->getId()}");
        }

        $entity = LedEntity::map($led);
        $item = $this->marshaler->marshalItem($entity->toArray());

        $this->client->putItem([
            'TableName' => LedEntity::TABLE_NAME,
            'Item' => $item
        ]);
    }


    /**
     * @param array $item
     * @return Led
     */
    private function map(array $item): Led
    {
        $unmarshalItem = $this->marshaler->unmarshalItem($item);
        $ledEntity = LedEntity::fill($unmarshalItem);

        return new Led(
            $ledEntity->getId(),
            $ledEntity->getName(),
            $ledEntity->getLastUpdate()
        );
    }
}
