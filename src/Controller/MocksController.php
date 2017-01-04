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
     * @param $contractUrl
     * @return JsonResponse
     * @throws \Exception
     */
    public function handle(Request $request, $microservice, $contractUrl) : JsonResponse
    {
        $contractId = md5($microservice . $request->getMethod() . $contractUrl);
        $contract = $this->contract->get($contractId);
        if(!$contract) {
            throw new \Exception(
                sprintf('The contract %s::%s doesn\'t exist', $request->getMethod(), $contractUrl),
                StatusCode::NOT_FOUND);
        }

        return new JsonResponse(json_decode($contract['response']), $contract['code']);
    }
}