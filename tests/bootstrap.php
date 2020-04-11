<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (!file_exists(dirname(__DIR__).'/doc/openapi/openapi.json')) {
    throw new RuntimeException(sprintf('You should dump the openapi spec in json format. If you sourced functions.sh just run `openapi-build`'));
}

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
