<?php

namespace App\Core;

use App\Core\Exceptions\RouterControllerException;
use App\Core\Exceptions\RouterMethodException;
use App\Core\Middleware;
use App\Core\Interfaces\RouterInterface;

class Router implements RouterInterface
{
    private $routes = [];
    private function add
    (
        string $url,
        array $controller,
        string $method,
    ): Router
    {
        $route = [
            "url" => $url,
            "controller" => $controller,
            "method" => $method,
            "middleware" => [],
        ];
        array_push($this->routes, $route);
        return $this;
    }

    public function get
    (
        string $url,
        array $controller,
    ): Router
    {
        $this->add($url, $controller, "GET");
        return $this;
    }

    public function post
    (
        string $url,
        array $controller,
    ): Router
    {
        $this->add($url, $controller, "POST");
        return $this;
    }

    public function middleware
    (
        string $middleware
    ): Router
    {
        array_push(
            $this->routes[count($this->routes) - 1]["middleware"],
            $middleware
        );
        return $this;
    }

    public function resolve(
        string $url,
        string $method,
    ): void
    {
        $isFound = false;
        foreach($this->routes as $route)
        {
            if($this->didRouteMatch($route, $url, $method))
            {
                $isFound = true;
                $class = $route["controller"][0];
                $classMethod = $route["controller"][1];

                $this->resolveMiddleWare($route);
                $this->classAndMethodExists(
                    $class,
                    $classMethod
                );
                break;
            }
        }
        $this->notFound($isFound);
    }

    private function didRouteMatch
    (
        array $route,
        string $url,
        string $method
    ): bool
    {
        if($route["url"] === $url && $route["method"] === $method)
        {
            return true;
        }
        return false;
    }

    private function classAndMethodExists
    (
        string $class,
        string $classMethod,
    ): void
    {
        if(class_exists($class))
        {
            if(method_exists($class, $classMethod))
            {
                $this->callMethod(
                    $class,
                    $classMethod
                );
            } else {
                throw new RouterMethodException($classMethod);
            }
        } else {
             throw new RouterControllerException($class);
        }
    }

    private function callMethod
    (
        string $class,
        string $classMethod,
    ): void
    {
        $container = new Container();
        $newController = $container->get($class);

        $request = new Request();
        $newController->$classMethod($request);
    }

    private function notFound(bool $isFound)
    {
        if(!$isFound)
        {
            return view("errors/notfound");
        }
    }

    private function resolveMiddleWare
    (
        array $route
    )
    {
        foreach($route["middleware"] as $routeMiddleware)
        {
            foreach(Middleware::$middlewares as $middleware)
            {
                if($this->didMiddleWareMatch($routeMiddleware, $middleware["name"]))
                {
                    $this->callMiddleWare($middleware);
                }
            }
        }
    }

    private function didMiddleWareMatch
    (
        string $routeMiddleWareName,
        string $middlewareName,
    ): bool
    {
        if($routeMiddleWareName === $middlewareName)
        {
            return true;
        }
        return false;
    }

    private function callMiddleWare
    (
        array $middleware,
    ): void
    {
        if(class_exists($middleware["class"]))
        {
            if(method_exists($middleware["class"], "handle"))
            {
                if(Middleware::$isContinue)
                {
                    $newMiddleWare = new $middleware["class"];
                    $newMiddleWare->handle();
                }
            }
        }
    }
}
