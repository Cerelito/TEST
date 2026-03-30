<?php

class OrganigramaController extends Controller
{
    private Empleado $empleadoModel;

    public function __construct()
    {
        requireAuth();
        $this->empleadoModel = new Empleado();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // index — render organigrama view with empresas list
    // ─────────────────────────────────────────────────────────────────────────

    public function index(): void
    {
        $empresas = $this->empleadoModel->getEmpresasList();

        $this->render('organigrama/index', [
            'title'    => 'Organigrama',
            'empresas' => $empresas,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // data — AJAX JSON endpoint
    // GET param: empresa_id
    // Returns hierarchical employee data
    // ─────────────────────────────────────────────────────────────────────────

    public function data(): void
    {
        $empresaId = (int)($_GET['empresa_id'] ?? 0);

        if (!$empresaId) {
            $this->json(['ok' => false, 'error' => 'empresa_id requerido'], 400);
        }

        $jerarquia = $this->empleadoModel->getForOrganigrama($empresaId);

        $this->json([
            'ok'   => true,
            'data' => $jerarquia,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Legacy / alias actions kept for backward-compat
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Flat JSON list of all org nodes (legacy endpoint).
     */
    public function api(): void
    {
        $nodos = $this->empleadoModel->getOrganigramaNodos();
        $this->json(['ok' => true, 'data' => $nodos]);
    }

    /**
     * Save an organigrama node (admin only).
     */
    public function guardarNodo(): void
    {
        requireRole(['admin']);
        verifyCSRF();

        $pdo      = Database::getInstance();
        $id       = (int)($_POST['id']          ?? 0);
        $empId    = (int)($_POST['empleado_id'] ?? 0) ?: null;
        $parentId = (int)($_POST['parent_id']   ?? 0) ?: null;
        $titulo   = sanitize($_POST['titulo']      ?? '');
        $desc     = sanitize($_POST['descripcion'] ?? '');
        $nivel    = (int)($_POST['nivel'] ?? 0);
        $orden    = (int)($_POST['orden'] ?? 0);

        if ($id) {
            $pdo->prepare(
                "UPDATE organigrama_nodos
                 SET empleado_id=?, parent_id=?, titulo=?, descripcion=?,
                     nivel=?, orden=?, updated_at=NOW()
                 WHERE id=?"
            )->execute([$empId, $parentId, $titulo, $desc, $nivel, $orden, $id]);
        } else {
            $pdo->prepare(
                "INSERT INTO organigrama_nodos
                     (empleado_id, parent_id, titulo, descripcion, nivel, orden, activo)
                 VALUES (?,?,?,?,?,?,1)"
            )->execute([$empId, $parentId, $titulo, $desc, $nivel, $orden]);
            $id = (int)$pdo->lastInsertId();
        }

        if (!empty($_POST['ajax'])) {
            $this->json(['ok' => true, 'id' => $id]);
        } else {
            setFlash('success', 'Nodo guardado.');
            redirect('organigrama');
        }
    }

    /**
     * Soft-delete an organigrama node.
     */
    public function eliminarNodo($id): void
    {
        requireRole(['admin']);
        verifyCSRF();

        Database::getInstance()
            ->prepare("UPDATE organigrama_nodos SET activo = 0 WHERE id = ?")
            ->execute([$id]);

        $this->json(['ok' => true]);
    }
}
