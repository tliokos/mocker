<?php

namespace Mocker\Controller;

use Twig_Environment as View;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Mocker\{
    Service\Microservice,
    Service\Contract,
    Service\Relationship,
    Resource\Formatter as ResourceFormatter,
    StatusCode
};

class ContractController
{
    /**
     * @var Microservice
     */
    private $microservice;

    /**
     * @var Contract
     */
    private $contract;

    /**
     * @var Relationship
     */
    private $relationship;

    /**
     * @var ResourceFormatter
     */
    private $resourceFormatter;

    /**
     * @var View
     */
    private $view;

    /**
     * ContractController constructor.
     * @param Microservice $microservice
     * @param Contract $contract
     * @param Relationship $relationship
     * @param ResourceFormatter $resourceFormatter
     * @param View $view
     */
    public function __construct(
        Microservice $microservice,
        Contract $contract,
        Relationship $relationship,
        ResourceFormatter $resourceFormatter,
        View $view
    )
    {
        $this->microservice = $microservice;
        $this->contract = $contract;
        $this->relationship = $relationship;
        $this->resourceFormatter = $resourceFormatter;
        $this->view = $view;
    }

    /**
     * @return JsonResponse
     */
    public function list() : JsonResponse
    {
        $contracts = $this->contract->list();
        $contracts = $this->resourceFormatter
            ->setTransformer('contract.collection')->formatCollection($contracts);

        return new JsonResponse($contracts, StatusCode::OK);
    }

    public function get(string $contractId) : JsonResponse
    {
        $contract = $this->contract->get($contractId);
        $contract = $this->resourceFormatter
            ->setTransformer('contract.item')->formatItem($contract);

        return new JsonResponse($contract, StatusCode::OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $contractId = $this->contract->create($data);
        $this->relationship->addContract($data['microservice']['id'], $contractId);
        $this->microservice->updateContractsCounter($data['microservice']['id'], 1);
        $contract = $this->resourceFormatter
            ->setTransformer('contract.id')->formatItem(['id' => $contractId]);

        return new JsonResponse($contract, StatusCode::CREATED);
    }

    /**
     * @param Request $request
     * @param $contractId
     * @return JsonResponse
     */
    public function update(Request $request, $contractId) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->contract->update($contractId, $data);

        return new JsonResponse(null, StatusCode::NO_CONTENT);
    }

    /**
     * @param string $contractId
     * @return JsonResponse
     */
    public function delete(string $contractId) : JsonResponse
    {
        $contract = $this->contract->get($contractId);
        $microservice = json_decode($contract['microservice'], true);
        $this->relationship->removeContract($microservice['id'], $contractId);
        $this->microservice->updateContractsCounter($microservice['id'], -1);
        $this->contract->delete($contractId);

        return new JsonResponse(null, StatusCode::NO_CONTENT);
    }
}