<?php

namespace core;

use PDO;

/**
 * Class DbConnection
 * @package core
 *
 * @property PDO $connection
 */
class DbConnection
{
    private $config;
    private $connection;

    public function __construct($config = [])
    {
        $this->config = $config;
        $this->connect();
    }

    /**
     * Connects to DB
     */
    private function connect()
    {
        $host = $dbname = $user = $password = '';
        extract($this->config);
        $this->connection = new PDO("mysql:host={$host};dbname={$dbname}", $user, $password);
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    public function loadTableSchema($name)
    {
        $pdo = $this->getConnection();
        $query = $pdo->prepare("SHOW FULL COLUMNS FROM `{$name}`;");
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $schema = [];
        foreach ($res as $column) {
            $schema[$column['Field']] = $column['Default'];
        }
        return $schema;
    }
}