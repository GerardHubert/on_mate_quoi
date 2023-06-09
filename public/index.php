<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
$dotenv = Dotenv\Dotenv::createImmutable("../");
$dotenv->load();
$router = new \App\Services\Router;
$router->run();
