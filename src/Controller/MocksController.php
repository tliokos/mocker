<?php

namespace Mocker\Controller;

use Mocker\{
    StatusCode,
    Service\Contract,
    Storage\Contract as ContractStorage
};
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
     * @param $url
     * @return JsonResponse
     * @throws \Exception
     */
    public function handle(Request $request, $microservice, $url) : JsonResponse
    {
        $url = urldecode($request->getQueryString() ? sprintf('%s?%s', $url, $request->getQueryString()) : $url);
        $contractId = ContractStorage::getId($microservice, $request->getMethod(), $url);
        $contract = $this->contract->get($contractId);
        if(!$contract) {
            throw new \Exception(
                sprintf('The contract %s::%s/%s doesn\'t exist', $request->getMethod(), $microservice, $url),
                StatusCode::NOT_FOUND);
        }

        return new JsonResponse(json_decode($contract['response']), $contract['code']);
    }
}