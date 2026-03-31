<?php
// app/config/db.php - Conexión a base de datos con PDO

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;
    private $conn;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db_name = $_ENV['DB_DATABASE'] ?? '';
        $this->username = $_ENV['DB_USERNAME'] ?? '';
        $this->password = $_ENV['DB_PASSWORD'] ?? '';
        $this->charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
    }

    /**
     * Obtener conexión PDO
     */
    public function getConnection()
    {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch (PDOException $exception) {
            if ($_ENV['APP_DEBUG'] === 'true') {
                die("Error de conexión: " . $exception->getMessage());
            } else {
                error_log("Error de BD: " . $exception->getMessage());
                die("Error de conexión a la base de datos. Contacte al administrador.");
            }
        }

        return $this->conn;
    }

    /**
     * Cerrar conexión
     */
    public function closeConnection()
    {
        $this->conn = null;
    }
}
