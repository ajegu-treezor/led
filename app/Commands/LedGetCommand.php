<?php


namespace App\Commands;


use App\Repositories\LedRepositoryInterface;
use App\Response\Mapper\LedDTOMapper;
use App\Response\ResponseInterface;

class LedGetCommand implements Command
{
    /** @var string */
    private $ledId;

    /** @var LedRepositoryInterface */
    private $ledRepo;

    /** @var LedDTOMapper */
    private $ledMapper;

    /** @var ResponseInterface */
    private $response;

    /**
     * LedGetCommand constructor.
     * @param string $ledId
     * @param LedRepositoryInterface $ledRepo
     * @param LedDTOMapper $ledMapper
     * @param ResponseInterface $response
     */
    public function __construct(string $ledId, LedRepositoryInterface $ledRepo, LedDTOMapper $ledMapper, ResponseInterface $response)
    {
        $this->ledId = $ledId;
        $this->ledRepo = $ledRepo;
        $this->ledMapper = $ledMapper;
        $this->response = $response;
    }


    public function execute(): void
    {
        $led = $this->ledRepo->get($this->ledId);
        $this->response->setData($this->ledMapper->map($led));
    }

}
