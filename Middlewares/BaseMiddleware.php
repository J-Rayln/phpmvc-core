<?php

namespace JonathanRayln\Core\Middlewares;

use JonathanRayln\Core\Http\Response;

abstract class BaseMiddleware
{
    abstract public function execute(Response $response);
}