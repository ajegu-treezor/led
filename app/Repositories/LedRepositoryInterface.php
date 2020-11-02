<?php


namespace App\Repositories;


use App\Models\Led;

interface LedRepositoryInterface
{
    /**
     * @return Led[]
     */
    public function all(): array;
}
