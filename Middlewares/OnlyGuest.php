<?php

namespace JonathanRayln\Core\Middlewares;

use JonathanRayln\Core\Application;
use JonathanRayln\Core\Http\Response;

class OnlyGuest extends BaseMiddleware
{
    public array $actions = [];

    /**
     * @param array $actions
     */
    public function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    public function execute(Response $response): void
    {
        if (!Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                $response->redirect('/');
            }
        }
    }
}