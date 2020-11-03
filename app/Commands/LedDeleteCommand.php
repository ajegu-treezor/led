<?php


namespace App\Commands;


use App\Exceptions\LedNotFoundException;
use App\Repositories\LedRepositoryInterface;

class LedDeleteCommand implements Command
{
    /** @var string */
    private $ledId;

    /** @var LedRepositoryInterface */
    private $ledRepo;

    /**
     * LedDeleteCommand constructor.
     * @param string $ledId
     * @param LedRepositoryInterface $ledRepo
     */
    public function __construct(string $ledId, LedRepositoryInterface $ledRepo)
    {
        $this->ledId = $ledId;
        $this->ledRepo = $ledRepo;
    }


    public function execute(): void
    {
        $ledExists = true;
        try {
            $this->ledRepo->get($this->ledId);
        } catch (LedNotFoundException $exception) {
            $ledExists = false;
        }

        if ($ledExists) {
            $this->ledRepo->delete($this->ledId);
        }
    }

}
