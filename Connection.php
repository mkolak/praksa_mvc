<?php

class Connection
{
    protected $db_host = "127.0.0.1";
    protected $db_port = 3306;
    protected $db_name = "model-demo";
    protected $username = "root";
    protected $password = "";
    protected $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    public $pdo;

    public function __construct()
    {
        $conn_string = "mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8mb4";
        try {
            $this->pdo = new PDO($conn_string, $this->username, $this->password);
        } catch (PDOException $PDOException) {
            throw new PDOException($PDOException->getMessage(), (int) $PDOException->getCode());
        }
    }
}

$conn = new Connection();
