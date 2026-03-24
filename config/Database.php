<?php

namespace Config;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $this->loadEnv();
        
        $host = getenv('DB_HOST');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');
        $db = getenv('DB_NAME');
        $port = getenv('DB_PORT');

        try {
            $this->connection = new \mysqli($host, $user, $pass, $db, (int)$port);
            
            if ($this->connection->connect_error) {
                throw new \Exception("Error de conexión: " . $this->connection->connect_error);
            }

            $this->connection->set_charset("utf8mb4");
        } catch (\Exception $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    private function loadEnv()
    {
        $envPath = dirname(__DIR__) . '/.env';
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') === false) {
                    list($key, $value) = explode('=', $line, 2);
                    putenv(trim($key) . '=' . trim($value));
                }
            }
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function closeConnection()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
