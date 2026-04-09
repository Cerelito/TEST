<?php
class ProjectController
{
    private Auth         $auth;
    private ProjectModel $model;

    public function __construct(Auth $auth)
    {
        $this->auth  = $auth;
        $this->model = new ProjectModel();
    }

    public function index(?string $param = null): void
    {
        $this->auth->requireLogin();
        $auth     = $this->auth;
        $projects = $this->model->all();
        $flash    = Router::flash();
        include ROOT_PATH . 'views/projects/index.php';
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
                $v->required('nombre', 'Nombre')->maxLen('nombre', 150, 'Nombre');
                if ($v->fails()) {
                    $error = $v->firstError();
                } else {
                    $this->model->create([
                        'nombre'      => Sanitizer::post('nombre'),
                        'descripcion' => Sanitizer::post('descripcion'),
                        'color'       => Sanitizer::post('color') ?: '#5563DE',
                    ], $this->auth->userId());
                    Router::redirectWithFlash('projects', 'Proyecto creado correctamente.');
                }
            }
        }

        $auth      = $this->auth;
        $csrfField = $this->auth->csrfField();
        $project   = null;
        include ROOT_PATH . 'views/projects/form.php';
    }

    public function edit(?string $id = null): void
    {
        $this->auth->requireRole(['admin', 'director', 'colaborador']);
        $project = $this->model->find((int)$id);
        if (!$project) { http_response_code(404); die('Proyecto no encontrado.'); }
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->auth->validateCsrf()) {
                $error = 'Token inválido.';
            } else {
                $v = new Validator($_POST);
                $v->required('nombre', 'Nombre');
                if ($v->fails()) {
                    $error = $v->firstError();
                } else {
                    $this->model->update((int)$id, [
                        'nombre'      => Sanitizer::post('nombre'),
                        'descripcion' => Sanitizer::post('descripcion'),
                        'color'       => Sanitizer::post('color') ?: '#5563DE',
                        'activo'      => isset($_POST['activo']) ? 1 : 0,
                    ]);
                    Router::redirectWithFlash('projects', 'Proyecto actualizado.');
                }
            }
        }

        $auth      = $this->auth;
        $csrfField = $this->auth->csrfField();
        include ROOT_PATH . 'views/projects/form.php';
    }

    public function delete(?string $id = null): void
    {
        $this->auth->requireRole(['admin']);
        if (!$this->auth->validateCsrf()) Router::redirect('projects');
        $this->model->delete((int)$id);
        Router::redirectWithFlash('projects', 'Proyecto desactivado.');
    }
}
