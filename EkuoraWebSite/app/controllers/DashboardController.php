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
        $productoModel = new ProductoCatalogo();
        $userModel = new User();

        $stats = $productoModel->getEstadisticas();
        $user_stats = $userModel->getEstadisticas();

        // Categorías para ver rápido
        $categorias = $productoModel->getCategorias(true);

        require_once VIEWS_PATH . 'dashboard/index.php';
    }
}
