<?php


namespace App\Adapter;


use Ramsey\Uuid\Uuid;

class RamseyUuidAdapter implements UuidAdapterInterface
{
    public function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }

}
