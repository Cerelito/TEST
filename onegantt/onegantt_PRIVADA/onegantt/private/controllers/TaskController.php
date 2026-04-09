<?php
class TaskController
{
    private Auth            $auth;
    private TaskModel       $tasks;
    private ProjectModel    $projects;
    private AttachmentModel $attachments;

    public function __construct(Auth $auth)
    {
        $this->auth        = $auth;
        $this->tasks       = new TaskModel();
        $this->projects    = new ProjectModel();
        $this->attachments = new AttachmentModel();
    }

    public function index(?string $param = null): void
    {
        $this->auth->requireLogin();

        $filters = [
            'proyecto_id' => Sanitizer::int($_GET['proyecto_id'] ?? 0) ?: null,
            'estatus_id'  => Sanitizer::int($_GET['estatus_id']  ?? 0) ?: null,
            'busqueda'    => Sanitizer::string($_GET['busqueda'] ?? ''),
        ];

        // Colaborador solo puede filtrar entre sus propias tareas
        if ($this->auth->isColaborador()) {
            $filters['asignado_a'] = $this->auth->userId();
        } else {
            // Admin y Director pueden filtrar por cualquier usuario
            $filters['asignado_a'] = Sanitizer::int($_GET['asignado_a'] ?? 0) ?: null;
        }

        $auth      = $this->auth;
        $taskList  = $this->tasks->filter($filters);
        $proyectos = $this->projects->listForSelect();
        $statuses  = $this->tasks->statuses();
        // Para el filtro de usuario (solo admin y director lo ven)
        $usuarios  = $this->auth->canSeeAllTasks() ? (new UserModel())->listForSelect() : [];
        $flash     = Router::flash();
        include ROOT_PATH . 'views/tasks/index.php';
    }

    public function create(?string $param = null): void
    {
        $this->auth->requireRole(['admin', 'director', 'colaborador']);
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->auth->validateCsrf()) {
                $error = 'Token inválido.';
            } else {
                $v = new Validator($_POST);
                $v->required('titulo', 'Título')
                  ->required('proyecto_id', 'Proyecto')
                  ->date('fecha_inicio', 'Fecha inicio')
                  ->date('fecha_fin', 'Fecha fin');

                if ($v->fails()) {
                    $error = $v->firstError();
                } else {
                    $taskId = $this->tasks->create([
                        'titulo'       => Sanitizer::post('titulo'),
                        'descripcion'  => Sanitizer::post('descripcion'),
                        'proyecto_id'  => Sanitizer::post('proyecto_id', 'int'),
                        'padre_id'     => Sanitizer::int($_POST['padre_id'] ?? 0) ?: null,
                        'estatus_id'   => Sanitizer::post('estatus_id', 'int') ?: 1,
                        'asignado_a'   => Sanitizer::int($_POST['asignado_a'] ?? 0) ?: null,
                        'fecha_inicio' => Sanitizer::post('fecha_inicio') ?: null,
                        'fecha_fin'    => Sanitizer::post('fecha_fin') ?: null,
                        'prioridad'    => Sanitizer::post('prioridad', 'int') ?: 2,
                    ], $this->auth->userId());

                    // Dependencias
                    $this->syncDependencies((int)$taskId, $_POST['depende_de'] ?? []);

                    // Adjuntos
                    if (!empty($_FILES['adjuntos']['name'][0])) {
                        foreach ($_FILES['adjuntos']['name'] as $i => $name) {
                            $file = [
                                'name'     => $name,
                                'type'     => $_FILES['adjuntos']['type'][$i],
                                'tmp_name' => $_FILES['adjuntos']['tmp_name'][$i],
                                'error'    => $_FILES['adjuntos']['error'][$i],
                                'size'     => $_FILES['adjuntos']['size'][$i],
                            ];
                            $this->attachments->upload((int)$taskId, $this->auth->userId(), $file);
                        }
                    }

                    Router::redirectWithFlash('tasks', 'Tarea creada correctamente.');
                }
            }
        }

        $auth      = $this->auth;
        $proyectos = $this->projects->listForSelect();
        $statuses  = $this->tasks->statuses();
        $usuarios  = (new UserModel())->listForSelect();
        $allTasks  = $this->tasks->filter([]);
        $task      = null;
        $deps      = [];
        $csrfField = $this->auth->csrfField();
        include ROOT_PATH . 'views/tasks/form.php';
    }

    public function edit(?string $id = null): void
    {
        $this->auth->requireLogin();
        $task = $this->tasks->find((int)$id);
        if (!$task) { http_response_code(404); die('Tarea no encontrada.'); }

        // Colaborador solo puede editar tareas asignadas a él mismo
        if ($this->auth->isColaborador() && $task['asignado_a'] !== $this->auth->userId()) {
            http_response_code(403); die('Sin permiso.');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->auth->validateCsrf()) {
                $error = 'Token inválido.';
            } else {
                $v = new Validator($_POST);
                $v->required('titulo', 'Título')->date('fecha_fin', 'Fecha fin');

                if ($v->fails()) {
                    $error = $v->firstError();
                } else {
                    // Nota nueva
                    $nota = Sanitizer::post('nota_nueva');
                    if (!empty($nota)) {
                        $this->tasks->addNote((int)$id, $this->auth->userId(), $nota);
                    }

                    // Adjuntos nuevos
                    if (!empty($_FILES['adjuntos']['name'][0])) {
                        foreach ($_FILES['adjuntos']['name'] as $i => $name) {
                            $file = [
                                'name'     => $name,
                                'type'     => $_FILES['adjuntos']['type'][$i],
                                'tmp_name' => $_FILES['adjuntos']['tmp_name'][$i],
                                'error'    => $_FILES['adjuntos']['error'][$i],
                                'size'     => $_FILES['adjuntos']['size'][$i],
                            ];
                            $this->attachments->upload((int)$id, $this->auth->userId(), $file);
                        }
                    }

                    // Dependencias (solo si el formulario las envía)
                    if (array_key_exists('depende_de', $_POST) || isset($_POST['depende_de'])) {
                        $this->syncDependencies((int)$id, $_POST['depende_de'] ?? []);
                    }

                    $this->tasks->update((int)$id, [
                        'titulo'       => Sanitizer::post('titulo'),
                        'descripcion'  => Sanitizer::post('descripcion'),
                        'proyecto_id'  => Sanitizer::post('proyecto_id', 'int'),
                        'padre_id'     => Sanitizer::int($_POST['padre_id'] ?? 0) ?: null,
                        'estatus_id'   => Sanitizer::post('estatus_id', 'int'),
                        'asignado_a'   => Sanitizer::int($_POST['asignado_a'] ?? 0) ?: null,
                        'fecha_inicio' => Sanitizer::post('fecha_inicio') ?: null,
                        'fecha_fin'    => Sanitizer::post('fecha_fin') ?: null,
                        'prioridad'    => Sanitizer::post('prioridad', 'int'),
                        'progreso'     => Sanitizer::post('progreso', 'int'),
                    ]);

                    Router::redirectWithFlash('tasks/edit', (int)$id, 'Tarea actualizada.');
                }
            }
        }

        $auth      = $this->auth;
        $notas     = $this->tasks->notes((int)$id);
        $adjuntos  = $this->attachments->byTask((int)$id);
        $deps      = $this->tasks->dependencies((int)$id);
        $proyectos = $this->projects->listForSelect();
        $statuses  = $this->tasks->statuses();
        $usuarios  = (new UserModel())->listForSelect();
        $allTasks  = $this->tasks->filter([]);
        $csrfField = $this->auth->csrfField();
        include ROOT_PATH . 'views/tasks/form.php';
    }

    public function gantt(?string $param = null): void
    {
        $this->auth->requireLogin();
        $auth       = $this->auth;
        $proyectoId = Sanitizer::int($_GET['proyecto_id'] ?? 0);
        $proyectos  = $this->projects->listForSelect();
        $ganttTasks = $proyectoId ? $this->tasks->forGantt($proyectoId) : [];
        include ROOT_PATH . 'views/tasks/gantt.php';
    }

    public function delete(?string $id = null): void
    {
        $this->auth->requireRole(['admin', 'director', 'colaborador']);
        if (!$this->auth->validateCsrf()) Router::redirect('tasks');
        $this->tasks->delete((int)$id);
        Router::redirectWithFlash('tasks', 'Tarea eliminada.');
    }

    // ── Helpers ───────────────────────────────────────────

    private function syncDependencies(int $tareaId, array $rawIds): void
    {
        // IDs nuevos (filtrar vacíos y asegurar int)
        $newIds = array_filter(array_map('intval', $rawIds), fn($i) => $i > 0 && $i !== $tareaId);

        // IDs actuales en BD
        $current = array_column($this->tasks->dependencies($tareaId), 'id');

        // Agregar los que faltan
        foreach (array_diff($newIds, $current) as $depId) {
            $this->tasks->addDependency($tareaId, $depId);
        }
        // Eliminar los que ya no están
        foreach (array_diff($current, $newIds) as $depId) {
            $this->tasks->removeDependency($tareaId, $depId);
        }
    }
}
