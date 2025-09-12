<?php

class ConnectionPool {
    private $pool = [];
    private $maxConnections;
    private $dbHost;
    private $dbUser;
    private $dbPass;
    private $dbName;

    public function __construct($maxConnections, $dbHost, $dbUser, $dbPass, $dbName) {
        $this->maxConnections = $maxConnections;
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->dbName = $dbName;

        for ($i = 0; $i < $maxConnections; $i++) {
            $this->pool[] = $this->createConnection();
        }
    }

    private function createConnection() {
        $conn = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    public function getConnection() {
        if (count($this->pool) == 0) {
            // No available connections, handle the situation
            throw new Exception("No available connections in the pool");
        }

        return array_pop($this->pool);
    }

    public function releaseConnection($connection) {
        $this->pool[] = $connection;
    }
}

// Crear una instancia del pool de conexiones
$maxConnections = 50;
$pool = new ConnectionPool($maxConnections, 'localhost', 'root', '12345678', 'proyecto_tsu');



$db = new mysqli('localhost', 'root', '12345678', 'proyecto_tsu');
//query("SET NAMES 'utf8';");
$db->set_charset("utf8");

$casa = 'Marcador';
 ?>
