<?php

$app['mocks.controller'] = function() use ($app) {
    return new Mocker\Controller\MocksController(
        $app['contract.service']
    );
};