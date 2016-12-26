<?php

$app['relationship.storage'] = function() use ($app) {
    return new Mocker\Storage\Relationship(
        $app['redis']
    );
};

$app['relationship.service'] = function() use ($app) {
    return new Mocker\Service\Relationship(
        $app['relationship.storage']
    );
};