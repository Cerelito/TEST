<?php

class EmpleadosController extends Controller
{
    private Empleado $model;

    public function __construct()
    {
        requireAuth();
        $this->model = new Empleado();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // index — list employees
    // ─────────────────────────────────────────────────────────────────────────

    public function index(): void
    {
        $filters = [
            'buscar'     => $_GET['buscar']     ?? '',
            'empresa_id' => $_GET['empresa_id'] ?? '',
            'activo'     => $_GET['activo']     ?? '',
        ];

        $empleados = $this->model->getAll(
            array_filter($filters, fn($v) => $v !== '')
        );
        $empresas = $this->model->getEmpresasList();

        $this->render('empleados/index', [
            'title'     => 'Empleados',
            'empleados' => $empleados,
            'empresas'  => $empresas,
            'filters'   => $filters,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // crear — show creation form
    // ─────────────────────────────────────────────────────────────────────────

    public function crear(): void
    {
        requireRole(['admin', 'capturista']);

        $empresas = $this->model->getEmpresasList();
        $pn       = (new ProgramaNivel())->getSelectList();
        $jefes    = $this->model->getAll(['activo' => '1']);

        $this->render('empleados/crear', [
            'title'           => 'Nuevo Empleado',
            'empresas'        => $empresas,
            'programas_nivel' => $pn,
            'jefes'           => $jefes,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // guardar — process creation (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function guardar(): void
    {
        requireRole(['admin', 'capturista']);
        verifyCSRF();

        $nombre    = sanitize($_POST['nombre']    ?? '');
        $puesto    = sanitize($_POST['puesto']    ?? '');
        $email     = sanitize($_POST['email']     ?? '');
        $telefono  = sanitize($_POST['telefono']  ?? '');
        $empresaId = (int)($_POST['empresa_id']   ?? 0);
        $jefeId    = (int)($_POST['jefe_id']      ?? 0) ?: null;
        $pnId      = (int)($_POST['programa_nivel_id'] ?? 0);

        if (!$nombre || !$empresaId) {
            setFlash('error', 'Nombre y empresa son obligatorios.');
            redirect('empleados/crear');
        }

        $id = $this->model->create([
            'nombre'     => $nombre,
            'puesto'     => $puesto,
            'email'      => $email,
            'telefono'   => $telefono,
            'empresa_id' => $empresaId,
            'jefe_id'    => $jefeId,
            'activo'     => 1,
        ]);

        // Assign programa nivel
        if ($pnId) {
            $this->model->asignarProgramaNivel($id, $pnId, currentUserId() ?? 0);
        }

        // Save centros de costo
        $centros = $_POST['centros'] ?? [];
        if (!empty($centros) && is_array($centros)) {
            $this->model->saveCentrosCosto($id, $centros);
            $this->syncRequisitorComprador($id, $centros);
        }

        logAction('crear', 'empleados', "Empleado creado: $nombre (ID: $id)");
        setFlash('success', "Empleado <strong>" . htmlspecialchars($nombre) . "</strong> creado exitosamente.");
        redirect('empleados');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // editar — show edit form
    // ─────────────────────────────────────────────────────────────────────────

    public function editar($id): void
    {
        requireRole(['admin', 'capturista']);

        $empleado = $this->model->getById((int)$id);
        if (!$empleado) {
            setFlash('error', 'Empleado no encontrado.');
            redirect('empleados');
        }

        $empresas = $this->model->getEmpresasList();
        $pn       = (new ProgramaNivel())->getSelectList();
        $jefes    = $this->model->getAll(['activo' => '1']);

        $this->render('empleados/editar', [
            'title'             => 'Editar: ' . htmlspecialchars($empleado['nombre']),
            'empleado'          => $empleado,
            'empresas'          => $empresas,
            'programas_nivel'   => $pn,
            'jefes'             => $jefes,
            'centros_asignados' => $empleado['centros_costo'] ?? [],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // actualizar — process update (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function actualizar($id): void
    {
        requireRole(['admin', 'capturista']);
        verifyCSRF();

        $empleado = $this->model->find((int)$id);
        if (!$empleado) {
            setFlash('error', 'Empleado no encontrado.');
            redirect('empleados');
        }

        $nombre    = sanitize($_POST['nombre']    ?? '');
        $puesto    = sanitize($_POST['puesto']    ?? '');
        $email     = sanitize($_POST['email']     ?? '');
        $telefono  = sanitize($_POST['telefono']  ?? '');
        $empresaId = (int)($_POST['empresa_id']   ?? 0);
        $jefeId    = (int)($_POST['jefe_id']      ?? 0) ?: null;
        $pnId      = (int)($_POST['programa_nivel_id'] ?? 0);

        if (!$nombre || !$empresaId) {
            setFlash('error', 'Nombre y empresa son obligatorios.');
            redirect('empleados/editar/' . $id);
        }

        $this->model->update((int)$id, [
            'nombre'     => $nombre,
            'puesto'     => $puesto,
            'email'      => $email,
            'telefono'   => $telefono,
            'empresa_id' => $empresaId,
            'jefe_id'    => $jefeId,
            'activo'     => isset($_POST['activo']) ? 1 : 0,
        ]);

        if ($pnId) {
            $this->model->asignarProgramaNivel((int)$id, $pnId, currentUserId() ?? 0);
        }

        // Update centros de costo
        $centros = $_POST['centros'] ?? [];
        if (is_array($centros)) {
            $this->model->saveCentrosCosto((int)$id, $centros);
            $this->syncRequisitorComprador((int)$id, $centros);
        }

        logAction('editar', 'empleados', "Empleado actualizado: $nombre (ID: $id)");
        setFlash('success', 'Empleado actualizado exitosamente.');
        redirect('empleados');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // eliminar — soft delete (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function eliminar($id): void
    {
        requireRole(['admin']);
        verifyCSRF();

        $empleado = $this->model->find((int)$id);
        if ($empleado) {
            $this->model->delete((int)$id);
            logAction('eliminar', 'empleados', "Empleado eliminado ID: $id");
            setFlash('success', 'Empleado eliminado.');
        } else {
            setFlash('error', 'Empleado no encontrado.');
        }
        redirect('empleados');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // toggleActivo — toggle active flag (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function toggleActivo($id): void
    {
        requireRole(['admin', 'capturista']);
        verifyCSRF();

        $this->model->toggleActivo((int)$id);
        logAction('toggle_activo', 'empleados', "Toggle activo empleado ID: $id");

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->json(['ok' => true]);
        }
        redirect('empleados');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // buscar — AJAX: search employees by name for jefe selector
    // ─────────────────────────────────────────────────────────────────────────

    public function buscar(): void
    {
        $q = sanitize($_GET['q'] ?? '');
        if (strlen($q) < 2) {
            $this->json([]);
        }

        $rows = $this->model->query(
            "SELECT e.id, e.nombre, emp.nombre AS empresa
             FROM empleados e
             LEFT JOIN empresas emp ON emp.id = e.empresa_id
             WHERE e.deleted_at IS NULL AND e.activo = 1
               AND e.nombre LIKE ?
             ORDER BY e.nombre LIMIT 20",
            ['%' . $q . '%']
        );

        $this->json($rows);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * After saving CCs, ensure requisitores/compradores tables are in sync.
     *
     * Rules:
     *  - Any CC with tipo=REQ or AMBOS and elab=1 → add to requisitores
     *  - Any CC with tipo=OC  or AMBOS and elab=1 → add to compradores
     */
    private function syncRequisitorComprador(int $empleadoId, array $centros): void
    {
        $esRequisitor = false;
        $esComprador  = false;

        foreach ($centros as $cc) {
            $tipo = $cc['tipo'] ?? '';
            $elab = (int)($cc['elab'] ?? 0);

            if ($elab === 1) {
                if (in_array($tipo, ['REQ', 'AMBOS'], true)) {
                    $esRequisitor = true;
                }
                if (in_array($tipo, ['OC', 'AMBOS'], true)) {
                    $esComprador = true;
                }
            }
        }

        if ($esRequisitor) {
            $this->upsertRequisitor($empleadoId);
        }
        if ($esComprador) {
            $this->upsertComprador($empleadoId);
        }
    }

    private function upsertRequisitor(int $empleadoId): void
    {
        $existing = $this->pdo()->prepare(
            "SELECT id FROM requisitores WHERE empleado_id = ?"
        );
        $existing->execute([$empleadoId]);
        $row = $existing->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            $this->pdo()->prepare(
                "UPDATE requisitores SET activo = 1 WHERE id = ?"
            )->execute([$row['id']]);
        } else {
            $this->pdo()->prepare(
                "INSERT INTO requisitores (empleado_id, activo) VALUES (?, 1)"
            )->execute([$empleadoId]);
        }
    }

    private function upsertComprador(int $empleadoId): void
    {
        $existing = $this->pdo()->prepare(
            "SELECT id FROM compradores WHERE empleado_id = ?"
        );
        $existing->execute([$empleadoId]);
        $row = $existing->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            $this->pdo()->prepare(
                "UPDATE compradores SET activo = 1 WHERE id = ?"
            )->execute([$row['id']]);
        } else {
            $this->pdo()->prepare(
                "INSERT INTO compradores (empleado_id, activo) VALUES (?, 1)"
            )->execute([$empleadoId]);
        }
    }

    /**
     * Expose PDO instance (convenience shorthand for private helpers).
     */
    private function pdo(): \PDO
    {
        return Database::getInstance();
    }
}
