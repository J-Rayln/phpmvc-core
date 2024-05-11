<?php

namespace JonathanRayln\Core\Http;

use JonathanRayln\Core\Application;
use JonathanRayln\Core\Exceptions\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    /**
     * @param Request  $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    private function getCallback()
    {
        $method = $this->request->method();
        $url = $this->request->getPath();
        $url = trim($url, '/');

        $routes = $this->routes[$method] ?? [];

        $routeParams = false;

        foreach ($routes as $route => $callback) {
            $route = trim($route, '/');
            $routeNames = [];

            if (!$route) {
                continue;
            }

            if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
                $routeNames = $matches[1];
            }

            $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/', fn($m) => isset($m[2]) ? "({$m[2]})" : '([\w-]+)', $route) . "$@";

            if (preg_match_all($routeRegex, $url, $valueMatches)) {
                $values = [];

                for ($i = 1; $i < count($valueMatches); $i++) {
                    $values[] = $valueMatches[$i][0];
                }

                $routeParams = array_combine($routeNames, $values);

                $this->request->setRouteParams($routeParams);

                return $callback;
            }
        }

        return false;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();

        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {

            $callback = $this->getCallback();

            if ($callback === false) {
                throw new NotFoundException();
            }
        }

        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        if (is_array($callback)) {
            /** @var \JonathanRayln\Core\Base\Controller $controller */
            $controller = new $callback[0]($this->request, $this->response);
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach ($controller->getMiddleware() as $middleware) {
                $middleware->execute();
            }
        }


        return call_user_func_array($callback, $this->request->getRouteParams());
    }
}