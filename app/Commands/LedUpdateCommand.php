<?php


namespace App\Commands;


use App\Exceptions\LedNotFoundException;
use App\Exceptions\ValidationException;
use App\Models\Led;
use App\Repositories\LedRepositoryInterface;
use App\Request\RequestInterface;
use DateTime;

class LedUpdateCommand implements Command
{
    /** @var string */
    private $ledId;

    /** @var RequestInterface */
    private $request;

    /** @var LedRepositoryInterface */
    private $ledRepo;

    /**
     * LedUpdateCommand constructor.
     * @param string $ledId
     * @param RequestInterface $request
     * @param LedRepositoryInterface $ledRepo
     */
    public function __construct(string $ledId, RequestInterface $request, LedRepositoryInterface $ledRepo)
    {
        $this->ledId = $ledId;
        $this->request = $request;
        $this->ledRepo = $ledRepo;
    }


    public function execute(): void
    {
        $this->request->validate();

        try {
            $this->ledRepo->get($this->ledId);
        } catch (LedNotFoundException $exception) {
            throw new ValidationException("Led not exists.");
        }

        $data = $this->request->getData();
        $led = new Led(
            $this->ledId,
            $data['name'],
            (new DateTime())->getTimestamp()
        );

        $this->ledRepo->update($led);
    }

}
