<?php


namespace App\Models;


class Led
{
    /** @var string  */
    private $id;

    /** @var string  */
    private $name;

    /** @var int  */
    private $lastUpdate;

    /**
     * Led constructor.
     * @param string $id
     * @param string $name
     * @param int $lastUpdate
     */
    public function __construct(string $id, string $name, int $lastUpdate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getLastUpdate(): int
    {
        return $this->lastUpdate;
    }
}
