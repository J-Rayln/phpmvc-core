<?php

namespace JonathanRayln\Core\Database;

use JonathanRayln\Core\Application;


/**
 * @method static \JonathanRayln\Core\Database\QueryBuilder table(string $string, string|null $as = null)
 */
class Db
{
    private static ?QueryBuilder $instance = null;

    private static function getInstance(): QueryBuilder
    {
        if (!self::$instance) {
            self::$instance = new QueryBuilder(Application::$app->db->pdo);
        }

        return self::$instance;
    }

    public static function __callStatic($method, $args)
    {
        $instance = self::getInstance();

        return call_user_func_array([$instance, $method], $args);
    }
}