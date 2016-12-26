<?php

namespace Mocker\Test\Acceptance;

use AcceptanceTester;
use Codeception\Util\Fixtures;
use Mocker\Storage\{
    Microservice as MicroserviceStorage,
    Contract as ContractStorage,
    Relationship as RelationshipStorage
};

class MicroservicesCest
{
    private $microservice = [
        'name' => 'microservice-name',
        'description' => 'microservice-description'
    ];

    private $contract = [
        'method' => 'POST',
        'url' => 'examples',
        'headers' => '',
        'request' => '',
        'response' => '',
        'code' => '200'
    ];

    private $id;

    private $hash;

    public function _before()
    {
        $this->app = Fixtures::get('app');
        $this->id = md5($this->microservice['name']);
        $this->hash = sprintf(MicroserviceStorage::MICROSERVICES_KEY, $this->id);
    }

    public function createMicroserviceWithValidData(AcceptanceTester $I)
    {
        $I->wantTo("Create a Microservice with valid data");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/microservices', $this->microservice);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->assertEquals($this->id, $I->grabDataFromResponseByJsonPath('$.data.id')[0]);
        $I->seeInRedis($this->hash);
        $I->seeRedisKeyContains($this->hash, 'name', 'microservice-name');
        $I->seeRedisKeyContains($this->hash, 'description', 'microservice-description');
        $I->seeRedisKeyContains($this->hash, 'contracts', 0);
    }

    public function createMicroserviceWithInvalidData(AcceptanceTester $I)
    {
        $I->wantTo("Create a Microservice with invalid data");
        $I->haveInRedis('hash', $this->hash, $this->microservice);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/microservices', $this->microservice);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }

    public function createMicroserviceWithMissingData(AcceptanceTester $I)
    {
        $I->wantTo("Create a Microservice with missing data");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/microservices', []);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }

    public function createContractUnderMicroservice(AcceptanceTester $I)
    {
        $I->wantTo("Create a Contract under a Microservice");
        $I->haveInRedis('hash', $this->hash, array_merge($this->microservice, ['contracts' => 0]));
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/contracts', array_merge([
            'microservice' => [
                'id' => $this->id,
                'name' => $this->microservice['name']
        ]], $this->contract));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeRedisKeyContains($this->hash, 'contracts', 1);
        $contractId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];
        $relationshipHash = sprintf(RelationshipStorage::RELATIONSHIPS_KEY, $this->id);
        $I->seeInRedis($relationshipHash);
        $I->seeRedisKeyContains($relationshipHash, $contractId);
    }

    public function deleteMicroservice(AcceptanceTester $I)
    {
        $I->wantTo("Delete a Microservice");
        $I->haveInRedis('hash', $this->hash, $this->microservice);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/api/microservices/' . $this->id);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT);
        $I->seeResponseEquals(null);
        $I->dontSeeInRedis($this->hash);
    }

    public function deleteMicroserviceWithContracts(AcceptanceTester $I)
    {
        $I->wantTo("Delete a Microservice with Contracts");
        $I->haveInRedis('hash', $this->hash, array_merge($this->microservice, ['contracts' => 0]));
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/contracts', array_merge([
            'microservice' => [
                'id' => $this->id,
                'name' => $this->microservice['name']
            ]], $this->contract));
        $contractHash = sprintf(ContractStorage::CONTRACTS_KEY,$I->grabDataFromResponseByJsonPath('$.data.id')[0]);
        $relationshipHash = sprintf(RelationshipStorage::RELATIONSHIPS_KEY, $this->id);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/api/microservices/' . $this->id);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT);
        $I->dontSeeInRedis($this->hash);
        $I->dontSeeInRedis($contractHash);
        $I->dontSeeInRedis($relationshipHash);
    }
}
