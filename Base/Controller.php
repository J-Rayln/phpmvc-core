<?php

namespace JonathanRayln\Core\Base;

use JonathanRayln\Core\Application;
use JonathanRayln\Core\Http\Request;
use JonathanRayln\Core\Http\Response;
use JonathanRayln\Core\Middlewares\BaseMiddleware;

class Controller
{
    public Request $request;
    public Response $response;
    public string $layout = 'main';
    public string $action = '';

    /** @var \JonathanRayln\Core\Middlewares\BaseMiddleware[] */
    protected array $middleware = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

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