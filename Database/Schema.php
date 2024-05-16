<?php

namespace JonathanRayln\Core\Database;

use JonathanRayln\Core\Application;

class Schema
{
    private static ?Schema\Builder $instance = null;

    private static function getInstance(): Schema\Builder
    {
        if (!self::$instance) {
            self::$instance = new Schema\Builder(Application::$app->db->pdo);
        }

        return self::$instance;
    }

    public static function __callStatic($method, $args)
    {
        $instance = self::getInstance();

        return call_user_func_array([$instance, $method], $args);
    }
}