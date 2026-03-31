<?php
// ─────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'erickedu_ekpermisos');
define('DB_USER', 'erickedu_cerelito');
define('DB_PASS', 'Syndulla25@');
// ─────────────────────────────────────────────

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: https://apotemaone.com');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

function jsonError($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $msg]);
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    jsonError('DB error: '.$e->getMessage(), 500);
}

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// ── GET ALL ──────────────────────────────────────────────────
if ($method === 'GET' && $action === 'load') {
    // Join con empresas y centros_costo para obtener el nombre oficial si existe
    // Agregamos un fallback global por si el CC no existe en esa empresa específica pero sí en otra
    $rows = $pdo->query("
        SELECT p.*, 
               c.descripcion as official_desc,
               (SELECT c2.descripcion FROM centros_costo c2 WHERE c2.codigo = p.cc LIMIT 1) as global_desc
        FROM permisos p
        LEFT JOIN empresas e ON p.empresa = e.nombre
        LEFT JOIN centros_costo c ON e.id_empresa = c.id_empresa AND p.cc = c.codigo
        ORDER BY p.empresa, p.usuario, p.cc
    ")->fetchAll();

    $result = array_map(function($r) {
        return [
            'id_row'   => (int)$r['id_row'],
            'empresa'  => $r['empresa'],
            'id'       => $r['user_id'],
            'usuario'  => $r['usuario'],
            'perfil'   => $r['perfil'],
            'cc'       => $r['cc'],
            'desc'     => $r['official_desc'] ?? $r['global_desc'] ?? $r['descripcion'],
            'elab'     => (bool)$r['elab'],
            'vobo'     => (bool)$r['vobo'],
            'aut'      => (bool)$r['aut'],
            'monto'    => (float)$r['monto'],
        ];
    }, $rows);
    echo json_encode(['ok' => true, 'data' => $result, 'total' => count($result)]);
    exit;
}

// ── GET EMPRESAS ─────────────────────────────────────────────
if ($method === 'GET' && $action === 'get_empresas') {
    $rows = $pdo->query("SELECT * FROM empresas ORDER BY nombre")->fetchAll();
    echo json_encode(['ok' => true, 'data' => $rows]);
    exit;
}

// ── SAVE EMPRESA ──────────────────────────────────────────────
if ($method === 'POST' && $action === 'save_empresa') {
    $b = json_decode(file_get_contents('php://input'), true);
    if (empty($b['nombre'])) jsonError('Nombre requerido');
    
    if (!empty($b['id_empresa'])) {
        $stmt = $pdo->prepare("UPDATE empresas SET nombre=:nombre WHERE id_empresa=:id");
        $stmt->execute([':nombre' => $b['nombre'], ':id' => $b['id_empresa']]);
        echo json_encode(['ok' => true, 'action' => 'updated']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO empresas (nombre) VALUES (:nombre)");
        $stmt->execute([':nombre' => $b['nombre']]);
        echo json_encode(['ok' => true, 'action' => 'inserted', 'id' => $pdo->lastInsertId()]);
    }
    exit;
}

// ── GET CCS ──────────────────────────────────────────────────
if ($method === 'GET' && $action === 'get_ccs') {
    $emp_id = $_GET['id_empresa'] ?? '';
    if ($emp_id) {
        $stmt = $pdo->prepare("SELECT * FROM centros_costo WHERE id_empresa=:id ORDER BY codigo");
        $stmt->execute([':id' => $emp_id]);
        $rows = $stmt->fetchAll();
    } else {
        $rows = $pdo->query("SELECT * FROM centros_costo ORDER BY codigo")->fetchAll();
    }
    echo json_encode(['ok' => true, 'data' => $rows]);
    exit;
}

// ── SAVE CC ──────────────────────────────────────────────────
if ($method === 'POST' && $action === 'save_cc') {
    $b = json_decode(file_get_contents('php://input'), true);
    if (empty($b['id_empresa']) || empty($b['codigo'])) jsonError('Datos incompletos');
    
    if (!empty($b['id_cc'])) {
        $stmt = $pdo->prepare("UPDATE centros_costo SET codigo=:c, descripcion=:d, id_empresa=:e WHERE id_cc=:id");
        $stmt->execute([':c' => $b['codigo'], ':d' => $b['descripcion'] ?? '', ':e' => $b['id_empresa'], ':id' => $b['id_cc']]);
        echo json_encode(['ok' => true, 'action' => 'updated']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO centros_costo (id_empresa, codigo, descripcion) VALUES (:e, :c, :d)");
        $stmt->execute([':e' => $b['id_empresa'], ':c' => $b['codigo'], ':d' => $b['descripcion'] ?? '']);
        echo json_encode(['ok' => true, 'action' => 'inserted', 'id' => $pdo->lastInsertId()]);
    }
    exit;
}

// ── FIND USER (AUTOFILL) ────────────────────────────────────
if ($method === 'GET' && $action === 'find_user') {
    $uid = $_GET['user_id'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM usuarios_master WHERE external_id=:uid LIMIT 1");
    $stmt->execute([':uid' => $uid]);
    $u = $stmt->fetch();
    echo json_encode(['ok' => true, 'data' => $u]);
    exit;
}

// ── FIND CC (AUTOFILL) ──────────────────────────────────────
if ($method === 'GET' && $action === 'find_cc') {
    $code = $_GET['cc'] ?? '';
    $emp_id = $_GET['id_empresa'] ?? '';
    if ($emp_id) {
        $stmt = $pdo->prepare("SELECT * FROM centros_costo WHERE id_empresa=:e AND codigo=:c LIMIT 1");
        $stmt->execute([':e' => $emp_id, ':c' => $code]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM centros_costo WHERE codigo=:c LIMIT 1");
        $stmt->execute([':c' => $code]);
    }
    $cc = $stmt->fetch();
    echo json_encode(['ok' => true, 'data' => $cc]);
    exit;
}

// ── SAVE (INSERT or UPDATE PERMISO) ─────────────────────────
if ($method === 'POST' && $action === 'save') {
    $b = json_decode(file_get_contents('php://input'), true);
    if (!$b) jsonError('Invalid payload');

    // Registrar en Master catalogs si no existen
    if (!empty($b['id']) && !empty($b['usuario'])) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO usuarios_master (external_id, nombre, perfil_defecto) VALUES (:uid, :nom, :per)");
        $stmt->execute([':uid' => $b['id'], ':nom' => $b['usuario'], ':per' => $b['perfil'] ?? '']);
    }

    if (!empty($b['id_row'])) {
        // UPDATE
        $stmt = $pdo->prepare("UPDATE permisos SET
            perfil=:perfil, elab=:elab, vobo=:vobo, aut=:aut, monto=:monto
            WHERE id_row=:id_row");
        $stmt->execute([
            ':perfil'  => $b['perfil']  ?? '',
            ':elab'    => (int)($b['elab'] ?? 0),
            ':vobo'    => (int)($b['vobo'] ?? 0),
            ':aut'     => (int)($b['aut']  ?? 0),
            ':monto'   => (float)($b['monto'] ?? 0),
            ':id_row'  => (int)$b['id_row'],
        ]);
        echo json_encode(['ok' => true, 'action' => 'updated']);
    } else {
        // INSERT - Validar CC y Empresa
        $empresa_nom = $b['empresa'] ?? '';
        $cc_cod = $b['cc'] ?? '';

        // 1. Obtener id_empresa
        $st_emp = $pdo->prepare("SELECT id_empresa FROM empresas WHERE nombre = :nom LIMIT 1");
        $st_emp->execute([':nom' => $empresa_nom]);
        $emp = $st_emp->fetch();
        if (!$emp) jsonError("La empresa '$empresa_nom' no está registrada en configuración.");

        // 2. Validar CC existe para esa empresa
        $st_cc = $pdo->prepare("SELECT descripcion FROM centros_costo WHERE id_empresa = :e AND codigo = :c LIMIT 1");
        $st_cc->execute([':e' => $emp['id_empresa'], ':c' => $cc_cod]);
        $cc_data = $st_cc->fetch();
        if (!$cc_data) jsonError("El Centro de Costo '$cc_cod' no está registrado para la empresa '$empresa_nom' en configuración.");

        // Usar descripción oficial
        $descripcion_final = $cc_data['descripcion'];

        $stmt = $pdo->prepare("INSERT INTO permisos
            (empresa, user_id, usuario, perfil, cc, descripcion, elab, vobo, aut, monto)
            VALUES (:empresa,:user_id,:usuario,:perfil,:cc,:desc,:elab,:vobo,:aut,:monto)");
        $stmt->execute([
            ':empresa' => $empresa_nom,
            ':user_id' => $b['id']       ?? '',
            ':usuario' => $b['usuario']  ?? '',
            ':perfil'  => $b['perfil']   ?? '',
            ':cc'      => $cc_cod,
            ':desc'    => $descripcion_final,
            ':elab'    => (int)($b['elab'] ?? 0),
            ':vobo'    => (int)($b['vobo'] ?? 0),
            ':aut'     => (int)($b['aut']  ?? 0),
            ':monto'   => (float)($b['monto'] ?? 0),
        ]);
        echo json_encode(['ok' => true, 'action' => 'inserted', 'id_row' => (int)$pdo->lastInsertId()]);
    }
    exit;
}

// ── DELETE ───────────────────────────────────────────────────
if ($method === 'POST' && $action === 'delete') {
    $b = json_decode(file_get_contents('php://input'), true);
    if (!$b || empty($b['id_row'])) jsonError('Missing id_row');
    $stmt = $pdo->prepare("DELETE FROM permisos WHERE id_row=:id_row");
    $stmt->execute([':id_row' => (int)$b['id_row']]);
    echo json_encode(['ok' => true]);
    exit;
}

jsonError('Unknown action');
