<?php


namespace App\Request;


use App\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IlluminateRequestAdapter implements RequestInterface
{

    /** @var Request */
    private $request;

    /**
     * IlluminateRequestAdapter constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function getData(): array
    {
        return $this->request->all();
    }

    public function validate(): void
    {
        $validator = Validator::make(
            $this->getData(),
            [
                'name' => 'required'
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
    }


}
