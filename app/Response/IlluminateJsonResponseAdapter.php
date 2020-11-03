<?php


namespace App\Response;

use Illuminate\Http\JsonResponse;

class IlluminateJsonResponseAdapter implements ResponseInterface
{
    /** @var mixed */
    private $data;

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getResponse(): JsonResponse
    {
        return new JsonResponse($this->data);
    }
}
