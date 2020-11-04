<?php


namespace App\Repositories\Mapper;


use App\Models\Led;
use App\Repositories\Entity\LedEntity;

class LedEntityMapper
{
    public function mapToModel(LedEntity $entity): Led
    {
        return new Led(
            $entity->getId(),
            $entity->getName(),
            $entity->getLastUpdate()
        );
    }

    public function mapToEntity(Led $led): LedEntity
    {
        $entity = new LedEntity();
        $entity->setId($led->getId())
            ->setName($led->getName())
            ->setLastUpdate($led->getLastUpdate());

        return $entity;
    }
}
