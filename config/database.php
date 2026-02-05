<?php
declare(strict_types=1);

final class Database
{
    private string $host = '127.0.0.1';   // IMPORTANT: avoid mysql socket lookup on macOS
    private int $port = 3306;
    private string $db_name = 'printcopy';
    private string $username = 'root';
    private string $password = '';

    public function getConnection(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $this->host,
            $this->port,
            $this->db_name
        );

        $pdo = new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        return $pdo;
    }
}