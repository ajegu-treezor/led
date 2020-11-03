<?php


namespace App\Http\Controllers;


use App\Response\IlluminateJsonResponseAdapter;
use App\Commands\LedAllCommand;
use App\Repositories\LedRepositoryInterface;
use App\Response\Mapper\LedDTOMapper;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;

class LedController extends Controller
{
    public function getAll(LedRepositoryInterface $ledRepo, LedDTOMapper $ledMapper): JsonResponse
    {
        $responseAdapter = new IlluminateJsonResponseAdapter();
        $command = new LedAllCommand($ledRepo, $ledMapper, $responseAdapter);
        $command->execute();

        return $responseAdapter->getResponse();
    }
}
