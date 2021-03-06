<?php


namespace App\Http\Controllers;


use App\Adapter\RamseyUuidAdapter;
use App\Commands\LedCreateCommand;
use App\Commands\LedDeleteCommand;
use App\Commands\LedGetCommand;
use App\Commands\LedUpdateCommand;
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

    /**
     * @param string $ledId
     * @param Request $request
     * @param LedRepositoryInterface $ledRepo
     * @return JsonResponse
     */
    public function update(string $ledId, Request $request, LedRepositoryInterface $ledRepo): JsonResponse
    {
        $responseAdapter = new IlluminateJsonResponseAdapter();
        $responseAdapter->setStatusCode(200);
        $requestAdapter = new IlluminateRequestAdapter($request);
        $command = new LedUpdateCommand($ledId, $requestAdapter, $ledRepo);
        try {
            $command->execute();
        } catch (ValidationException $exception) {
            $responseAdapter->setStatusCode(400);
            $responseAdapter->setData($exception->getMessage());
        }

        return $responseAdapter->getResponse();
    }

    /**
     * @param string $ledId
     * @param LedRepositoryInterface $ledRepo
     * @return JsonResponse
     */
    public function delete(string $ledId, LedRepositoryInterface $ledRepo): JsonResponse
    {
        $command = new LedDeleteCommand($ledId, $ledRepo);
        $command->execute();

        $responseAdapter = new IlluminateJsonResponseAdapter();
        $responseAdapter->setStatusCode(204);

        return $responseAdapter->getResponse();
    }
}
