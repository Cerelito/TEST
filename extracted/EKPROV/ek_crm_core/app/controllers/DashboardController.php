<?php
// app/controllers/DashboardController.php

class DashboardController
{
    public function __construct()
    {
        requireAuth();
    }

    public function index()
    {
        $proveedorModel = new Proveedor();
        $solicitudModel = new Solicitud();
        $userModel = new User();

        // Obtener estadísticas según permisos
        $stats = [];

        if (puedeVer('proveedores')) {
            $stats['proveedores'] = $proveedorModel->getEstadisticas();
        }

        if (puedeVer('solicitudes')) {
            $stats['solicitudes'] = $solicitudModel->getEstadisticas();
        }

        if (puedeVer('usuarios')) {
            $stats['usuarios'] = $userModel->getEstadisticas();
        }

        // Obtener solicitudes pendientes (para admins y supervisores)
        $solicitudes_pendientes = [];
        if (tienePermiso('solicitudes.aprobar')) {
            $solicitudes_pendientes = $solicitudModel->getAll(['estatus' => 'PENDIENTE']);
        }

        // Obtener proveedores recientes
        $proveedores_recientes = [];
        if (puedeVer('proveedores')) {
            $proveedores_recientes = $proveedorModel->getAll(['limit' => 5]);
        }

        require_once VIEWS_PATH . 'dashboard/index.php';
    }
}
