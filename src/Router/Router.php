<?php

namespace Router;

use Controller\Controller;
use Controller\ErrorController;
use FastRoute;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var FastRoute\Dispatcher
     */
    private $dispatcher;

    /**
     * MonitoringRouter constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->configureRouter();
    }

    /**
     * Get a controller based on the request
     *
     * @param Request $request
     * @return Controller
     */
    public function getController(Request $request): Controller
    {
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        if (!isset ($routeInfo[1]) || empty($routeInfo[1])) {
            return $this->error();
        }

        if (! $this->container->has($routeInfo[1])) {
            return $this->error();
        }

        foreach($routeInfo[2] as $name => $value) {
            $request->request->set($name, $value);
        }

        return $this->container->get($routeInfo[1]);
    }

    /**
     * Configure the router
     */
    private function configureRouter()
    {
        $this->dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $routeCollector) {
            $routeCollector->addRoute('GET', '/', 'home_controller');
        });
    }

    /**
     * Get an error controller
     *
     * @return ErrorController
     */
    private function error()
    {
        return new ErrorController();
    }
}
