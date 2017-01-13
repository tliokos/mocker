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
                'url' => 'statsAgg?query=%7B%22fields%22%3A%5B%22advertiser_id%22%5D%2C%22filters%22%3A%7B%22advertiser_id%22%3A%7B%22relation%22%3A%22in%22%2C%22sets%22%3A%5B1%2C2%5D%7D%2C%22date%22%3A%7B%22relation%22%3A%22between%22%2C%22sets%22%3A%5B%222016-01-01%22%2C%222016-12-31%22%5D%7D%7D%2C%22groups%22%3A%5B%22advertiser_id%22%5D%2C%22groupDefaultNumber%22%3A0%2C%22orders%22%3A%7B%22date%22%3A%22desc%22%7D%2C%22page%22%3A0%2C%22limit%22%3A100%7D',
                'headers' => '',
                'request' => '',
                'response' => '{"status":"OK","message":"","metadata":{"total":1,"page":1,"query":{"fields":["advertiser_id"],"filters":{"advertiser_id":{"relation":"in","sets":[1,2]},"date":{"relation":"between","sets":["2016-01-01","2016-12-31"]}},"groups":["advertiser_id"],"groupDefaultNumber":0,"orders":{"date":"desc"},"page":0,"limit":100}},"data":[{"advertiser_id":"1"}]}',
                'code' => '200'
            ],
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
