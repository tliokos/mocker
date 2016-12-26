<?php

$app['microservice.storage'] = function() use ($app) {
    return new Mocker\Storage\Microservice(
        $app['redis']
    );
};

$app['microservice.validator'] = function () use ($app) {
    return new Mocker\Validator\Microservice(
        $app['validator']
    );
};

$app['microservice.service'] = function() use ($app) {
    return new Mocker\Service\Microservice(
        $app['microservice.storage'],
        $app['microservice.validator']
    );
};

$app['microservice.controller'] = function() use ($app) {
    return new Mocker\Controller\MicroserviceController(
        $app['microservice.service'],
        $app['contract.service'],
        $app['relationship.service'],
        $app['resource.formatter'],
        $app['twig']
    );
};