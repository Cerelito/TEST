<?php

class DashboardController extends Controller
{
    public function __construct()
    {
        requireAuth();
    }

    public function index(): void
    {
        $pdo = Database::getInstance();

        // ── KPI stats ─────────────────────────────────────────────────────────
        $stats = [
            'total_empleados'      => (int)$pdo->query(
                "SELECT COUNT(*) FROM empleados WHERE deleted_at IS NULL AND activo = 1"
            )->fetchColumn(),
            'total_requisitores'   => (int)$pdo->query(
                "SELECT COUNT(*) FROM requisitores WHERE activo = 1"
            )->fetchColumn(),
            'total_compradores'    => (int)$pdo->query(
                "SELECT COUNT(*) FROM compradores WHERE activo = 1"
            )->fetchColumn(),
            'total_programas'      => (int)$pdo->query(
                "SELECT COUNT(*) FROM programa_nivel WHERE deleted_at IS NULL AND activo = 1"
            )->fetchColumn(),
            'usuarios_pendientes'  => (int)$pdo->query(
                "SELECT COUNT(*) FROM usuarios WHERE aprobado = 0 AND deleted_at IS NULL"
            )->fetchColumn(),
            'total_centros_costo'  => (int)$pdo->query(
                "SELECT COUNT(*) FROM centros_costo WHERE activo = 1"
            )->fetchColumn(),
        ];

        // ── Recent employees ──────────────────────────────────────────────────
        $recientes = $pdo->query("
            SELECT e.id, e.nombre, e.puesto, e.email,
                   emp.nombre AS empresa,
                   pn.nombre  AS programa_nivel
            FROM empleados e
            LEFT JOIN empresas emp ON emp.id = e.empresa_id
            LEFT JOIN empleado_programa_nivel epn ON epn.empleado_id = e.id
            LEFT JOIN programa_nivel pn ON pn.id = epn.programa_nivel_id
            WHERE e.deleted_at IS NULL
            ORDER BY e.id DESC
            LIMIT 8
        ")->fetchAll(\PDO::FETCH_ASSOC);

        // ── CC distribution by tipo ───────────────────────────────────────────
        $ccDist = $pdo->query("
            SELECT tipo, COUNT(*) AS total
            FROM empleado_cc
            WHERE activo = 1
            GROUP BY tipo
        ")->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('dashboard/index', [
            'title'     => 'Dashboard',
            'stats'     => $stats,
            'recientes' => $recientes,
            'ccDist'    => $ccDist,
        ]);
    }
}
