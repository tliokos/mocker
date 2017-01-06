<?php

$app->get('/mocker-dashboard/{template}', function($template) use ($app) {
    return $app['twig']->render($template . '.twig');
});

$app->get('/mocker-api/microservices', 'microservice.controller:list');
$app->get('/mocker-api/microservices/{microserviceId}', 'microservice.controller:get');
$app->post('/mocker-api/microservices', 'microservice.controller:create');
$app->delete('/mocker-api/microservices/{microserviceId}', 'microservice.controller:delete');

$app->get('/mocker-api/contracts', 'contract.controller:list');
$app->get('/mocker-api/contracts/{contractId}', 'contract.controller:get');
$app->post('/mocker-api/contracts', 'contract.controller:create');
$app->put('/mocker-api/contracts/{contractId}', 'contract.controller:update');
$app->delete('/mocker-api/contracts/{contractId}', 'contract.controller:delete');

$app->get('/mocker-api/httpHeaders', function() {
    return new \Symfony\Component\HttpFoundation\JsonResponse([
        ['id' => 'Accept', 'name' => 'Accept'],
        ['id' => 'Accept-Charset', 'name' => 'Accept-Charset'],
        ['id' => 'Accept-Encoding', 'name' => 'Accept-Encoding'],
        ['id' => 'Accept-Language', 'name' => 'Accept-Language'],
        ['id' => 'Accept-Datetime', 'name' => 'Accept-Datetime'],
        ['id' => 'Authorization', 'name' => 'Authorization'],
        ['id' => 'Cache-Control', 'name' => 'Cache-Control'],
        ['id' => 'Connection', 'name' => 'Connection'],
        ['id' => 'Cookie', 'name' => 'Cookie'],
        ['id' => 'Content-Length', 'name' => 'Content-Length'],
        ['id' => 'Content-MD5', 'name' => 'Content-MD5'],
        ['id' => 'Content-Type', 'name' => 'Content-Type'],
        ['id' => 'Date', 'name' => 'Date'],
        ['id' => 'Expect', 'name' => 'Expect'],
        ['id' => 'Forwarded', 'name' => 'Forwarded'],
        ['id' => 'From', 'name' => 'From'],
        ['id' => 'Host', 'name' => 'Host'],
        ['id' => 'If-Match', 'name' => 'If-Match'],
        ['id' => 'If-Modified-Since', 'name' => 'If-Modified-Since'],
        ['id' => 'If-None-Match', 'name' => 'If-None-Match'],
        ['id' => 'If-Range', 'name' => 'If-Range'],
        ['id' => 'If-Unmodified-Since', 'name' => 'If-Unmodified-Since'],
        ['id' => 'Max-Forwards', 'name' => 'Max-Forwards'],
        ['id' => 'Origin', 'name' => 'Origin'],
        ['id' => 'Pragma', 'name' => 'Pragma'],
        ['id' => 'Proxy-Authorization', 'name' => 'Proxy-Authorization'],
        ['id' => 'Range', 'name' => 'Range'],
        ['id' => 'Referer', 'name' => 'Referer'],
        ['id' => 'User-Agent', 'name' => 'User-Agent'],
        ['id' => 'Upgrade', 'name' => 'Upgrade'],
        ['id' => 'Via', 'name' => 'Via'],
        ['id' => 'Warning', 'name' => 'Warning']
    ], Mocker\StatusCode::OK);
});

$app->match('{microservice}/{url}', 'mocks.controller:handle')
    ->assert('microservice', '^[a-zA-Z\-\_]+$')
    ->assert('url', '.*')
    ->method('GET|POST|PUT|PATCH|DELETE|OPTIONS');