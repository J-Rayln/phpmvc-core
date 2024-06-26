<?php

namespace JonathanRayln\Core\Http\Routing;

use JonathanRayln\Core\Application;

class Route
{
    public static function get(string $uri, array|string|\Closure $handler): void
    {
        Application::$app->router->get($uri, $handler);
    }

    public static function post(string $uri, array|string|\Closure $handler): void
    {
        Application::$app->router->post($uri, $handler);
    }

    public static function both(string $uri, array|string|\Closure $handler): void
    {
        Application::$app->router->get($uri, $handler);
        Application::$app->router->post($uri, $handler);
    }
}