<?php

use Codeception\Util\Fixtures;

$_SERVER['ENVIRONMENT'] = Mocker\Environment::TESTING;

$app = require_once __DIR__ . '/../app/bootstrap.php';

Fixtures::add('app', $app);

