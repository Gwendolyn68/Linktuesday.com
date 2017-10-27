<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;

try {
    $container = new ContainerBuilder();
    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../config/'));
    $loader->load('services.yml');

    $request = Request::createFromGlobals();
    $router = new \Router\Router($container);

    $controller = $router->getController($request);

    $response = $controller->handleRequest($request);
    $response->send();
} catch (Throwable $t) {
    echo $t->getMessage();
}
