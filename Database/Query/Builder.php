<?php

namespace JonathanRayln\Core\Database\Query;

use PDO;

class Builder
{
    protected string $table;
    protected string $fields = '*';
    protected ?string $orderBy = null;
    protected string $direction = 'asc';
    protected array $wheres = [];
    protected array $values = [];
    protected ?int $limit = null;
    protected ?int $offset = null;

    public function __construct(private readonly \PDO $pdo) {}

    public function table(string $table, ?string $as = null): static
    {
        $this->table = $table;

        var_dump($this->table);

        return $this;
    }

    public function select(string $fields): static
    {
        if (!empty($fields)) {
            $this->fields = trim($fields, ',');
        }
        return $this;
    }

    public function orderBy(string $columns, string $direction = 'asc'): static
    {
        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException('Invalid sort direction given.  Accepted values are \'asc\' or \'desc\'.');
        }

        $this->orderBy = $columns;
        $this->direction = $direction;
        return $this;
    }

    private function addWhere(string $type, string $column, string $operator, null|string|int|array $values = null): void
    {
        $this->wheres[] = [
            'type'     => $type,
            'column'   => $column,
            'operator' => $operator,
            'value'    => $values,
        ];

        if (is_array($values)) {
            foreach ($values as $value) {
                $this->values[] = $value;
            }
        } else {
            $this->values[] = $values;
        }
    }

    public function where($column, $operator = null, $value = null): static
    {
        if ($column && !$value) {
            $value = $operator;
            $operator = '=';
        }

        $this->addWhere('AND', $column, $operator, $value);

        return $this;
    }

    public function orWhere($column, $operator = null, $value = null): static
    {
        if ($column && !$value) {
            $value = $operator;
            $operator = '=';
        }

        $this->addWhere('OR', $column, $operator, $value);

        return $this;
    }

    public function in(string $column, array $values): static
    {
        $this->addWhere('AND', $column, 'IN', $values);

        return $this;
    }

    public function orIn(string $column, array $values): static
    {
        $this->addWhere('OR', $column, 'IN', $values);

        return $this;
    }

    public function notIn(string $column, array $values): static
    {
        $this->addWhere('AND', $column, 'NOT IN', $values);

        return $this;
    }

    public function orNotIn(string $column, array $values): static
    {
        $this->addWhere('OR', $column, 'NOT IN', $values);

        return $this;
    }

    public function whereBetween(string $column, array $values): static
    {
        if (count($values) !== 2) {
            throw new \InvalidArgumentException('Invalid number of $values passed.  $values[] must contain only 2 arguments (minimum and maximum values).');
        }

        if ($values[0] > $values[1]) {
            throw new \InvalidArgumentException('Invalid $values passed.  $value[0] must be less than $value[1].');
        }

        $this->addWhere('AND', $column, 'BETWEEN', $values);

        return $this;
    }

    public function orWhereBetween(string $column, array $values): static
    {
        if (count($values) !== 2) {
            throw new \InvalidArgumentException('Invalid number of $values passed.  $values[] must contain only 2 arguments (minimum and maximum values).');
        }

        if ($values[0] > $values[1]) {
            throw new \InvalidArgumentException('Invalid $values passed.  $value[0] must be less than $value[1].');
        }

        $this->addWhere('OR', $column, 'BETWEEN', $values);

        return $this;
    }

    public function whereNotBetween(string $column, array $values): static
    {
        if (count($values) !== 2) {
            throw new \InvalidArgumentException('Invalid number of $values passed.  $values[] must contain only 2 arguments (minimum and maximum values).');
        }

        if ($values[0] > $values[1]) {
            throw new \InvalidArgumentException('Invalid $values passed.  $value[0] must be less than $value[1].');
        }

        $this->addWhere('AND', $column, 'NOT BETWEEN', $values);

        return $this;
    }

    public function orWhereNotBetween(string $column, array $values): static
    {
        if (count($values) !== 2) {
            throw new \InvalidArgumentException('Invalid number of $values passed.  $values[] must contain only 2 arguments (minimum and maximum values).');
        }

        if ($values[0] > $values[1]) {
            throw new \InvalidArgumentException('Invalid $values passed.  $value[0] must be less than $value[1].');
        }

        $this->addWhere('OR', $column, 'NOT_BETWEEN', $values);

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function get(): false|array
    {
        $sql = "SELECT $this->fields FROM $this->table";

        if (!empty($this->wheres)) {
            $sql .= ' WHERE ';
            foreach ($this->wheres as $index => $where) {
                if ($index > 0) {
                    $sql .= ' ' . $where['type'] . ' ';
                }

                switch ($where['operator']) {
                    case 'BETWEEN':
                        $sql .= '(' . $where['column'] . ' ' . $where['operator'] . ' ? AND ?)';
                        break;
                    case 'NOT_BETWEEN':
                        $sql .= '(' . $where['column'] . ' < ? OR ' . $where['column'] . ' > ?)';
                        break;
                    case 'IN':
                    case 'NOT IN':
                        $placeholders = str_repeat('?, ', count($where['value']));
                        $placeholders = trim($placeholders, ', ');
                        $sql .= $where['column'] . ' ' . $where['operator'] . ' (' . $placeholders . ')';
                        break;
                    default:
                        $sql .= $where['column'] . ' ' . $where['operator'] . ' ?';
                }
            }
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY $this->orderBy $this->direction";
        }

        if (!empty($this->limit)) {
            $sql .= " LIMIT $this->limit";
        }

        if (!empty($this->offset)) {
            $sql .= " OFFSET $this->offset";
        }

        $statement = $this->pdo->prepare($sql);
        $statement->execute($this->values);
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function first()
    {
        return $this->get()[0] ?? [];
    }
}