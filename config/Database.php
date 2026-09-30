<?php

declare(strict_types=1);

class Database
{
    private string $host = 'localhost';
    private string $db = 'breadsaver';
    private string $user = 'root';
    private string $pass = '';

    private ?PDO $connection = null;

    public function connect(): PDO
    {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";

        $this->connection = new PDO(
            $dsn,
            $this->user,
            $this->pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        return $this->connection;
    }
}
