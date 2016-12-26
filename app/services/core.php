<?php

$app['dotenv'] = function() {
    $dotEnvFile = $_SERVER['ENVIRONMENT'] === \Mocker\Environment::TESTING
        ? '.env.testing'
        : '.env';
    return new \Dotenv\Dotenv(__DIR__ . '/../../', $dotEnvFile);
};

$app['http.client'] = function() {
    return new GuzzleHttp\Client();
};

$app['redis'] = function() {
    $redis = new \Redis();
    $redis->connect(getenv('REDIS_HOST'), getenv('REDIS_PORT'));
    $redis->select(getenv('REDIS_DATABASE'));
    return $redis;
};