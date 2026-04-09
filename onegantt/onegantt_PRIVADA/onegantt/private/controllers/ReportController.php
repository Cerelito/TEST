<?php
class ReportController
{
    private Auth         $auth;
    private TaskModel    $tasks;
    private ProjectModel $projects;

    public function __construct(Auth $auth)
    {
        $this->auth     = $auth;
        $this->tasks    = new TaskModel();
        $this->projects = new ProjectModel();
    }

    public function index(?string $param = null): void
    {
        $this->auth->requireLogin();
        $auth      = $this->auth;
        $proyectos = $this->projects->listForSelect();
        $flash     = Router::flash();
        include ROOT_PATH . 'views/reports/index.php';
    }

    public function export(?string $param = null): void
    {
        $this->auth->requireLogin();
        $proyectoId = Sanitizer::int($_GET['proyecto_id'] ?? 0);
        $exporter   = new ExcelExporter();
        $exporter->exportTasks($proyectoId);
    }

    public function import(?string $param = null): void
    {
        $this->auth->requireRole(['admin', 'gestor']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->auth->validateCsrf()) {
            Router::redirect('reports');
        }

        $importer = new ExcelImporter();
        $result   = $importer->importTasks($_FILES['archivo'] ?? [], $this->auth->userId());

        if ($result['ok']) {
            Router::redirectWithFlash('reports', "Importación exitosa: {$result['inserted']} tareas agregadas.");
        } else {
            Router::redirectWithFlash('reports', 'Error en importación: ' . $result['error'], 'error');
        }
    }
}
