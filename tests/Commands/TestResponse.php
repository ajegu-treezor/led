<?php


namespace Commands;


use App\Response\ResponseInterface;

class TestResponse implements ResponseInterface
{
    /** @var array */
    private $data;

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
