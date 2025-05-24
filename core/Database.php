<?php

namespace App\Core;

use App\Core\ConfigManager;
use PDO;

class Database
{
    protected static $pdo = null;
    protected $statement;

    public function __construct()
    {
        if (self::$pdo === null) {
            self::$pdo = self::getConnection();
        }
    }

    public static function getConnection(): PDO
    {
        $config = ConfigManager::get('database');

        if (!$config || !isset($config['host'], $config['dbname'], $config['user'], $config['password'], $config['charset'])) {
            throw new \RuntimeException("Invalid database configuration.");
        }

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new PDO($dsn, $config['user'], $config['password'], $options);
    }

    public function query(string $sql): void
    {
        $this->statement = $this->pdo->prepare($sql);
    }

    public function bind(string|int $key, mixed $value, int $type = null): void
    {
        if ($type === null) {
            $type = match (true) {
                is_int($value)  => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                default         => PDO::PARAM_STR,
            };
        }

        $this->statement->bindValue($key, $value, $type);
    }

    public function execute(): bool
    {
        return $this->statement->execute();
    }

    public function single(): array|false
    {
        return $this->statement->fetch();
    }

    public function resultSet(): array
    {
        return $this->statement->fetchAll();
    }

    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    public static function executeQuery(string $query, array $parameters = [], bool $single = true): array|false
    {
        $database = new self();
        $database->query($query);

        foreach ($parameters as $key => $value) {
            $database->bind($key, $value);
        }

        $database->execute();

        return $single ? $database->single() : $database->resultSet();
    }
}
