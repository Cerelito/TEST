<?php

class ModulosErpController extends Controller
{
    public function __construct()
    {
        requireAuth();
        requireRole(['admin', 'superadmin']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // index — tree view
    // ─────────────────────────────────────────────────────────────────────────

    public function index(): void
    {
        $pdo = Database::getInstance();

        $all = $pdo->query(
            "SELECT m.*,
                    (SELECT COUNT(*) FROM modulos_erp c WHERE c.parent_id = m.id)           AS hijos_count,
                    (SELECT COUNT(*) FROM programa_nivel_permisos p WHERE p.modulo_erp_id = m.id) AS permisos_count
             FROM modulos_erp m
             ORDER BY ISNULL(m.parent_id) DESC, COALESCE(m.parent_id,0), m.orden, m.nombre"
        )->fetchAll(\PDO::FETCH_ASSOC);

        $tree     = $this->buildTree($all);
        $flatList = $this->flattenTree($tree);

        $this->render('modulos-erp/index', [
            'title'    => 'Módulos ERP',
            'tree'     => $tree,
            'flatList' => $flatList,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // guardar — create (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function guardar(): void
    {
        verifyCSRF();

        $nombre   = sanitize($_POST['nombre']   ?? '');
        $parentId = (int)($_POST['parent_id']   ?? 0) ?: null;
        $orden    = (int)($_POST['orden']        ?? 0);
        $esSep    = isset($_POST['es_separador']) ? 1 : 0;
        $clave    = trim($_POST['clave']         ?? '');

        if (!$nombre) {
            setFlash('error', 'El nombre es obligatorio.');
            redirect('modulos-erp');
        }

        $pdo = Database::getInstance();

        // Auto-generate clave if empty
        if (!$clave) {
            $slug = $this->toSlug($nombre);
            if ($parentId) {
                $stmt = $pdo->prepare("SELECT clave FROM modulos_erp WHERE id = ?");
                $stmt->execute([$parentId]);
                $parentClave = $stmt->fetchColumn();
                $clave = $parentClave ? $parentClave . '.' . $slug : $slug;
            } else {
                $clave = $slug;
            }
        }

        // Ensure clave is unique — append suffix if needed
        $baseClave = $clave;
        $suffix    = 1;
        while ($this->claveExists($pdo, $clave)) {
            $clave = $baseClave . '_' . $suffix++;
        }

        $pdo->prepare(
            "INSERT INTO modulos_erp (parent_id, clave, nombre, orden, es_separador, activo)
             VALUES (?, ?, ?, ?, ?, 1)"
        )->execute([$parentId, $clave, $nombre, $orden, $esSep]);

        logAction('crear', 'modulos_erp', "Módulo creado: $nombre ($clave)");
        setFlash('success', "Módulo <strong>$nombre</strong> creado correctamente.");
        redirect('modulos-erp');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // editar — show edit form
    // ─────────────────────────────────────────────────────────────────────────

    public function editar($id): void
    {
        $pdo    = Database::getInstance();
        $modulo = $this->findModulo($pdo, (int)$id);

        if (!$modulo) {
            setFlash('error', 'Módulo no encontrado.');
            redirect('modulos-erp');
        }

        $all      = $pdo->query("SELECT * FROM modulos_erp ORDER BY ISNULL(parent_id) DESC, COALESCE(parent_id,0), orden, nombre")->fetchAll(\PDO::FETCH_ASSOC);
        $tree     = $this->buildTree($all);
        $flatList = $this->flattenTree($tree);

        // Remove self and all descendants from parent selector
        $descendants = $this->getDescendantIds($pdo, (int)$id);
        $flatList = array_filter($flatList, fn($m) => !in_array((int)$m['id'], $descendants) && (int)$m['id'] !== (int)$id);

        $this->render('modulos-erp/editar', [
            'title'    => 'Editar Módulo: ' . htmlspecialchars($modulo['nombre']),
            'modulo'   => $modulo,
            'flatList' => array_values($flatList),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // actualizar — update (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function actualizar($id): void
    {
        verifyCSRF();

        $pdo    = Database::getInstance();
        $modulo = $this->findModulo($pdo, (int)$id);

        if (!$modulo) {
            setFlash('error', 'Módulo no encontrado.');
            redirect('modulos-erp');
        }

        $nombre   = sanitize($_POST['nombre']    ?? '');
        $parentId = (int)($_POST['parent_id']    ?? 0) ?: null;
        $orden    = (int)($_POST['orden']         ?? 0);
        $esSep    = isset($_POST['es_separador']) ? 1 : 0;
        $activo   = isset($_POST['activo'])       ? 1 : 0;
        $clave    = trim($_POST['clave']          ?? '');

        if (!$nombre || !$clave) {
            setFlash('error', 'Nombre y clave son obligatorios.');
            redirect('modulos-erp/editar/' . $id);
        }

        // Check clave uniqueness (excluding self)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM modulos_erp WHERE clave = ? AND id != ?");
        $stmt->execute([$clave, (int)$id]);
        if ((int)$stmt->fetchColumn() > 0) {
            setFlash('error', "La clave '$clave' ya está en uso por otro módulo.");
            redirect('modulos-erp/editar/' . $id);
        }

        // Prevent circular parent (can't set parent to own descendant)
        if ($parentId) {
            $descendants = $this->getDescendantIds($pdo, (int)$id);
            if (in_array($parentId, $descendants) || $parentId === (int)$id) {
                setFlash('error', 'No puedes asignar un descendiente como padre.');
                redirect('modulos-erp/editar/' . $id);
            }
        }

        $pdo->prepare(
            "UPDATE modulos_erp
             SET parent_id=?, clave=?, nombre=?, orden=?, es_separador=?, activo=?
             WHERE id=?"
        )->execute([$parentId, $clave, $nombre, $orden, $esSep, $activo, (int)$id]);

        logAction('editar', 'modulos_erp', "Módulo actualizado: $nombre (ID: $id)");
        setFlash('success', "Módulo <strong>$nombre</strong> actualizado.");
        redirect('modulos-erp');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // eliminar — delete (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function eliminar($id): void
    {
        verifyCSRF();

        $pdo    = Database::getInstance();
        $modulo = $this->findModulo($pdo, (int)$id);

        if (!$modulo) {
            setFlash('error', 'Módulo no encontrado.');
            redirect('modulos-erp');
        }

        // Count children
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM modulos_erp WHERE parent_id = ?");
        $stmt->execute([(int)$id]);
        $hijos = (int)$stmt->fetchColumn();

        if ($hijos > 0) {
            setFlash('error', "No se puede eliminar: este módulo tiene $hijos submódulo(s). Elimínalos primero.");
            redirect('modulos-erp');
        }

        // Remove permisos and then the module
        $pdo->prepare("DELETE FROM programa_nivel_permisos WHERE modulo_erp_id = ?")->execute([(int)$id]);
        $pdo->prepare("DELETE FROM modulos_erp WHERE id = ?")->execute([(int)$id]);

        logAction('eliminar', 'modulos_erp', "Módulo eliminado: {$modulo['nombre']} (ID: $id)");
        setFlash('success', "Módulo <strong>{$modulo['nombre']}</strong> eliminado.");
        redirect('modulos-erp');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // toggleActivo — toggle active flag (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function toggleActivo($id): void
    {
        verifyCSRF();

        $pdo = Database::getInstance();
        $pdo->prepare("UPDATE modulos_erp SET activo = IF(activo=1,0,1) WHERE id = ?")->execute([(int)$id]);

        logAction('toggle', 'modulos_erp', "Toggle activo módulo ID: $id");
        redirect('modulos-erp');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // descargarPlantilla — send CSV template (GET)
    // ─────────────────────────────────────────────────────────────────────────

    public function descargarPlantilla(): void
    {
        $pdo = Database::getInstance();

        // Load all modules ordered so parents always come before children
        $dbRows = $pdo->query(
            "SELECT m.nombre, m.clave, m.orden, m.es_separador,
                    p.clave AS parent_clave
             FROM modulos_erp m
             LEFT JOIN modulos_erp p ON p.id = m.parent_id
             ORDER BY ISNULL(m.parent_id) DESC, COALESCE(m.parent_id, 0), m.orden, m.nombre"
        )->fetchAll(\PDO::FETCH_ASSOC);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="modulos_erp_export.csv"');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // BOM for Excel UTF-8

        fputcsv($out, ['nombre', 'clave', 'parent_clave', 'orden', 'es_separador']);

        if (!empty($dbRows)) {
            // Export existing modules from DB
            foreach ($dbRows as $r) {
                fputcsv($out, [
                    $r['nombre'],
                    $r['clave'],
                    $r['parent_clave'] ?? '',
                    $r['orden'],
                    $r['es_separador'],
                ]);
            }
        } else {
            // DB is empty — export example rows so user has a reference
            $examples = [
                ['Ventas',               'ventas',                        '',                          1, 0],
                ['Clientes',             'ventas.clientes',               'ventas',                    1, 0],
                ['Alta de Clientes',     'ventas.clientes.alta',          'ventas.clientes',           1, 0],
                ['Consulta',             'ventas.clientes.consulta',      'ventas.clientes',           2, 0],
                ['Pedidos',              'ventas.pedidos',                'ventas',                    2, 0],
                ['Nuevo Pedido',         'ventas.pedidos.nuevo',          'ventas.pedidos',            1, 0],
                ['Autorizar',            'ventas.pedidos.autorizar',      'ventas.pedidos',            2, 0],
                ['Recursos Humanos',     'rrhh',                          '',                          2, 0],
                ['Empleados',            'rrhh.empleados',                'rrhh',                      1, 0],
                ['Expediente',           'rrhh.empleados.expediente',     'rrhh.empleados',            1, 0],
                ['Documentos',           'rrhh.empleados.expediente.docs','rrhh.empleados.expediente', 1, 0],
                ['Contratos',            'rrhh.empleados.expediente.cont','rrhh.empleados.expediente', 2, 0],
                ['Nómina',               'rrhh.nomina',                   'rrhh',                      2, 0],
                ['Contabilidad',         'contabilidad',                  '',                          3, 0],
                ['Pólizas',              'contabilidad.polizas',          'contabilidad',              1, 0],
                ['Ingresos',             'contabilidad.polizas.ingresos', 'contabilidad.polizas',      1, 0],
                ['Egresos',              'contabilidad.polizas.egresos',  'contabilidad.polizas',      2, 0],
            ];
            foreach ($examples as $row) {
                fputcsv($out, $row);
            }
        }

        fclose($out);
        exit;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // importarCsv — process CSV upload (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function importarCsv(): void
    {
        verifyCSRF();

        if (empty($_FILES['csv_file']['tmp_name'])) {
            setFlash('error', 'No se recibió ningún archivo.');
            redirect('modulos-erp');
        }

        $tmpFile = $_FILES['csv_file']['tmp_name'];
        $handle  = fopen($tmpFile, 'r');
        if (!$handle) {
            setFlash('error', 'No se pudo abrir el archivo.');
            redirect('modulos-erp');
        }

        // Strip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Read header row
        $header = fgetcsv($handle);
        if (!$header) {
            setFlash('error', 'Archivo CSV vacío o inválido.');
            fclose($handle);
            redirect('modulos-erp');
        }

        // Normalize headers
        $header = array_map('trim', array_map('strtolower', $header));
        $colIdx = [];
        foreach (['nombre', 'clave', 'parent_clave', 'orden', 'es_separador'] as $col) {
            $idx = array_search($col, $header);
            $colIdx[$col] = ($idx !== false) ? $idx : -1;
        }

        if ($colIdx['nombre'] === -1) {
            setFlash('error', 'El CSV debe tener al menos la columna "nombre".');
            fclose($handle);
            redirect('modulos-erp');
        }

        $rows = [];
        while (($line = fgetcsv($handle)) !== false) {
            $nombre      = trim($line[$colIdx['nombre']] ?? '');
            $clave       = trim($line[$colIdx['clave']]  ?? '');
            $parentClave = trim($line[$colIdx['parent_clave']] ?? '');
            $orden       = (int)($line[$colIdx['orden']] ?? 0);
            $esSep       = (int)($line[$colIdx['es_separador']] ?? 0);

            if (!$nombre) continue;

            $rows[] = compact('nombre', 'clave', 'parentClave', 'orden', 'esSep');
        }
        fclose($handle);

        if (empty($rows)) {
            setFlash('error', 'El archivo no contiene filas válidas.');
            redirect('modulos-erp');
        }

        $pdo       = Database::getInstance();
        $created   = 0;
        $updated   = 0;
        $maxPasses = 15;

        // Index existing claves → id
        $existing = [];
        $stmt = $pdo->query("SELECT id, clave FROM modulos_erp");
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $r) {
            $existing[$r['clave']] = (int)$r['id'];
        }

        // Build a queue; resolve in multiple passes (handles out-of-order parents)
        $queue = $rows;
        $pass  = 0;

        while (!empty($queue) && $pass < $maxPasses) {
            $pass++;
            $next = [];

            foreach ($queue as $row) {
                $nombre      = $row['nombre'];
                $parentClave = $row['parentClave'];
                $orden       = $row['orden'];
                $esSep       = $row['esSep'];

                // Resolve clave
                $clave = $row['clave'];
                if (!$clave) {
                    $slug  = $this->toSlug($nombre);
                    $clave = $parentClave ? ($parentClave . '.' . $slug) : $slug;
                }

                // Resolve parent
                $parentId = null;
                if ($parentClave) {
                    if (!isset($existing[$parentClave])) {
                        // Parent not yet inserted — retry later
                        $next[] = $row;
                        continue;
                    }
                    $parentId = $existing[$parentClave];
                }

                if (isset($existing[$clave])) {
                    // UPDATE existing module
                    $pdo->prepare(
                        "UPDATE modulos_erp
                         SET nombre = ?, parent_id = ?, orden = ?, es_separador = ?
                         WHERE clave = ?"
                    )->execute([$nombre, $parentId, $orden, $esSep, $clave]);
                    $updated++;
                } else {
                    // INSERT new module
                    $baseClave = $clave;
                    $suffix    = 1;
                    while ($this->claveExists($pdo, $clave)) {
                        $clave = $baseClave . '_' . $suffix++;
                    }

                    $pdo->prepare(
                        "INSERT INTO modulos_erp (parent_id, clave, nombre, orden, es_separador, activo)
                         VALUES (?, ?, ?, ?, ?, 1)"
                    )->execute([$parentId, $clave, $nombre, $orden, $esSep]);

                    $existing[$clave] = (int)$pdo->lastInsertId();
                    $created++;
                }
            }

            $queue = $next;
        }

        $unresolved = count($queue);
        $msg = "Importación completada: $created módulo(s) creado(s), $updated actualizado(s).";
        if ($unresolved > 0) {
            $msg .= " $unresolved fila(s) no resueltas (padre no encontrado).";
        }

        logAction('importar', 'modulos_erp', $msg);
        setFlash('success', $msg);
        redirect('modulos-erp');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function findModulo(\PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare("SELECT * FROM modulos_erp WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function claveExists(\PDO $pdo, string $clave): bool
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM modulos_erp WHERE clave = ?");
        $stmt->execute([$clave]);
        return (int)$stmt->fetchColumn() > 0;
    }

    private function getDescendantIds(\PDO $pdo, int $parentId): array
    {
        $ids  = [];
        $stmt = $pdo->prepare("SELECT id FROM modulos_erp WHERE parent_id = ?");
        $stmt->execute([$parentId]);
        foreach ($stmt->fetchAll(\PDO::FETCH_COLUMN) as $childId) {
            $ids[] = (int)$childId;
            $ids   = array_merge($ids, $this->getDescendantIds($pdo, (int)$childId));
        }
        return $ids;
    }

    private function buildTree(array $items, $parentId = null): array
    {
        $tree = [];
        foreach ($items as $item) {
            $ip = $item['parent_id'] === null ? null : (int)$item['parent_id'];
            if ($ip === $parentId) {
                $item['children'] = $this->buildTree($items, (int)$item['id']);
                $tree[] = $item;
            }
        }
        usort($tree, fn($a, $b) => (int)($a['orden'] ?? 0) <=> (int)($b['orden'] ?? 0));
        return $tree;
    }

    private function flattenTree(array $tree, int $depth = 0): array
    {
        $flat = [];
        foreach ($tree as $node) {
            $children = $node['children'] ?? [];
            unset($node['children']);
            $node['depth'] = $depth;
            $flat[] = $node;
            $flat   = array_merge($flat, $this->flattenTree($children, $depth + 1));
        }
        return $flat;
    }

    private function toSlug(string $str): string
    {
        $str  = mb_strtolower(trim($str));
        $from = ['á','é','í','ó','ú','ü','ñ','à','è','ì','ò','ù','â','ê','î','ô','û'];
        $to   = ['a','e','i','o','u','u','n','a','e','i','o','u','a','e','i','o','u'];
        $str  = str_replace($from, $to, $str);
        $str  = preg_replace('/[^a-z0-9]+/', '_', $str);
        return trim($str, '_');
    }
}
