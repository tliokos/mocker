<?php

namespace Mocker\Test\Acceptance;

use AcceptanceTester;
use Codeception\Example;
use Mocker\Storage\Contract as ContractStorage;

class MocksCest
{
    private $microservice = [
        'id' =>'17a57f651d2a2346c080690d172c6fb1',
        'name' => 'microservice-name'
    ];

    /**
     * @dataprovider contractsProvider
     */
    public function queryMockedEndpoints(AcceptanceTester $I, Example $example)
    {
        $I->wantTo("I want to test a {$example['method']} request with response code {$example['code']}");
        $contractId = ContractStorage::getId(
            $this->microservice['name'],
            $example['method'],
            $example['url']
        );
        $I->haveInRedis('hash', sprintf(ContractStorage::CONTRACTS_KEY, $contractId), [
            'microservice' => $example['microservice'],
            'method' => $example['method'],
            'url' => $example['url'],
            'headers' => $example['headers'],
            'request' => $example['request'],
            'response' => $example['response'],
            'code' => $example['code'],
        ]);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $method = 'send' . $example['method'];
        $I->$method('/' . $this->microservice['name'] . '/' . $example['url']);
        $I->seeResponseCodeIs($example['code']);
        $I->seeResponseEquals($example['response']);
    }

    /**
     * @return array
     */
    protected function contractsProvider()
    {
        return [
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'GET',
                'url' => 'examples/1',
                'headers' => '',
                'request' => '',
                'response' => '{}',
                'code' => '200'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'GET',
                'url' => 'examples/1',
                'headers' => '',
                'request' => '',
                'response' => '{}',
                'code' => '400'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'GET',
                'url' => 'examples/1',
                'headers' => '',
                'request' => '',
                'response' => '{}',
                'code' => '401'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'GET',
                'url' => 'examples/1',
                'headers' => '',
                'request' => '',
                'response' => '{}',
                'code' => '403'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'GET',
                'url' => 'examples/1',
                'headers' => '',
                'request' => '',
                'response' => '{}',
                'code' => '404'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'GET',
                'url' => 'examples/1',
                'headers' => '',
                'request' => '',
                'response' => '{}',
                'code' => '500'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'GET',
                'url' => 'examples/1',
                'headers' => '',
                'request' => '',
                'response' => json_encode([
                    'id' => 1,
                    'name' => 'name'
                ]),
                'code' => '200'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'POST',
                'url' => 'examples',
                'headers' => '',
                'request' => '',
                'response' => json_encode([
                    'id' => 1
                ]),
                'code' => '201'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'POST',
                'url' => 'examples',
                'headers' => '',
                'request' => '',
                'response' => json_encode([
                    'id' => 1
                ]),
                'code' => '409'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'POST',
                'url' => 'examples',
                'headers' => '',
                'request' => '',
                'response' => json_encode([
                    'id' => 1
                ]),
                'code' => '422'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'PUT',
                'url' => 'examples/1',
                'headers' => '',
                'request' => '',
                'response' => '',
                'code' => '204'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'PATCH',
                'url' => 'examples/1',
                'headers' => '',
                'request' => '',
                'response' => '',
                'code' => '204'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'DELETE',
                'url' => 'examples/1',
                'headers' => '',
                'request' => '',
                'response' => '{}',
                'code' => '200'
            ],
            [
                'microservice' => json_encode($this->microservice),
                'method' => 'OPTIONS',
                'url' => 'examples',
                'headers' => '',
                'request' => '',
                'response' => '{}',
                'code' => '200'
            ]
        ];
    }
}
