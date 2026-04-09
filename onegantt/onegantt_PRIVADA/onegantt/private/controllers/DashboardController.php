<?php
class DashboardController
{
    private Auth $auth;
    private Database $db;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
        $this->db   = Database::getInstance();
    }

    public function index(?string $param = null): void
    {
        $this->auth->requireLogin();
        $userId = $this->auth->userId();

        $stats = [
            'proyectos'  => $this->db->fetchOne('SELECT COUNT(*) AS n FROM projects WHERE activo = 1')['n'] ?? 0,
            'tareas_hoy' => $this->db->fetchOne('SELECT COUNT(*) AS n FROM tasks WHERE fecha_fin = CURDATE() AND estatus_id NOT IN (4,5)')['n'] ?? 0,
            'vencidas'   => $this->db->fetchOne('SELECT COUNT(*) AS n FROM tasks WHERE fecha_fin < CURDATE() AND estatus_id NOT IN (4,5)')['n'] ?? 0,
            'mis_tareas' => $this->db->fetchOne('SELECT COUNT(*) AS n FROM tasks WHERE asignado_a = :u AND estatus_id NOT IN (4,5)', [':u' => $userId])['n'] ?? 0,
        ];

        $misTareas = $this->db->fetchAll(
            'SELECT t.id, t.titulo, t.fecha_fin, t.progreso, s.nombre AS estatus, s.color AS estatus_color, p.nombre AS proyecto
             FROM tasks t
             JOIN statuses s ON s.id = t.estatus_id
             JOIN projects p ON p.id = t.proyecto_id
             WHERE t.asignado_a = :u AND t.estatus_id NOT IN (4,5)
             ORDER BY t.fecha_fin ASC LIMIT 8',
            [':u' => $userId]
        );

        $proyectos = $this->db->fetchAll(
            'SELECT p.id, p.nombre, p.color,
                    COUNT(t.id) AS total_tareas,
                    ROUND(AVG(t.progreso),0) AS avance
             FROM projects p
             LEFT JOIN tasks t ON t.proyecto_id = p.id AND t.padre_id IS NULL
             WHERE p.activo = 1
             GROUP BY p.id ORDER BY p.nombre LIMIT 6'
        );

        $auth      = $this->auth;  // necesario para que las vistas accedan a $auth
        $flash     = Router::flash();
        include ROOT_PATH . 'views/dashboard/index.php';
    }
}
