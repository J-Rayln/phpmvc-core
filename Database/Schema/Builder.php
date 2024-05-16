<?php

namespace JonathanRayln\Core\Database\Schema;

use PDO;

class Builder
{
    public function __construct(private PDO $pdo) {}

    public function create(string $table, $interface)
    {
        echo '<pre>';
        print_r($interface);
        echo '</pre>';
        exit;
    }
}