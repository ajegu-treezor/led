<?php


namespace App\Response;


interface ResponseInterface
{
    public function setData(array $data): void;
}
