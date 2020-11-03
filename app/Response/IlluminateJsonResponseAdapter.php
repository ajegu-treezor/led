<?php


namespace App\Response;

use Illuminate\Http\JsonResponse;

class IlluminateJsonResponseAdapter implements ResponseInterface
{
    /** @var JsonResponse */
    private $response;

    /**
     * IlluminateJsonResponseAdapter constructor.
     */
    public function __construct()
    {
        $this->response = new JsonResponse();
    }


    public function setData($data): void
    {
        $this->response->setData($data);
    }

    public function setStatusCode(int $code): void
    {
        $this->response->setStatusCode($code);
    }


    public function getResponse(): JsonResponse
    {
        return $this->response;
    }
}
