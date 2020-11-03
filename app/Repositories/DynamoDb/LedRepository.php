<?php


namespace App\Repositories\DynamoDb;


use App\Exceptions\EntityNotFoundException;
use App\Exceptions\EntitySerializeException;
use App\Exceptions\LedInvalidException;
use App\Exceptions\LedNotFoundException;
use App\Models\Led;
use App\Repositories\Entity\LedEntity;
use App\Repositories\LedRepositoryInterface;
use App\Repositories\Mapper\LedEntityMapper;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

class LedRepository implements LedRepositoryInterface
{
    /** @var EntityManager */
    private $manager;

    /** @var LedEntityMapper */
    private $ledMapper;

    /** @var Serializer */
    protected $serializer;

    /**
     * LedRepository constructor.
     * @param EntityManager $manager
     * @param LedEntityMapper $ledMapper
     * @param Serializer $serializer
     */
    public function __construct(EntityManager $manager, LedEntityMapper $ledMapper, Serializer $serializer)
    {
        $this->manager = $manager;
        $this->ledMapper = $ledMapper;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function all(): array
    {
        $items = $this->manager->scan();
        return array_map(function($item) {
            $model = null;
            try {
                $model = $this->ledMapper->mapToModel($this->map($item));
            } catch (LedInvalidException $exception) {
                // log error
            }
            return $model;
        }, $items);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $ledId): Led
    {
        try {
            $item = $this->manager->getItem($ledId);
        } catch (EntityNotFoundException $exception) {
            throw new LedNotFoundException("Led could not be find with id {$ledId}");
        }

        try {
            $entity = $this->map($item);
        } catch (LedInvalidException $e) {
            throw new LedNotFoundException($e->getMessage());
        }

        return $this->ledMapper->mapToModel($entity);
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

        $entity = $this->ledMapper->mapToEntity($led);
        try {
            $this->manager->create($entity);
        } catch (EntitySerializeException $e) {
            throw new LedInvalidException($e->getMessage());
        }
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

        $entity = $this->ledMapper->mapToEntity($led);

        try {
            $this->manager->update($entity);
        } catch (EntitySerializeException $e) {
            throw new LedInvalidException($e->getMessage());
        }

    }

    public function delete(string $ledId): void
    {
        try {
            $this->get($ledId);
        } catch (LedNotFoundException $exception) {
            throw new LedInvalidException("The led not exists with ID {$ledId}");
        }

        $this->manager->delete($ledId);
    }

    /**
     * @param array $item
     * @return LedEntity
     * @throws LedInvalidException
     */
    private function map(array $item): LedEntity
    {
        try {
            return $this->serializer->denormalize($item, LedEntity::class, 'array');
        } catch (ExceptionInterface $e) {
            throw new LedInvalidException($e->getMessage());
        }
    }
}
