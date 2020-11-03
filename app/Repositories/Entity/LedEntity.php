<?php


namespace App\Repositories\Entity;

class LedEntity implements Entity
{
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
}
