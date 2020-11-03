<?php


namespace Commands;


use App\Request\RequestInterface;

class TestRequest implements RequestInterface
{
    private $validate = false;
    private $data = [];

    public function getData(): array
    {
        return $this->data;
    }

    public function validate(): void
    {
        $this->validate = true;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isValidate(): bool
    {
        return $this->validate;
    }

}
