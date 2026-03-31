<?php
define('ROOT_PATH', 'c:/Users/ecruz/OneDrive - UrbanoPark/Escritorio/EKPROV/ek_crm_core/');
require 'c:/Users/ecruz/OneDrive - UrbanoPark/Escritorio/EKPROV/ek_crm_core/app/config/config.php';
require 'c:/Users/ecruz/OneDrive - UrbanoPark/Escritorio/EKPROV/ek_crm_core/app/config/db.php';

try {
    $db = (new Database())->getConnection();
    $stmt = $db->query('DESCRIBE Proveedores');
    echo "Field\tType\tNull\tKey\tDefault\tExtra\n";
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
        echo "{$r['Field']}\t{$r['Type']}\t{$r['Null']}\t{$r['Key']}\t{$r['Default']}\t{$r['Extra']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
