<?php

class Conexion
{
    private $connection;
    private $host;
    private $username;
    private $password;
    private $db;
    private $port;
    private $server;

    public function __construct()
    {
        $this->server = $_SERVER['HTTP_HOST'];
        $this->connection = null;
        $this->port = 3306; // puerto por defecto de MySQL
        $this->db = 'backend-sec70-dasencio-v2';
        $this->host = 'localhost'; // suponiendo que te estás conectando a localhost

        if ($this->server == 'localhost') {
            $this->username = 'backend-sec70-dasencio-v2';
            $this->password = 'l4cl4v3-c11s4';
        }
    }

    public function getConnection()
    {
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->db, $this->port);
        mysqli_set_charset($this->connection, 'utf8');
        if (!$this->connection) {
            die('Connection failed: ' . mysqli_connect_error());
        }
        return $this->connection;
    }

    public function closeConnection()
    {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }
}
