<?php

$app['unique.storage.key.validator'] = function ($app) {
    return new Mocker\Constraint\UniqueStorageKeyValidator($app['redis']);
};

$app['json.validator'] = function () {
    return new Mocker\Constraint\JsonValidator();
};

