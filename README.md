Mocker - Microservices Mocking Framework
=======================================

### About Mocker

Mocker is a simple framework that allows you to mock the dependencies of your 
microservices. It is based on the "Consumer-Driven Contracts" pattern
and uses contracts for validating the expectations on both consumer and producer sides.

### Development Stack

Mocker is developed on PHP7 (is not backwards compatible with PHP5) and uses Redis as its storage engine.

### Installation  

- Clone the repository 
- Copy `.env.sample` to `.env`
- Install the dependencies `composer install`
- Build the containers `docker-compose up -d --build` 

### Use Case

You're building an application that has a **Consumer** and a **Producer**  microservice and you 
need to mock the **Producer** microservice for both development and testing purposes. By using
Mocker, you can create a **Contract** between the **Consumer**  and the **Producer** and make sure that:
- **Consumer** is able to handle all the different responses generated by the **Producer** 
- **Producer** always produces the responses expected by the **Consumer**

### Example

In Mocker, all contracts are created under specific microservices so we need a microservice first.  In 
order to crate a new microservice, open your browser and navigate to:  

```
 http://localhost:8080/mocker-dashboard/microservices
```

After creating the microservice, navigate to the contracts page and create a new contract. For each contract
you  can specify Microservice, Method, URL, Headers, Request Body, Response Body and Response Code.  Let's 
assume that we've created the following contract.

#### Request

```
 Method: GET, URL: producer/1
```
    
##### Response  

``` 
Code: 200, Body: [{"id":1, "name":"name"}] 
```

  Let's see how we can use it in order to test our **Consumer** and **Producer** microservices.

#### Important Note

  In all the examples we assume that you use `.env.testing` file, where you define your testing configuration. 
If you have created a microservice in mocker for the **Producer** and its name is **producer-microservice**, 
in the `.env.testing` file the basic Url to the **Producer** microservice should be set to 

```
http://localhost:8080/producer-microservice
```

#### Testing the Consumer  

Let's assume that we are testing the **Consumer** microservice, and we need to make a GET request to the
**Producer**  microservice on endpoint `producer/1`. Even if the endpoint does not exist yet, we can use 
Mocker like:

```
 $httpClient->get('producer/1'); 
```

  This will give as back the response specified in the contract with the respective code. As soon as we have
the  response back, it is really easy to test if the **Consumer** behaves the way we want it to do. Keep in 
mind that  the method you will use in the `$httpClient` must much the method specified in the contract.