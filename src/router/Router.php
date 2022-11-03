<?php

namespace App\Routes;

class Router
{
    protected $prefix;
    protected $routes = [];
    protected $notFound = "Undefined route";

    public function addRoute(string $method, string $path, callable $callback)
    {
        if (array_key_exists($path, $this->routes)) {
            $this->routes[$path] += [$method => $callback];
        } else {
            $this->routes[$path] = [$method => $callback];
        }
    }

    public function run()
    {
        $uri = parse_url($_SERVER['REQUEST_URI']);
        $path = $uri['path'];
        
        $method = $_SERVER['REQUEST_METHOD'];

        if (array_key_exists($path, $this->routes)) {
            $pathMethods = $this->routes[$path];

            if (array_key_exists($method, $pathMethods)) {
                $pathMethods[$method]();
            }
        } else {
            echo $this->notFound;
        }
    }
}
