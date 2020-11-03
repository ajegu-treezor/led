<?php


namespace Commands;


use App\Response\ResponseInterface;

class TestResponse implements ResponseInterface
{
    /** @var mixed */
    private $data;

    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

}
