<?php

$app['resource.serializer'] = function() {
    return new Mocker\Resource\Serializer();
};

$app['resource.manager'] = function($app) {
    $resourceManager =  new \League\Fractal\Manager();
    $resourceManager->setSerializer($app['resource.serializer']);
    return $resourceManager;
};

$app['resource.factory'] = function() {
    return new Mocker\Resource\Factory();
};

$app['transformer.namespace.builder'] = function() {
    return new Mocker\Transformer\NamespaceBuilder();
};

$app['transformer.factory'] = function($app) {
    return new Mocker\Transformer\Factory($app['transformer.namespace.builder']);
};

$app['resource.formatter'] = function($app) {
    return new Mocker\Resource\Formatter(
        $app['resource.manager'],
        $app['resource.factory'],
        $app['transformer.factory']
    );
};