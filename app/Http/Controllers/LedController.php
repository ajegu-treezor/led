<?php


namespace App\Http\Controllers;


use App\Adapter\RamseyUuidAdapter;
use App\Commands\LedCreateCommand;
use App\Commands\LedGetCommand;
use App\Exceptions\ValidationException;
use App\Request\IlluminateRequestAdapter;
use App\Response\IlluminateJsonResponseAdapter;
use App\Commands\LedAllCommand;
use App\Repositories\LedRepositoryInterface;
use App\Response\Mapper\LedDTOMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class LedController extends Controller
{
    /**
     * @param LedRepositoryInterface $ledRepo
     * @param LedDTOMapper $ledMapper
     * @return JsonResponse
     */
    public function getAll(LedRepositoryInterface $ledRepo, LedDTOMapper $ledMapper): JsonResponse
    {
        $responseAdapter = new IlluminateJsonResponseAdapter();
        $command = new LedAllCommand($ledRepo, $ledMapper, $responseAdapter);
        $command->execute();

        return $responseAdapter->getResponse();
    }

    /**
     * @param string $ledId
     * @param LedRepositoryInterface $ledRepo
     * @param LedDTOMapper $ledMapper
     * @return JsonResponse
     */
    public function get(string $ledId, LedRepositoryInterface $ledRepo, LedDTOMapper $ledMapper): JsonResponse
    {
        $responseAdapter = new IlluminateJsonResponseAdapter();
        $command = new LedGetCommand($ledId, $ledRepo, $ledMapper, $responseAdapter);
        $command->execute();

        return $responseAdapter->getResponse();
    }

    /**
     * @param Request $request
     * @param LedRepositoryInterface $ledRepo
     * @return JsonResponse
     * @throws \App\Exceptions\LedInvalidException
     */
    public function create(Request $request, LedRepositoryInterface $ledRepo): JsonResponse
    {
        $responseAdapter = new IlluminateJsonResponseAdapter();
        $responseAdapter->setStatusCode(201);
        $requestAdapter = new IlluminateRequestAdapter($request);
        $uuidAdapter = new RamseyUuidAdapter();
        $command = new LedCreateCommand($requestAdapter, $ledRepo, $uuidAdapter);
        try {
            $command->execute();
        } catch (ValidationException $exception) {
            $responseAdapter->setStatusCode(400);
            $responseAdapter->setData($exception->getMessage());
        }

        return $responseAdapter->getResponse();
    }

}
