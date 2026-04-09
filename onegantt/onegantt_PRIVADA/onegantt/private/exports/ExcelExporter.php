<?php
class ExcelExporter
{
    private TaskModel    $tasks;
    private ProjectModel $projects;

    public function __construct()
    {
        $this->tasks    = new TaskModel();
        $this->projects = new ProjectModel();
    }

    public function exportTasks(int $proyectoId = 0): void
    {
        $filters = $proyectoId ? ['proyecto_id' => $proyectoId] : [];
        $rows    = $this->tasks->filter($filters);

        $proyecto = $proyectoId
            ? ($this->projects->find($proyectoId)['nombre'] ?? 'todos')
            : 'todos';

        $filename = 'onegantt_tareas_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        // BOM para Excel
        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, [
            'ID', 'Proyecto', 'Título', 'Descripción', 'Estatus',
            'Asignado a', 'Fecha inicio', 'Fecha fin', 'Prioridad', 'Progreso %',
            'Padre ID', 'Creado en'
        ]);

        $prioridades = [1 => 'Baja', 2 => 'Media', 3 => 'Alta'];

        foreach ($rows as $r) {
            fputcsv($out, [
                $r['id'],
                $r['proyecto_nombre'],
                $r['titulo'],
                $r['descripcion'] ?? '',
                $r['estatus_nombre'],
                $r['asignado_nombre'] ?? '',
                $r['fecha_inicio'] ?? '',
                $r['fecha_fin'] ?? '',
                $prioridades[$r['prioridad']] ?? 'Media',
                $r['progreso'],
                $r['padre_id'] ?? '',
                $r['created_at'],
            ]);
        }

        fclose($out);
        exit;
    }
}
