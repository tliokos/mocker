Mocker
====================

### About Mocker

Mocker is a simple solution (PHP7, Apache, Redis) that allows you to  mock the dependencies of your 
microservices (external API calls) while you test. 
Mocker is based on the "Consumer-Driven Contracts" pattern and uses contracts for
validating the expectations on the consumer and producer sides.

### Use Case

Let's assume that we have two microservices (`Consumer` and `Producer`) 
and that for some specific requests `Consumer` needs to query `Producer` in order 
to get some data back.
In this, simple case we need to be able to test that:
- `Consumer` is able to handle all the different responses from the `Producer`
- `Producer` always produces the responses expected by the `Consumer` under specific circumstances

By using Mocker, we can create a `Contract` between the `Consumer` and the `Producer`. 
The contract consists of the request parameters (url, method, body and headers) as well
as the response parameters (response body and code).

### Example

Let's assume the following contract:
##### Request
```
Method: GET, URL: producer/1
```

##### Response
```
Code: 200, Body: [{"id":1, "name":"name"}]
```
Once we crate this contract by using the UI provided with Mocker, we will 
get back a unique Url (e.g. `mocker/2e23e6cf6f5fde596b25fa0f323d19d2`) 
that we can use in order to test.

##### Testing the Consumer

While we are testing the consumer, we just execute the method specified in the 
contract against the Url provided by Mocker. For example in Codeception, it will
be something like `$I->sendGET('mocker/2e23e6cf6f5fde596b25fa0f323d19d2');`. 
This will give as back the response specified in the contract with the respective code.
As soon as we have the response back, it is really easy to test if the `Consumer` behaves
the way we expect it to do.

##### Testing the Producer

While we are testing the producer, we need to get first the information stored in the contract,
execute the request to the URL specified in the contract and check that the response is exactly the 
same as the one specified in the contract.
```
$contract = $guzzle->get('api/2e23e6cf6f5fde596b25fa0f323d19d2');
$I->sendGET($contract['url']);
$response = $I->grabDataFromResponseByJsonPath('$.....');
$I->assertEquals($contract['response'], response);
```

### Prerequisites 

- Docker

### Installation

- Clone the repository
- Copy .env.sample to .env
- Install dependencies `composer install`
- Build the containers `docker-compose up -d --build`
- Navigate to `localhost:8080/dashboard/microservices`

### TODO

- Detailed Documentation
- UI Fixes