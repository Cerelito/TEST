<?php
/**
 * Database configuration and PDO singleton connection.
 * Returns a single PDO instance for the ek_accesos database.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'ek_accesos');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

class Database {
    public static function getInstance(): PDO { return getDB(); }
}

function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log the error and show a generic message to the user
            error_log('[EK Accesos] Database connection failed: ' . $e->getMessage());
            http_response_code(500);
            die('Error de conexión a la base de datos. Por favor, inténtelo más tarde.');
        }
    }

    return $pdo;
}
