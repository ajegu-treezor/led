<?php


namespace App\Commands;


use App\Adapter\UuidAdapterInterface;
use App\Models\Led;
use App\Repositories\LedRepositoryInterface;
use App\Request\RequestInterface;
use DateTime;

class LedCreateCommand implements Command
{
    /** @var RequestInterface */
    private $request;

    /** @var LedRepositoryInterface */
    private $ledRepo;

    /** @var UuidAdapterInterface */
    private $uuidAdapter;

    /**
     * LedCreateCommand constructor.
     * @param RequestInterface $request
     * @param LedRepositoryInterface $ledRepo
     * @param UuidAdapterInterface $uuidAdapter
     */
    public function __construct(RequestInterface $request, LedRepositoryInterface $ledRepo, UuidAdapterInterface $uuidAdapter)
    {
        $this->request = $request;
        $this->ledRepo = $ledRepo;
        $this->uuidAdapter = $uuidAdapter;
    }


    /**
     * @throws \App\Exceptions\LedInvalidException
     * @throws \App\Exceptions\ValidationException
     */
    public function execute(): void
    {
        $this->request->validate();

        $data = $this->request->getData();
        $led = new Led(
            $this->uuidAdapter->generateUuid(),
            $data['name'],
            (new DateTime())->getTimestamp()
        );

        $this->ledRepo->create($led);
    }

}
