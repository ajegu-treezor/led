<?php


namespace App\Models;


use Ramsey\Uuid\Uuid;

class Led
{
    private $id;
    private $name;
    private $lastUpdate;

    /**
     * Led constructor.
     * @param string $name
     * @param int $lastUpdate
     * @param null|string $id
     */
    public function __construct(string $name, int $lastUpdate, $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @return mixed|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

}
