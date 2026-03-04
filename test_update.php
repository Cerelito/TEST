<?php
define('ROOT_PATH', __DIR__ . '/ek_crm_core/');
require_once ROOT_PATH . 'app/config/config.php';
require_once ROOT_PATH . 'app/config/db.php';
require_once ROOT_PATH . 'app/models/Proveedor.php';

// Set a dummy user ID for logging/auditing if needed
if (!function_exists('usuarioId')) {
    function usuarioId()
    {
        return 1;
    }
}
if (!function_exists('logSeguridad')) {
    function logSeguridad($a, $b, $c, $d)
    {
    }
}

try {
    $prov = new Proveedor();
    // Get an existing provider to update (e.g. ID 5520 from the URL in screenshot)
    // Or just fetch the first one
    $db = (new Database())->getConnection();
    $stmt = $db->query("SELECT * FROM Proveedores LIMIT 1");
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existing) {
        die("No providers found to test update.");
    }

    echo "Testing update for ID: " . $existing['Id'] . "\n";

    $updateData = [
        'IdManual' => $existing['IdManual'],
        'RFC' => $existing['RFC'],
        'TipoPersona' => $existing['TipoPersona'],
        'TipoProveedor' => $existing['TipoProveedor'],
        'RazonSocial' => $existing['RazonSocial'],
        'NombreComercial' => $existing['NombreComercial'],
        'Nombre' => $existing['Nombre'],
        'ApellidoPaterno' => $existing['ApellidoPaterno'],
        'ApellidoMaterno' => $existing['ApellidoMaterno'],
        'RegimenFiscalId' => $existing['RegimenFiscalId'], // Keep existing
        'CorreoPagosInterno' => 'test@example.com',
        'CorreoProveedor' => 'test@example.com',
        'Responsable' => 'TEST USER',
        'LimiteCredito' => 1000,
        'Calle' => 'Test Street',
        'NumeroExterior' => '123',
        'NumeroInterior' => '',
        'Colonia' => 'Test Col',
        'CP' => '12345',
        'Estado' => 'CDMX',
        'Municipio' => 'Cuauhtemoc',
        'RutaConstancia' => '',
        'RutaCaratula' => '',
        'Estatus' => 'ACTIVO',
        'UsuarioCreadorId' => 1
    ];

    if ($prov->update($existing['Id'], $updateData)) {
        echo "Update SUCCESS!\n";
    } else {
        echo "Update FAILED!\n";
    }

} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
