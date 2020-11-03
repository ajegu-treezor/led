<?php


namespace App\Commands;


use App\Models\Led;
use App\Repositories\LedRepositoryInterface;
use App\Response\Mapper\LedDTOMapper;
use App\Response\ResponseInterface;

class LedAllCommand implements Command
{
    /** @var LedRepositoryInterface */
    private $ledRepo;

    /** @var LedDTOMapper */
    private $ledMapper;

    /** @var ResponseInterface */
    private $response;

    /**
     * LedAllCommand constructor.
     * @param LedRepositoryInterface $ledRepo
     * @param ResponseInterface $response
     * @param LedDTOMapper $ledMapper
     */
    public function __construct(LedRepositoryInterface $ledRepo, LedDTOMapper $ledMapper, ResponseInterface $response)
    {
        $this->ledRepo = $ledRepo;
        $this->ledMapper = $ledMapper;
        $this->response = $response;
    }


    public function execute(): void
    {
        $leds = $this->ledRepo->all();
        $result = array_map(function(Led $led) {
            return $this->ledMapper->map($led);
        }, $leds);
        $this->response->setData($result);
    }

}
