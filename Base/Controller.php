<?php

namespace JonathanRayln\Core\Base;

use JonathanRayln\Core\Application;
use JonathanRayln\Core\Middlewares\BaseMiddleware;

class Controller
{
    public string $layout = 'main';
    public string $action = '';

    /** @var \JonathanRayln\Core\Middlewares\BaseMiddleware[] */
    protected array $middleware = [];

    public function render($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middleware[] = $middleware;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }
}