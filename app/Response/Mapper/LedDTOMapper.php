<?php


namespace App\Response\Mapper;


use App\Models\Led;
use App\Response\DTO\LedDTO;

class LedDTOMapper
{
    public function map(Led $led): LedDTO
    {
        $dto = new LedDTO();
        $dto->id = $led->getId();
        $dto->name = $led->getName();
        $dto->lastUpdate = $led->getLastUpdate();

        return $dto;
    }
}
