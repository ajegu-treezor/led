<?php


namespace App\Repositories;


use App\Exceptions\LedNotFoundException;
use App\Models\Led;

interface LedRepositoryInterface
{
    /**
     * @return Led[]
     */
    public function all(): array;

    /**
     * @param string $ledId
     * @return Led
     * @throws LedNotFoundException
     */
    public function get(string $ledId): Led;
}
