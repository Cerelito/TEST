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
    // plantilla — download CSV template
    // ─────────────────────────────────────────────────────────────────────────

    public function plantilla(): void
    {
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="plantilla_modulos_erp.csv"');
        header('Cache-Control: no-cache');

        $out = fopen('php://output', 'w');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM so Excel opens with correct encoding
        fputcsv($out, ['nombre', 'clave', 'parent_clave', 'orden', 'es_separador']);
        fputcsv($out, ['Compras',             'compras',              '',              '0', '0']);
        fputcsv($out, ['Requisiciones',       'compras.req',          'compras',       '1', '0']);
        fputcsv($out, ['Crear Requisición',   'compras.req.crear',    'compras.req',   '1', '0']);
        fputcsv($out, ['Ver Requisiciones',   'compras.req.ver',      'compras.req',   '2', '0']);
        fputcsv($out, ['Órdenes de Compra',   'compras.oc',           'compras',       '2', '0']);
        fputcsv($out, ['--- Separador ---',   'compras.sep1',         'compras',       '3', '1']);
        fclose($out);
        exit;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // importar — process CSV upload (POST)
    // ─────────────────────────────────────────────────────────────────────────

    public function importar(): void
    {
        verifyCSRF();

        if (empty($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            setFlash('error', 'No se recibió ningún archivo o hubo un error en la subida.');
            redirect('modulos-erp');
            return;
        }

        $ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            setFlash('error', 'Solo se aceptan archivos .csv — en Excel usa "Guardar como → CSV UTF-8".');
            redirect('modulos-erp');
            return;
        }

        $handle = fopen($_FILES['archivo']['tmp_name'], 'r');
        if (!$handle) {
            setFlash('error', 'No se pudo leer el archivo.');
            redirect('modulos-erp');
            return;
        }

        // Strip UTF-8 BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            setFlash('error', 'El archivo está vacío o no tiene encabezado.');
            redirect('modulos-erp');
            return;
        }

        $header    = array_map(fn($h) => strtolower(trim($h)), $header);
        $nameIdx   = array_search('nombre',       $header);
        $claveIdx  = array_search('clave',        $header);
        $parentIdx = array_search('parent_clave', $header);
        $ordenIdx  = array_search('orden',        $header);
        $sepIdx    = array_search('es_separador', $header);

        if ($nameIdx === false) {
            fclose($handle);
            setFlash('error', 'El CSV no tiene columna "nombre". Descarga la plantilla y úsala como base.');
            redirect('modulos-erp');
            return;
        }

        // Read all rows
        $rows = [];
        $rowNum = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            if (count(array_filter($row, fn($c) => trim($c) !== '')) === 0) continue;
            $rows[] = ['data' => $row, 'num' => $rowNum];
        }
        fclose($handle);

        if (empty($rows)) {
            setFlash('error', 'El archivo no contiene datos (solo encabezado).');
            redirect('modulos-erp');
            return;
        }

        $pdo = Database::getInstance();

        // Seed clave map with existing modules
        $claveMap = $pdo->query("SELECT clave, id FROM modulos_erp")
            ->fetchAll(\PDO::FETCH_KEY_PAIR);

        $inserted = 0;
        $skipped  = 0;
        $warnings = [];

        // Multi-pass: up to N passes to resolve parent dependencies regardless of row order
        $pending  = $rows;
        $maxPasses = count($rows) + 1;

        for ($pass = 0; $pass < $maxPasses && !empty($pending); $pass++) {
            $nextPending = [];

            foreach ($pending as $item) {
                $row    = $item['data'];
                $rowNum = $item['num'];

                $nombre      = trim($row[$nameIdx] ?? '');
                $claveInput  = $claveIdx  !== false ? trim($row[$claveIdx]  ?? '') : '';
                $parentClave = $parentIdx !== false ? trim($row[$parentIdx] ?? '') : '';
                $orden       = $ordenIdx  !== false ? (int)($row[$ordenIdx] ?? 0)  : 0;
                $esSep       = $sepIdx    !== false ? ((int)($row[$sepIdx]  ?? 0) ? 1 : 0) : 0;

                if ($nombre === '') {
                    $warnings[] = "Fila $rowNum: nombre vacío — omitida.";
                    $skipped++;
                    continue;
                }

                // Resolve parent_id
                $parentId = null;
                if ($parentClave !== '') {
                    if (isset($claveMap[$parentClave])) {
                        $parentId = (int)$claveMap[$parentClave];
                    } else {
                        $nextPending[] = $item; // retry next pass
                        continue;
                    }
                }

                // Build clave
                if ($claveInput === '') {
                    $slug = $this->toSlug($nombre);
                    $claveInput = $parentClave !== '' ? $parentClave . '.' . $slug : $slug;
                }

                // Skip duplicates
                if (isset($claveMap[$claveInput])) {
                    $warnings[] = "Fila $rowNum: clave «$claveInput» ya existe — omitida.";
                    $skipped++;
                    continue;
                }

                $pdo->prepare(
                    "INSERT INTO modulos_erp (parent_id, clave, nombre, orden, es_separador, activo)
                     VALUES (?, ?, ?, ?, ?, 1)"
                )->execute([$parentId, $claveInput, $nombre, $orden, $esSep]);

                $newId = (int)$pdo->lastInsertId();
                $claveMap[$claveInput] = $newId;
                $inserted++;
            }

            $pending = $nextPending;
        }

        // Anything still pending = unresolvable parent
        foreach ($pending as $item) {
            $row         = $item['data'];
            $nombre      = trim($row[$nameIdx] ?? '');
            $parentClave = $parentIdx !== false ? trim($row[$parentIdx] ?? '') : '';
            $warnings[]  = "Fila {$item['num']}: padre «$parentClave» no encontrado para «$nombre» — omitida.";
            $skipped++;
        }

        logAction('importar', 'modulos_erp', "CSV: $inserted módulos creados, $skipped omitidos.");

        $msg = "<strong>$inserted módulos importados</strong>" . ($skipped ? ", $skipped omitidos" : '');
        if (!empty($warnings)) {
            $msg .= '<br><small style="opacity:.8">' . implode('<br>', array_slice($warnings, 0, 8)) . '</small>';
        }

        setFlash($inserted > 0 ? 'success' : 'error', $msg);
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
