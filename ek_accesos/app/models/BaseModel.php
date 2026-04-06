<?php

abstract class BaseModel
{
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function all(array $conditions = [], string $orderBy = '', int $limit = 0): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        $params = [];

        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "`$col` = ?";
                $params[] = $val;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }

        if ($orderBy) $sql .= " ORDER BY $orderBy";
        if ($limit > 0) $sql .= " LIMIT $limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $cols = implode(', ', array_map(fn($c) => "`$c`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->pdo->prepare("INSERT INTO `{$this->table}` ($cols) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        return (int)$this->pdo->lastInsertId();
    }

    public function update($id, array $data): bool
    {
        $set = implode(', ', array_map(fn($c) => "`$c` = ?", array_keys($data)));
        $stmt = $this->pdo->prepare("UPDATE `{$this->table}` SET $set WHERE `{$this->primaryKey}` = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function delete($id): bool
    {
        // Soft-delete: stamp deleted_at if the column exists on this table.
        // Subclasses that don't have deleted_at should override and use hard delete.
        static $softDeleteTables = null;

        if ($softDeleteTables === null) {
            $softDeleteTables = [];
        }

        $table = $this->table;

        if (!array_key_exists($table, $softDeleteTables)) {
            // Check once per table whether deleted_at column exists
            try {
                $stmt = $this->pdo->prepare("SHOW COLUMNS FROM `{$table}` LIKE 'deleted_at'");
                $stmt->execute();
                $softDeleteTables[$table] = (bool) $stmt->fetch();
            } catch (\PDOException $e) {
                $softDeleteTables[$table] = false;
            }
        }

        if ($softDeleteTables[$table]) {
            $stmt = $this->pdo->prepare(
                "UPDATE `{$this->table}` SET `deleted_at` = NOW() WHERE `{$this->primaryKey}` = ? AND `deleted_at` IS NULL"
            );
            return $stmt->execute([$id]);
        }

        $stmt = $this->pdo->prepare(
            "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?"
        );
        return $stmt->execute([$id]);
    }

    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        $params = [];
        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "`$col` = ?";
                $params[] = $val;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    protected function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    protected function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
