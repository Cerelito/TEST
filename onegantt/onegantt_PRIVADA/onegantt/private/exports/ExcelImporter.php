<?php
class ExcelImporter
{
    private TaskModel    $tasks;
    private ProjectModel $projects;
    private Database     $db;

    public function __construct()
    {
        $this->tasks    = new TaskModel();
        $this->projects = new ProjectModel();
        $this->db       = Database::getInstance();
    }

    public function importTasks(array $file, int $userId): array
    {
        if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'Archivo inválido o no recibido.'];
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['csv'], true)) {
            return ['ok' => false, 'error' => 'Solo se aceptan archivos CSV.'];
        }

        $handle   = fopen($file['tmp_name'], 'r');
        $header   = fgetcsv($handle); // saltar encabezado
        $inserted = 0;
        $errors   = [];
        $line     = 1;

        // Mapear proyectos por nombre
        $proyMap = [];
        foreach ($this->projects->listForSelect() as $p) {
            $proyMap[strtolower(trim($p['nombre']))] = $p['id'];
        }

        while (($row = fgetcsv($handle)) !== false) {
            $line++;
            if (count($row) < 4) { $errors[] = "Línea {$line}: columnas insuficientes."; continue; }

            $proyNombre = strtolower(trim($row[1] ?? ''));
            $proyId     = $proyMap[$proyNombre] ?? null;

            if (!$proyId) { $errors[] = "Línea {$line}: proyecto '{$row[1]}' no encontrado."; continue; }

            $titulo = Sanitizer::string($row[2] ?? '');
            if (empty($titulo)) { $errors[] = "Línea {$line}: título vacío."; continue; }

            try {
                $this->tasks->create([
                    'titulo'       => $titulo,
                    'descripcion'  => Sanitizer::string($row[3] ?? ''),
                    'proyecto_id'  => $proyId,
                    'estatus_id'   => 1,
                    'fecha_inicio' => $this->parseDate($row[6] ?? ''),
                    'fecha_fin'    => $this->parseDate($row[7] ?? ''),
                    'prioridad'    => $this->parsePriority($row[8] ?? ''),
                    'padre_id'     => Sanitizer::int($row[10] ?? 0) ?: null,
                ], $userId);
                $inserted++;
            } catch (\Exception $e) {
                $errors[] = "Línea {$line}: " . $e->getMessage();
            }
        }

        fclose($handle);

        return [
            'ok'       => true,
            'inserted' => $inserted,
            'errors'   => $errors,
        ];
    }

    private function parseDate(string $val): ?string
    {
        $val = trim($val);
        if (empty($val)) return null;
        $ts = strtotime($val);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    private function parsePriority(string $val): int
    {
        return match(strtolower(trim($val))) {
            'alta', 'high', '3' => 3,
            'baja', 'low', '1'  => 1,
            default             => 2,
        };
    }
}
