<?php


namespace App\Request;


use App\Exceptions\ValidationException;

interface RequestInterface
{
    public function getData(): array;

    /**
     * @throws ValidationException
     */
    public function validate(): void;
}
