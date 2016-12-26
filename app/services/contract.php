<?php

$app['contract.storage'] = function() use ($app) {
    return new Mocker\Storage\Contract(
        $app['redis']
    );
};

$app['contract.validator'] = function () use ($app) {
    return new Mocker\Validator\Contract(
        $app['validator']
    );
};

$app['contract.service'] = function() use ($app) {
    return new Mocker\Service\Contract(
        $app['contract.storage'],
        $app['contract.validator']
    );
};

$app['contract.controller'] = function() use ($app) {
    return new Mocker\Controller\ContractController(
        $app['microservice.service'],
        $app['contract.service'],
        $app['relationship.service'],
        $app['resource.formatter'],
        $app['twig']
    );
};