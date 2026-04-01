<?php
// app/helpers/ExcelImporter.php - Helper para importar productos desde CSV/Excel

class ExcelImporter
{
    private $productosModel;
    private $errores = [];
    private $importados = 0;
    private $actualizados = 0;

    public function __construct()
    {
        $this->productosModel = new ProductoCatalogo();
    }

    /**
     * Importar productos desde archivo CSV
     *
     * Columnas esperadas:
     * nombre, categoria_slug, descripcion, descripcion_corta, ruta_imagen, sku, marca, orden, activo
     */
    public function importarCSV($archivo)
    {
        if (!file_exists($archivo)) {
            throw new Exception('Archivo no encontrado');
        }

        $handle = fopen($archivo, 'r');
        if (!$handle) {
            throw new Exception('No se pudo abrir el archivo');
        }

        // Leer encabezados
        $headers = fgetcsv($handle, 0, ',');
        if (!$headers) {
            fclose($handle);
            throw new Exception('Archivo CSV vacío o inválido');
        }

        // Normalizar encabezados
        $headers = array_map('trim', $headers);
        $headers = array_map('strtolower', $headers);

        // Validar columnas requeridas
        $requeridas = ['nombre', 'categoria_slug'];
        foreach ($requeridas as $col) {
            if (!in_array($col, $headers)) {
                fclose($handle);
                throw new Exception("Columna requerida faltante: {$col}");
            }
        }

        $fila = 1; // Contador de filas (empezando después del header)

        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            $fila++;

            // Saltar filas vacías
            if (empty(array_filter($data))) {
                continue;
            }

            // Convertir array a asociativo usando headers
            $row = array_combine($headers, $data);

            try {
                $this->procesarFila($row, $fila);
            } catch (Exception $e) {
                $this->errores[] = "Fila {$fila}: " . $e->getMessage();
            }
        }

        fclose($handle);

        return [
            'importados' => $this->importados,
            'actualizados' => $this->actualizados,
            'errores' => $this->errores
        ];
    }

    /**
     * Procesar una fila del CSV
     */
    private function procesarFila($row, $numFila)
    {
        // Validar datos mínimos
        if (empty($row['nombre'])) {
            throw new Exception("Nombre del producto vacío");
        }

        if (empty($row['categoria_slug'])) {
            throw new Exception("Categoría no especificada");
        }

        // Buscar categoría por slug
        $categoria = $this->productosModel->getCategoriaBySlug(trim($row['categoria_slug']));
        if (!$categoria) {
            throw new Exception("Categoría no encontrada: " . $row['categoria_slug']);
        }

        // Procesar imagen si existe
        $imagen_principal = null;
        if (!empty($row['ruta_imagen'])) {
            $ruta_imagen = trim($row['ruta_imagen']);

            // Si es una ruta absoluta del sistema, verificar que existe
            if (file_exists($ruta_imagen)) {
                // Copiar imagen a uploads
                $imagen_principal = $this->copiarImagen($ruta_imagen);
            } elseif (filter_var($ruta_imagen, FILTER_VALIDATE_URL)) {
                // Si es URL, descargar
                $imagen_principal = $this->descargarImagen($ruta_imagen);
            } else {
                // Asumir que es una ruta relativa dentro de uploads
                $imagen_principal = $ruta_imagen;
            }
        }

        // Preparar datos del producto
        $datos = [
            'categoria_id' => $categoria['id'],
            'nombre' => trim($row['nombre']),
            'descripcion' => isset($row['descripcion']) ? trim($row['descripcion']) : null,
            'descripcion_corta' => isset($row['descripcion_corta']) ? trim($row['descripcion_corta']) : null,
            'imagen_principal' => $imagen_principal,
            'precio_referencia' => null, // Ya no usamos precio
            'sku' => isset($row['sku']) ? trim($row['sku']) : null,
            'marca' => isset($row['marca']) ? trim($row['marca']) : null,
            'orden' => isset($row['orden']) ? intval($row['orden']) : 0,
            'activo' => isset($row['activo']) ? ($row['activo'] === '1' || strtolower($row['activo']) === 'si') : 1,
            'destacado' => 0,
            'nuevo' => 0,
            'rating' => 0
        ];

        // Verificar si ya existe por SKU
        if (!empty($datos['sku'])) {
            $existente = $this->buscarProductoPorSKU($datos['sku']);
            if ($existente) {
                // Actualizar existente
                $this->productosModel->actualizarProducto($existente['id'], $datos);
                $this->actualizados++;
                return;
            }
        }

        // Crear nuevo producto
        if ($this->productosModel->crearProducto($datos)) {
            $this->importados++;
        } else {
            throw new Exception("Error al guardar en base de datos");
        }
    }

    /**
     * Copiar imagen desde una ruta del sistema
     */
    private function copiarImagen($ruta_origen)
    {
        $dir_destino = UPLOADS_PATH . 'productos/';

        if (!is_dir($dir_destino)) {
            mkdir($dir_destino, 0755, true);
        }

        $extension = strtolower(pathinfo($ruta_origen, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $permitidas)) {
            throw new Exception("Tipo de imagen no permitido: {$extension}");
        }

        $nombre_destino = uniqid('prod_') . '.' . $extension;
        $ruta_destino = $dir_destino . $nombre_destino;

        if (!copy($ruta_origen, $ruta_destino)) {
            throw new Exception("Error al copiar imagen");
        }

        return 'uploads_privados/productos/' . $nombre_destino;
    }

    /**
     * Descargar imagen desde URL
     */
    private function descargarImagen($url)
    {
        $dir_destino = UPLOADS_PATH . 'productos/';

        if (!is_dir($dir_destino)) {
            mkdir($dir_destino, 0755, true);
        }

        // Descargar imagen
        $imagen_data = @file_get_contents($url);
        if ($imagen_data === false) {
            throw new Exception("Error al descargar imagen desde URL");
        }

        // Detectar extensión desde URL
        $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $extension = 'jpg'; // Por defecto
        }

        $nombre_destino = uniqid('prod_') . '.' . $extension;
        $ruta_destino = $dir_destino . $nombre_destino;

        if (file_put_contents($ruta_destino, $imagen_data) === false) {
            throw new Exception("Error al guardar imagen descargada");
        }

        return 'uploads_privados/productos/' . $nombre_destino;
    }

    /**
     * Buscar producto por SKU
     */
    private function buscarProductoPorSKU($sku)
    {
        $productos = $this->productosModel->getProductos();
        foreach ($productos as $producto) {
            if ($producto['sku'] === $sku) {
                return $producto;
            }
        }
        return null;
    }

    /**
     * Obtener errores
     */
    public function getErrores()
    {
        return $this->errores;
    }

    /**
     * Generar CSV de ejemplo
     */
    public static function generarCSVEjemplo()
    {
        $headers = [
            'nombre',
            'categoria_slug',
            'descripcion',
            'descripcion_corta',
            'ruta_imagen',
            'sku',
            'marca',
            'orden',
            'activo'
        ];

        $ejemplos = [
            [
                'Organizador de Especias DrawerFit',
                'cook-bake',
                'Organizador expandible para especias que se adapta a cajones profundos. Mantén todas tus especias ordenadas y visibles.',
                'Organizador expandible para especias en cajón profundo',
                '/ruta/a/imagen1.jpg',
                'ORG-001',
                'YouCopia',
                '0',
                '1'
            ],
            [
                'Rack Organizador de Botellas',
                'hydrate',
                'Organizador vertical para botellas de agua. Ahorra espacio en tu cocina.',
                'Organizador vertical para botellas',
                'https://ejemplo.com/imagen2.jpg',
                'ORG-002',
                'YouCopia',
                '1',
                '1'
            ],
            [
                'Caddy para Limpieza',
                'cleanup',
                'Caddy portátil para productos de limpieza con múltiples compartimentos.',
                'Caddy portátil para productos de limpieza',
                '',
                'ORG-003',
                'YouCopia',
                '2',
                '1'
            ]
        ];

        $csv = implode(',', $headers) . "\n";
        foreach ($ejemplos as $ejemplo) {
            $csv .= implode(',', array_map(function($val) {
                return '"' . str_replace('"', '""', $val) . '"';
            }, $ejemplo)) . "\n";
        }

        return $csv;
    }
}
