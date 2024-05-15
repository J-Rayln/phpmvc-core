<?php

namespace JonathanRayln\Core\Database;

use JonathanRayln\Core\Application;
use JonathanRayln\Core\Base\Model;

abstract class DbModel extends Model
{
    abstract public function primaryKey(): string;

    abstract public static function tableName(): string;

    abstract public function attributes(): array;

    /**
     * @return false|array
     */
    public function findAll(): false|array
    {
        $tableName = static::tableName();

        $statement = self::prepare("SELECT * FROM $tableName");

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param $where
     * @return false|object|null
     */
    public static function findOne($where): false|object|null
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);

        $sql = implode("AND", array_map(fn($attr) => "$attr = :$attr", $attributes));

        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");

        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();

        return $statement->fetchObject(static::class);
    }

    public function save(): bool
    {
        $tableName = static::tableName();
        $attributes = $this->attributes();

        $columns = implode(', ', $attributes);
        $placeholders = implode(', ', array_map(fn($attr) => ":$attr", $attributes));

        $statement = self::prepare("INSERT INTO $tableName ($columns) 
                VALUES ($placeholders)");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        return $statement->execute();
    }

    public static function prepare($sql): false|\PDOStatement
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}