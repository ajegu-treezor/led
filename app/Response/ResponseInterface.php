<?php


namespace App\Response;


interface ResponseInterface
{
    /**
     * @param mixed $data
     */
    public function setData($data): void;
}
