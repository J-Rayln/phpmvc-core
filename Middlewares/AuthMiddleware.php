<?php

namespace JonathanRayln\Core\Middlewares;

use JonathanRayln\Core\Application;
use JonathanRayln\Core\Exceptions\ForbiddenException;
use JonathanRayln\Core\Http\Response;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    /**
     * @param array $actions
     */
    public function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    public function execute(Response $response)
    {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}