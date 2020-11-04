<?php


namespace App\Repositories\DynamoDb;


use App\Exceptions\EntityNotFoundException;
use App\Exceptions\EntitySerializeException;
use App\Repositories\Entity\Entity;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

class EntityManager
{
    const PK = 'id';

    /** @var string */
    private $table;

    /** @var DynamoDbClient $client */
    private $client;

    /** @var Marshaler */
    private $marshaler;

    /** @var Serializer */
    private $serializer;

    /**
     * EntityManager constructor.
     * @param DynamoDbClient $client
     * @param Marshaler $marshaler
     * @param Serializer $serializer
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, Serializer $serializer)
    {
        $this->client = $client;
        $this->marshaler = $marshaler;
        $this->serializer = $serializer;
        $this->table = getenv('DATABASE_NAME');
    }

    public function scan(): array
    {
        $result = $this->client->scan([
            'TableName' => $this->table
        ]);

        return array_map(function($item) {
            return $this->marshaler->unmarshalItem($item);
        }, $result->get('Items'));
    }

    /**
     * @param $itemId
     * @return array|null
     * @throws EntityNotFoundException
     */
    public function getItem($itemId): ?array
    {
        $result = $this->client->getItem([
            'TableName' => $this->table,
            'Key' => $this->marshaler->marshalItem([
                self::PK => $itemId
            ])
        ]);

        $item = $result->get('Item');

        if (null === $item) {
            throw new EntityNotFoundException("Item not found with ID {$itemId}.");
        }

        return $this->marshaler->unmarshalItem($item);
    }

    /**
     * @param Entity $entity
     * @throws EntitySerializeException
     */
    public function create(Entity $entity): void
    {
        try {
            $data = $this->serializer->normalize($entity, 'array');
        } catch (ExceptionInterface $e) {
            throw new EntitySerializeException($e->getMessage());
        }
        $item = $this->marshaler->marshalItem($data);

        $this->client->putItem([
            'TableName' => $this->table,
            'Item' => $item
        ]);
    }

    /**
     * @param Entity $entity
     * @throws EntitySerializeException
     */
    public function update(Entity $entity): void
    {
        try {
            $data = $this->serializer->normalize($entity, 'array');
        } catch (ExceptionInterface $e) {
            throw new EntitySerializeException($e->getMessage());
        }
        $item = $this->marshaler->marshalItem($data);

        $this->client->putItem([
            'TableName' => $this->table,
            'Item' => $item
        ]);
    }

    /**
     * @param $entityId
     */
    public function delete($entityId): void
    {
        $this->client->deleteItem([
            'TableName' => $this->table,
            'Key' => $this->marshaler->marshalItem([
                'id' => $entityId
            ])
        ]);
    }
}
