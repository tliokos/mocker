<?php

namespace Mocker\Controller;

use Twig_Environment as View;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Mocker\{
    Service\Microservice,
    Service\Contract,
    Service\Relationship,
    StatusCode,
    Resource\Formatter as ResourceFormatter
};

class MicroserviceController
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
     * MicroserviceController constructor.
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
        $microservices = $this->microservice->list();
        $microservices = $this->resourceFormatter
            ->setTransformer('microservice.collection')->formatCollection($microservices);

        return new JsonResponse($microservices, StatusCode::OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $microserviceId = $this->microservice->create($data);
        $microservice = $this->resourceFormatter
            ->setTransformer('microservice.item')->formatItem(['id' => $microserviceId]);

        return new JsonResponse($microservice, StatusCode::CREATED);
    }

    /**
     * @param string $microserviceId
     * @return JsonResponse
     */
    public function delete(string $microserviceId) : JsonResponse
    {
        $contracts = $this->relationship->getContracts($microserviceId);
        foreach($contracts as $contractId) {
            $this->contract->delete($contractId);
            $this->relationship->removeContract($microserviceId, $contractId);
        }
        $this->microservice->delete($microserviceId);

        return new JsonResponse(null, StatusCode::NO_CONTENT);
    }
}