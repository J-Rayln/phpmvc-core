<?php

namespace JonathanRayln\Core\Database;

use JonathanRayln\Core\Application;
use JonathanRayln\Core\Database\Query\Builder;

/**
 * @method static Query\Builder table(string $string, string|null $as = null)
 * @method static Query\Builder select(string $fields)
 * @method static Query\Builder orderBy(string $columns, string $direction = 'asc')
 * @method static Query\Builder where($column, $operator = null, $value = null)
 * @method static Query\Builder orWhere($column, $operator = null, $value = null)
 * @method static Query\Builder in(string $column, array $values)
 * @method static Query\Builder orIn(string $column, array $values)
 * @method static Query\Builder notIn(string $column, array $values)
 * @method static Query\Builder orNotIn(string $column, array $values)
 * @method static Query\Builder whereBetween(string $column, array $values)
 * @method static Query\Builder orWhereBetween(string $column, array $values)
 * @method static Query\Builder whereNotBetween(string $column, array $values)
 * @method static Query\Builder orWhereNotBetween(string $column, array $values)
 * @method static Query\Builder limit(int $limit)
 * @method static Query\Builder offset(int $offset)
 */
class Db
{
    private static ?Builder $instance = null;

    private static function getInstance(): Builder
    {
        if (!self::$instance) {
            self::$instance = new Builder(Application::$app->db->pdo);
        }

        return self::$instance;
    }

    public static function __callStatic($method, $args)
    {
        $instance = self::getInstance();

        return call_user_func_array([$instance, $method], $args);
    }
}