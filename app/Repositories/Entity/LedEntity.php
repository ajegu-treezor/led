<?php


namespace App\Repositories\Entity;


use App\Models\Led;

class LedEntity extends Entity
{
    const TABLE_NAME = 'led';
    const PRIMARY_KEY = 'id';

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var int */
    private $lastUpdate;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return LedEntity
     */
    public function setId(string $id): LedEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return LedEntity
     */
    public function setName(string $name): LedEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastUpdate(): int
    {
        return $this->lastUpdate;
    }

    /**
     * @param int $lastUpdate
     * @return LedEntity
     */
    public function setLastUpdate(int $lastUpdate): LedEntity
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    public static function map(Led $led): self
    {
        $entity = new static();
        $entity->setId($led->getId())
            ->setName($led->getName())
            ->setLastUpdate($led->getLastUpdate());

        return $entity;
    }
}
