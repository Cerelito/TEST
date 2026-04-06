<?php
// app/models/Ajuste.php

class Ajuste
{
    private $db;
    private $table = 'ajustes';

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Obtener todos los ajustes
     */
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ajustes = [];
        foreach ($rows as $row) {
            $ajustes[$row['clave']] = $row['valor'];
        }

        return $ajustes;
    }

    /**
     * Obtener un ajuste por su clave
     */
    public function get($clave)
    {
        $stmt = $this->db->prepare("SELECT valor FROM {$this->table} WHERE clave = ?");
        $stmt->execute([$clave]);
        return $stmt->fetchColumn();
    }

    /**
     * Guardar múltiples ajustes
     */
    public function saveMultiple($datos)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (clave, valor) VALUES (?, ?) ON DUPLICATE KEY UPDATE valor = VALUES(valor)");

        foreach ($datos as $clave => $valor) {
            $stmt->execute([$clave, $valor]);
        }

        return true;
    }
}
