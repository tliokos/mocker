<?php

use Mocker\{StatusCode, Exception\ValidationException};

$app->error(function(ValidationException $exception) use ($app) {
    return $app->json($exception->getDecodedMessage(), StatusCode::UNPROCESSABLE_ENTITY);
});