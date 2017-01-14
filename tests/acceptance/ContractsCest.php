<?php

namespace Mocker\Test\Acceptance;

use AcceptanceTester;
use Codeception\Example;
use Mocker\Storage\{Microservice as MicroserviceStorage, Contract as ContractStorage};

class ContractsCest
{
    private $microservice = [
        'name' => 'microservice-name',
        'description' => 'microservice-description'
    ];

    private $contract = [
        'method' => 'POST',
        'url' => 'examples',
        'headers' => [
            'Authorization' => 'Bearer #',
            'Content-Type' => 'application/json'
        ],
        'request' => '[{"name":"example-name"},{"description":"example-description"}]',
        'response' => '[{"id":1}]',
        'code' => '200'
    ];

    private $microserviceId;

    private $microserviceHash;

    public function _before()
    {
        $this->microserviceId = md5($this->microservice['name']);
        $this->microserviceHash = sprintf(MicroserviceStorage::MICROSERVICES_KEY, $this->microserviceId);
    }

    public function createContractWithValidData(AcceptanceTester $I)
    {
        $I->wantTo("Create a Contract with valid data");
        $I->haveInRedis('hash', $this->microserviceHash, $this->microservice);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/mocker-api/contracts', array_merge([
            'microservice' => [
                'id' => $this->microserviceId,
                'name' => $this->microservice['name']
            ]], $this->contract));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED);
        $I->seeResponseIsJson();
        $contractId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $contractHash = sprintf(ContractStorage::CONTRACTS_KEY, $contractId);
        $I->seeInRedis($contractHash);
        $I->seeRedisKeyContains($contractHash, 'microservice', json_encode([
            'id' => $this->microserviceId,
            'name' => $this->microservice['name']
        ]));
        $I->seeRedisKeyContains($contractHash, 'method', $this->contract['method']);
        $I->seeRedisKeyContains($contractHash, 'url', $this->contract['url']);
        $I->seeRedisKeyContains($contractHash, 'headers', json_encode($this->contract['headers']));
        $I->seeRedisKeyContains($contractHash, 'request', $this->contract['request']);
        $I->seeRedisKeyContains($contractHash, 'response', $this->contract['response']);
        $I->seeRedisKeyContains($contractHash, 'code', $this->contract['code']);
    }

    public function updateContractWithValidData(AcceptanceTester $I)
    {
        $I->wantTo("Update a Contract with valid data");
        $I->haveInRedis('hash', $this->microserviceHash, $this->microservice);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/mocker-api/contracts', array_merge([
            'microservice' => [
                'id' => $this->microserviceId,
                'name' => $this->microservice['name']
            ]], $this->contract));
        $contractId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $contractHash = sprintf(ContractStorage::CONTRACTS_KEY, $contractId);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/mocker-api/contracts/' . $contractId, [
            'microservice' => [
                'id' => $this->microserviceId,
                'name' => $this->microservice['name']
            ],
            'method' => 'DELETE',
            'url' => 'examples/1',
            'headers' => [
                'Authorization' => 'Basic #',
                'Content-Type' => 'text/html'
            ],
            'request' => null,
            'response' => null,
            'code' => '204'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT);
        $I->seeResponseEquals(null);
        $I->seeRedisKeyContains($contractHash, 'method', 'DELETE');
        $I->seeRedisKeyContains($contractHash, 'url', 'examples/1');
        $I->seeRedisKeyContains($contractHash, 'headers', json_encode([
            'Authorization' => 'Basic #',
            'Content-Type' => 'text/html'
        ]));
        $I->seeRedisKeyContains($contractHash, 'request', null);
        $I->seeRedisKeyContains($contractHash, 'response', null);
        $I->seeRedisKeyContains($contractHash, 'code', '204');
    }

    /**
     * @dataprovider contractsProvider
     */
    public function createContractWithMissingData(AcceptanceTester $I, Example $contract)
    {
        $I->wantTo("Create a Contract with Missing Data");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/mocker-api/contracts', [
            'microservice' => $contract['microservice'],
            'method' => $contract['method'],
            'url' => $contract['url'],
            'headers' => $contract['headers'],
            'request' => $contract['request'],
            'response' => $contract['response'],
            'code' => $contract['code'],
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @dataprovider contractsProvider
     */
    public function updateContractWithMissingData(AcceptanceTester $I, Example $contract)
    {
        $I->wantTo("Update a Contract with Missing Data");
        $I->haveInRedis('hash', $this->microserviceHash, $this->microservice);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/mocker-api/contracts', array_merge([
            'microservice' => [
                'id' => $this->microserviceId,
                'name' => $this->microservice['name']
            ]], $this->contract));
        $contractId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/mocker-api/contracts/' . $contractId, [
            'microservice' => $contract['microservice'],
            'method' => $contract['method'],
            'url' => $contract['url'],
            'headers' => $contract['headers'],
            'request' => $contract['request'],
            'response' => $contract['response'],
            'code' => $contract['code'],
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNPROCESSABLE_ENTITY);
    }

    public function getNotDecodedContractDetails(AcceptanceTester $I)
    {
        $I->wantTo("Get Contract Details");
        $I->haveInRedis('hash', $this->microserviceHash, $this->microservice);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/mocker-api/contracts', array_merge([
            'microservice' => [
                'id' => $this->microserviceId,
                'name' => $this->microservice['name']
            ]], $this->contract));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED);
        $I->seeResponseIsJson();
        $contractId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $I->sendGET('/mocker-api/contracts/' . $contractId);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->assertEquals('17a57f651d2a2346c080690d172c6fb1', $I->grabDataFromResponseByJsonPath('$.data.id')[0]);
        $I->assertEquals([
            "id" => "1465d24108f608c5c2ace4d0725190a7",
            "name" => "microservice-name"
        ], $I->grabDataFromResponseByJsonPath('$.data.microservice')[0]);
        $I->assertEquals([
            "Authorization" => "Bearer #",
            "Content-Type" => "application/json"
        ], $I->grabDataFromResponseByJsonPath('$.data.headers')[0]);
        $I->assertEquals('POST', $I->grabDataFromResponseByJsonPath('$.data.method')[0]);
        $I->assertEquals('examples', $I->grabDataFromResponseByJsonPath('$.data.url')[0]);
        $I->assertEquals(200, $I->grabDataFromResponseByJsonPath('$.data.code')[0]);
        $I->assertEquals('[{"name":"example-name"},{"description":"example-description"}]', $I->grabDataFromResponseByJsonPath('$.data.request')[0]);
        $I->assertEquals('[{"id":1}]', $I->grabDataFromResponseByJsonPath('$.data.response')[0]);
    }

    public function getDecodedContractDetails(AcceptanceTester $I)
    {
        $I->wantTo("Get Contract Details");
        $I->haveInRedis('hash', $this->microserviceHash, $this->microservice);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/mocker-api/contracts', array_merge([
            'microservice' => [
                'id' => $this->microserviceId,
                'name' => $this->microservice['name']
            ]], $this->contract));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED);
        $I->seeResponseIsJson();
        $contractId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $I->sendGET('/mocker-api/contracts/' . $contractId . '?decoded=true');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->assertEquals('17a57f651d2a2346c080690d172c6fb1', $I->grabDataFromResponseByJsonPath('$.data.id')[0]);
        $I->assertEquals([
            "id" => "1465d24108f608c5c2ace4d0725190a7",
            "name" => "microservice-name"
        ], $I->grabDataFromResponseByJsonPath('$.data.microservice')[0]);
        $I->assertEquals([
            "Authorization" => "Bearer #",
            "Content-Type" => "application/json"
        ], $I->grabDataFromResponseByJsonPath('$.data.headers')[0]);
        $I->assertEquals('POST', $I->grabDataFromResponseByJsonPath('$.data.method')[0]);
        $I->assertEquals('examples', $I->grabDataFromResponseByJsonPath('$.data.url')[0]);
        $I->assertEquals(200, $I->grabDataFromResponseByJsonPath('$.data.code')[0]);
        $I->assertEquals([
            ["name" => "example-name"],
            ["description" => "example-description"]
        ], $I->grabDataFromResponseByJsonPath('$.data.request')[0]);
        $I->assertEquals([
            ["id" => 1]
        ], $I->grabDataFromResponseByJsonPath('$.data.response')[0]);
    }

    /**
     * @return array
     */
    protected function contractsProvider()
    {
        return [
            [
                'microservice' => [
                    'id' => '',
                    'name' => 'microservice-name'
                ],
                'method' => 'POST',
                'url' => 'examples',
                'headers' => [
                    'Authorization' => 'Bearer #',
                    'Content-Type' => 'application/json'
                ],
                'request' => '[{"name"="example-name"},{"description"="example-description"}]',
                'response' => '[{"id"=1}]',
                'code' => '201'
            ],
            [
                'microservice' => [
                    'id' => 'hash',
                    'name' => 'microservice-name'
                ],
                'method' => '',
                'url' => 'examples',
                'headers' => [
                    'Authorization' => 'Bearer #',
                    'Content-Type' => 'application/json'
                ],
                'request' => '[{"name"="example-name"},{"description"="example-description"}]',
                'response' => '[{"id"=1}]',
                'code' => '201'
            ],
            [
                'microservice' => [
                    'id' => 'hash',
                    'name' => 'microservice-name'
                ],
                'method' => 'POST',
                'url' => '',
                'headers' => [
                    'Authorization' => 'Bearer #',
                    'Content-Type' => 'application/json'
                ],
                'request' => '[{"name"="example-name"},{"description"="example-description"}]',
                'response' => '[{"id"=1}]',
                'code' => '201'
            ],
            [
                'microservice' => [
                    'id' => 'hash',
                    'name' => 'microservice-name'
                ],
                'method' => 'POST',
                'url' => 'examples',
                'headers' => [
                    'Authorization' => 'Bearer #',
                    'Content-Type' => 'application/json'
                ],
                'request' => '[{"name"="example-name"},{"description"="example-description"}]',
                'response' => '[{"id"=1}]',
                'code' => ''
            ]
        ];
    }
}
