<?php
class Database
{
    private string $host = 'localhost';
    private string $name = 'presensi_siswa';
    private string $user = 'root';
    private string $pass = '';
    private PDO $pdo;

    public function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $this->user, $this->pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
