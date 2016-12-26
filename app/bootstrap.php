<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(__DIR__ . '/services', FilesystemIterator::SKIP_DOTS)
);

foreach($files as $fileName => $fileInfo){
    require_once $fileName;
}

require_once 'routes.php';
require_once 'errors.php';
require_once 'service_providers.php';

$app['debug'] = getenv('ENVIRONMENT') !== Mocker\Environment::PRODUCTION ? true : false;

return $app;

