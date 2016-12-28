<?php

namespace Mocker\Controller;

use Mocker\{StatusCode, Service\Contract};
use Symfony\Component\HttpFoundation\{Request, JsonResponse};

class MocksController
{
    /**
     * @var Contract
     */
    private $contract;

    /**
     * MocksController constructor.
     * @param Contract $contract
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * @param Request $request
     * @param $contractId
     * @return JsonResponse
     * @throws \Exception
     */
    public function handle(Request $request, $contractId) : JsonResponse
    {
        $contract = $this->contract->get($contractId);
        if($request->getMethod() !== $contract['method']) {
            throw new \Exception(
                sprintf('The contract you\'re trying to access doesn\'t exist'),
                StatusCode::NOT_FOUND);
        }

        return new JsonResponse(json_decode($contract['response']), $contract['code']);
    }
}