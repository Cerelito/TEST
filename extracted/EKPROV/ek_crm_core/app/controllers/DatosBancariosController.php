<?php
// app/controllers/DatosBancariosController.php

/**
 * DatosBancariosController.php
 * Gestión de cuentas bancarias de proveedores
 * Con sistema de aprobación Admin/Capturista y Dispersión Inteligente
 */

require_once ROOT_PATH . 'app/helpers/Email.php';

class DatosBancariosController
{
    private $datosBancariosModel;
    private $proveedorModel;
    private $catalogoModel;
    private $driveService;

    public function __construct()
    {
        requireAuth();
        requirePermiso('proveedores.ver');

        $this->datosBancariosModel = new DatosBancarios();
        $this->proveedorModel = new Proveedor();
        $this->catalogoModel = new Catalogo();
        $this->driveService = new GoogleDriveService();
    }

    /**
     * Ver cuentas bancarias de un proveedor
     */
    public function index($proveedor_id)
    {
        $proveedor = $this->proveedorModel->getById($proveedor_id);

        if (!$proveedor) {
            setFlash('error', 'Proveedor no encontrado');
            redirect('proveedores');
        }

        // Obtener todas las cuentas (activas)
        $cuentas = $this->datosBancariosModel->getCuentasByProveedor($proveedor_id, false);

        // Obtener historial completo
        $historial = $this->datosBancariosModel->getHistorialProveedor($proveedor_id);

        require_once VIEWS_PATH . 'datos-bancarios/index.php';
    }

    /**
     * Crear nueva cuenta bancaria
     */
    public function crear($proveedor_id)
    {
        $proveedor = $this->proveedorModel->getById($proveedor_id);

        if (!$proveedor) {
            setFlash('error', 'Proveedor no encontrado');
            redirect('proveedores');
        }

        // Obtener catálogos
        $bancos = $this->catalogoModel->getBancos(true);
        // Cambiado: Obtener TODAS las compañías activas para permitir selección nueva desde aquí
        $cias = $this->catalogoModel->getCias(1);

        // Obtener IDs de compañías que YA tiene el proveedor (para marcarlas en la vista)
        $ciasActuales = $this->proveedorModel->getCias($proveedor_id);
        $ciasProveedorIds = array_column($ciasActuales, 'Id');

        // Detectar colisiones (cualquier cuenta existente es colisión al crear una nueva)
        $todasCuentas = $this->datosBancariosModel->getTodasListas($proveedor_id);
        $colisiones = [];
        foreach ($todasCuentas as $c) {
            if ($c['Activo']) {
                $clabe = !empty($c['Clabe']) ? substr($c['Clabe'], -4) : 'N/A';
                $colisiones[$c['CiaId']] = [
                    'Banco' => $c['BancoNombre'],
                    'Clabe' => $clabe
                ];
            }
        }

        require_once VIEWS_PATH . 'datos-bancarios/crear.php';
    }

    /**
     * Guardar nueva cuenta bancaria
     */
    public function guardar($proveedor_id)
    {
        verificarCSRF();

        try {
            // Validar datos
            $cia_ids = $_POST['CiaId'] ?? [];
            $banco_id = $_POST['BancoId'] ?? null;

            // Compatibilidad si viene como string único
            if (!is_array($cia_ids)) {
                $cia_ids = [$cia_ids];
            }

            // Filtrar vacíos
            $cia_ids = array_filter($cia_ids);

            if (empty($cia_ids)) {
                throw new Exception('Debe seleccionar al menos una compañía');
            }

            // Procesar archivo PDF (carátula) - UNA SOLA VEZ
            $ruta_caratula = null;
            if (isset($_FILES['ArchivoCaratula']) && $_FILES['ArchivoCaratula']['error'] === UPLOAD_ERR_OK) {
                $ruta_caratula = $this->subirArchivo($_FILES['ArchivoCaratula'], $proveedor_id, 'caratula');
            }

            $sinCaratula = isset($_POST['SinCaratula']) && $_POST['SinCaratula'] == '1';

            if (!$ruta_caratula && (!esAdmin() || !$sinCaratula)) {
                throw new Exception("La carátula bancaria es obligatoria.");
            }

            // Iterar sobre cada compañía seleccionada y crear la cuenta
            $cuentas_creadas = 0;

            foreach ($cia_ids as $cia_id) {
                // SINCRONIZACIÓN: Asegurar que la CIA esté asignada al proveedor
                $this->proveedorModel->asignarCia($proveedor_id, $cia_id);

                // Preparar datos para esta instancia
                $datos = [
                    'ProveedorId' => $proveedor_id,
                    'CiaId' => $cia_id,
                    'BancoId' => $banco_id,
                    'Cuenta' => $_POST['Cuenta'] ?? null,
                    'Clabe' => $_POST['Clabe'] ?? null,
                    'Sucursal' => $_POST['Sucursal'] ?? null,
                    'Plaza' => $_POST['Plaza'] ?? null,
                    'RutaCaratula' => $ruta_caratula
                ];

                // Crear cuenta
                $this->datosBancariosModel->crearCuenta($datos, usuarioId());
                $cuentas_creadas++;
            }

            if (esAdmin()) {
                setFlash('success', "Se crearon $cuentas_creadas cuenta(s) bancaria(s) y fueron aprobadas exitosamente.");
            } else {
                setFlash('success', "Se registraron $cuentas_creadas cuenta(s) bancaria(s). Pendiente de aprobación.");
            }

            redirect('datos-bancarios/index/' . $proveedor_id);

        } catch (Exception $e) {
            error_log("Error in DatosBancariosController::guardar: " . $e->getMessage());
            setFlash('error', 'Error al crear cuenta: ' . $e->getMessage());
            redirect('datos-bancarios/crear/' . $proveedor_id);
        }
    }

    /**
     * Editar cuenta bancaria (solo Admin o si es pendiente)
     */
    public function editar($cuenta_id)
    {
        $cuenta = $this->datosBancariosModel->getCuentaById($cuenta_id);

        if (!$cuenta) {
            setFlash('error', 'Cuenta no encontrada');
            redirect('proveedores');
        }

        // Solo Admin puede editar cuentas aprobadas
        if ($cuenta['Estatus'] === 'APROBADO' && !esAdmin()) {
            setFlash('error', 'Solo el Administrador puede modificar cuentas aprobadas');
            redirect('datos-bancarios/index/' . $cuenta['ProveedorId']);
        }

        $bancos = $this->catalogoModel->getBancos(true);

        // Cambiado: Obtener TODAS las compañías (para rediseño de grid)
        $cias = $this->catalogoModel->getCias(1);
        // Obtener IDs de las que ya tiene el proveedor (para visualización)
        $ciasAsignadasAlProv = $this->proveedorModel->getCias($cuenta['ProveedorId']);
        $ciasProvIds = array_column($ciasAsignadasAlProv, 'Id');

        // Detectar cuentas "hermanas" (misma CLABE/Cuenta) y colisiones (otras cuentas)
        $todasCuentas = $this->datosBancariosModel->getTodasListas($cuenta['ProveedorId']);
        $cuentasHermanasIds = [$cuenta['CiaId']];
        $idsHermanas = [$cuenta['Id']];
        $colisiones = []; // Map: CiaId => [Banco, Ultimos4Clabe]

        // 1. Identificar Ids de todas las hermanas
        foreach ($todasCuentas as $c) {
            if ($c['Id'] == $cuenta['Id'])
                continue;

            $coincideArchivo = (!empty($cuenta['RutaCaratula']) && !empty($c['RutaCaratula']) && $c['RutaCaratula'] === $cuenta['RutaCaratula']);
            $coincideCuenta = (!empty($cuenta['Cuenta']) && !empty($c['Cuenta']) && $c['Cuenta'] === $cuenta['Cuenta'] && $c['BancoId'] == $cuenta['BancoId']);
            $coincideClabe = (!empty($cuenta['Clabe']) && !empty($c['Clabe']) && $c['Clabe'] === $cuenta['Clabe']);

            if ($coincideArchivo || $coincideCuenta || $coincideClabe) {
                $idsHermanas[] = $c['Id'];
                $cuentasHermanasIds[] = $c['CiaId'];
            }
        }

        // 2. Identificar colisiones (cuentas que NO son hermanas)
        foreach ($todasCuentas as $c) {
            if (in_array($c['Id'], $idsHermanas))
                continue;

            if ($c['Activo']) {
                $clabe = !empty($c['Clabe']) ? substr($c['Clabe'], -4) : 'N/A';
                $colisiones[$c['CiaId']] = [
                    'Banco' => $c['BancoNombre'],
                    'Clabe' => $clabe
                ];
            }
        }

        $cuentasHermanasIds = array_unique($cuentasHermanasIds);

        require_once VIEWS_PATH . 'datos-bancarios/editar.php';
    }

    /**
     * Actualizar cuenta bancaria con Dispersión Inteligente
     */
    public function actualizar($cuenta_id)
    {
        verificarCSRF();

        try {
            $cuenta = $this->datosBancariosModel->getCuentaById($cuenta_id);

            if (!$cuenta) {
                throw new Exception('Cuenta no encontrada');
            }

            // Solo Admin puede editar cuentas aprobadas
            if ($cuenta['Estatus'] === 'APROBADO' && !esAdmin()) {
                throw new Exception('Solo el Administrador puede modificar cuentas aprobadas');
            }

            // Procesar archivo PDF si se subió uno nuevo
            $ruta_caratula = null;
            if (isset($_FILES['ArchivoCaratula']) && $_FILES['ArchivoCaratula']['error'] === UPLOAD_ERR_OK) {
                $ruta_caratula = $this->subirArchivo($_FILES['ArchivoCaratula'], $cuenta['ProveedorId'], 'caratula');
            } else {
                // Si no hay nuevo, mantenemos el anterior (necesario para la creación de nuevas cuentas)
                $ruta_caratula = $cuenta['RutaCaratula'];
            }

            // Preparar datos capturados
            $datosBase = [
                'BancoId' => $_POST['BancoId'] ?? null,
                'Cuenta' => $_POST['Cuenta'] ?? null,
                'Clabe' => $_POST['Clabe'] ?? null,
                'Sucursal' => $_POST['Sucursal'] ?? null,
                'Plaza' => $_POST['Plaza'] ?? null,
                'RutaCaratula' => $ruta_caratula // Usamos la nueva o la existente
            ];

            // 1. Identificar TODAS las cuentas "hermanas" actuales (el grupo que estamos editando)
            //    Esto nos sirve para saber cuáles actualizar.
            $todasCuentas = $this->datosBancariosModel->getTodasListas($cuenta['ProveedorId']);
            $mapaSisters = [];

            // La cuenta actual siempre es parte del grupo
            $mapaSisters[$cuenta['CiaId']] = $cuenta['Id'];

            // Buscar otras con mismos datos originales (CLABE, Cuenta, Archivo)
            foreach ($todasCuentas as $c) {
                if ($c['Id'] == $cuenta['Id'])
                    continue;

                $coincideArchivo = (!empty($cuenta['RutaCaratula']) && !empty($c['RutaCaratula']) && $c['RutaCaratula'] === $cuenta['RutaCaratula']);
                $coincideCuenta = (!empty($cuenta['Cuenta']) && !empty($c['Cuenta']) && $c['Cuenta'] === $cuenta['Cuenta'] && $c['BancoId'] == $cuenta['BancoId']);
                $coincideClabe = (!empty($cuenta['Clabe']) && !empty($c['Clabe']) && $c['Clabe'] === $cuenta['Clabe']);

                if ($coincideArchivo || $coincideCuenta || $coincideClabe) {
                    $mapaSisters[$c['CiaId']] = $c['Id'];
                }
            }

            // 2. Procesar la selección de compañías enviada (Update o Create)
            $cia_ids_seleccionadas = $_POST['CiaId'] ?? [];
            if (!is_array($cia_ids_seleccionadas))
                $cia_ids_seleccionadas = [$cia_ids_seleccionadas];
            $cia_ids_seleccionadas = array_unique($cia_ids_seleccionadas);

            // SEGURIDAD: Solo Admin puede desvincular. Capturistas solo agregan.
            if (!esAdmin()) {
                $cias_actuales = array_keys($mapaSisters);
                foreach ($cias_actuales as $cia_id_actual) {
                    if (!in_array($cia_id_actual, $cia_ids_seleccionadas)) {
                        // El capturista intentó quitar una CIA, la forzamos de vuelta
                        $cia_ids_seleccionadas[] = $cia_id_actual;
                    }
                }
                $cia_ids_seleccionadas = array_unique($cia_ids_seleccionadas);
            }

            $sinCaratula = isset($_POST['SinCaratula']) && $_POST['SinCaratula'] == '1';
            if (!$ruta_caratula && (!esAdmin() || !$sinCaratula)) {
                throw new Exception("La carátula bancaria es obligatoria.");
            }

            $contador_updated = 0;
            $contador_created = 0;

            foreach ($cia_ids_seleccionadas as $cia_id) {
                // SINCRONIZACIÓN: Asegurar que la CIA esté asignada al proveedor
                $this->proveedorModel->asignarCia($cuenta['ProveedorId'], $cia_id);

                if (isset($mapaSisters[$cia_id])) {
                    // EXISTE: Actualizar
                    $sisterId = $mapaSisters[$cia_id];
                    // Si no se subió archivo nuevo, no mandamos la key RutaCaratula para no borrar la existente si la lógica del modelo lo hiciera
                    // Pero aquí preparamos $datosBase con la ruta correcta (nueva o vieja), así que la enviamos siempre.
                    $this->datosBancariosModel->actualizarCuenta($sisterId, $datosBase, usuarioId());

                    // Si se marcó "Aprobar", aprobamos también
                    if (isset($_POST['aprobar_despues']) && $_POST['aprobar_despues'] == '1') {
                        $this->datosBancariosModel->aprobarCuenta($sisterId, usuarioId(), 'Aprobación masiva desde edición principal');
                    }
                    $contador_updated++;
                } else {
                    // NUEVA: Crear
                    $datosNew = $datosBase;
                    $datosNew['ProveedorId'] = $cuenta['ProveedorId'];
                    $datosNew['CiaId'] = $cia_id;

                    $newId = $this->datosBancariosModel->crearCuenta($datosNew, usuarioId());

                    // Si es aprobación, aprobar la nueva también
                    if (isset($_POST['aprobar_despues']) && $_POST['aprobar_despues'] == '1') {
                        $this->datosBancariosModel->aprobarCuenta($newId, usuarioId(), 'Creada y aprobada desde edición masiva');
                    }
                    $contador_created++;
                }
            }

            // Mensaje final
            $msg = "Se actualizaron $contador_updated cuentas";
            if ($contador_created > 0) {
                $msg .= " y se crearon $contador_created nuevas asignaciones";
            }
            $msg .= " exitosamente.";
            setFlash('success', $msg);
            redirect('datos-bancarios/index/' . $cuenta['ProveedorId']);

        } catch (Exception $e) {
            error_log("Error in DatosBancariosController::actualizar: " . $e->getMessage());
            setFlash('error', 'Error al actualizar cuenta: ' . $e->getMessage());
            redirect('datos-bancarios/editar/' . $cuenta_id);
        }
    }

    /**
     * Aprobar cuenta pendiente (solo Admin)
     */
    public function aprobar($cuenta_id)
    {
        requireAdmin();
        verificarCSRF();

        try {
            $cuenta = $this->datosBancariosModel->getCuentaById($cuenta_id);

            if (!$cuenta) {
                throw new Exception('Cuenta no encontrada');
            }

            $notas = $_POST['notas'] ?? null;

            // 1. Aprobar en BD
            $this->datosBancariosModel->aprobarCuenta($cuenta_id, usuarioId(), $notas);

            // NOTA: Se eliminó el envío de correo individual.
            // Se asume que el flujo correcto es enviar todo junto al final.

            setFlash('success', 'Cuenta aprobada exitosamente');

            // Redirigir según contexto
            if (isset($_POST['redirect_to']) && $_POST['redirect_to'] === 'pendientes') {
                redirect('datos-bancarios/pendientes');
            } else {
                redirect('datos-bancarios/index/' . $cuenta['ProveedorId']);
            }

        } catch (Exception $e) {
            setFlash('error', 'Error al aprobar cuenta: ' . $e->getMessage());
            redirect('datos-bancarios/index/' . ($cuenta['ProveedorId'] ?? ''));
        }
    }

    /**
     * Rechazar cuenta pendiente (solo Admin)
     */
    public function rechazar($cuenta_id)
    {
        requireAdmin();
        verificarCSRF();

        try {
            $cuenta = $this->datosBancariosModel->getCuentaById($cuenta_id);

            if (!$cuenta) {
                throw new Exception('Cuenta no encontrada');
            }

            $motivo = $_POST['motivo'] ?? 'Sin motivo especificado';

            $this->datosBancariosModel->rechazarCuenta($cuenta_id, usuarioId(), $motivo);

            setFlash('warning', 'Cuenta rechazada');

            // Redirigir según contexto
            if (isset($_POST['redirect_to']) && $_POST['redirect_to'] === 'pendientes') {
                redirect('datos-bancarios/pendientes');
            } else {
                redirect('datos-bancarios/index/' . $cuenta['ProveedorId']);
            }

        } catch (Exception $e) {
            setFlash('error', 'Error al rechazar cuenta: ' . $e->getMessage());
            redirect('datos-bancarios/index/' . $cuenta['ProveedorId']);
        }
    }

    /**
     * Establecer cuenta como principal (Admin o Supervisor)
     */
    public function establecerPrincipal($cuenta_id)
    {
        verificarCSRF();

        try {
            $cuenta = $this->datosBancariosModel->getCuentaById($cuenta_id);

            if (!$cuenta) {
                throw new Exception('Cuenta no encontrada');
            }

            if ($cuenta['Estatus'] !== 'APROBADO') {
                throw new Exception('Solo se pueden establecer como principales las cuentas aprobadas');
            }

            $this->datosBancariosModel->establecerComoPrincipal($cuenta_id, usuarioId());

            setFlash('success', 'Cuenta establecida como principal');
            redirect('datos-bancarios/index/' . $cuenta['ProveedorId']);

        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
            redirect('proveedores');
        }
    }

    /**
     * Desactivar cuenta (solo Admin)
     */
    public function desactivar($cuenta_id)
    {
        requireAdmin();
        verificarCSRF();

        try {
            $cuenta = $this->datosBancariosModel->getCuentaById($cuenta_id);

            if (!$cuenta) {
                throw new Exception('Cuenta no encontrada');
            }

            $motivo = $_POST['motivo'] ?? null;

            $this->datosBancariosModel->desactivarCuenta($cuenta_id, usuarioId(), $motivo);

            setFlash('success', 'Cuenta desactivada');
            redirect('datos-bancarios/index/' . $cuenta['ProveedorId']);

        } catch (Exception $e) {
            setFlash('error', 'Error al desactivar cuenta: ' . $e->getMessage());
            redirect('datos-bancarios/index/' . $cuenta['ProveedorId']);
        }
    }

    /**
     * Ver historial de una cuenta
     */
    public function historial($cuenta_id)
    {
        $cuenta = $this->datosBancariosModel->getCuentaById($cuenta_id);

        if (!$cuenta) {
            setFlash('error', 'Cuenta no encontrada');
            redirect('proveedores');
        }

        $historial = $this->datosBancariosModel->getHistorialCuenta($cuenta_id);
        $adjuntos = $this->datosBancariosModel->getAdjuntosCuenta($cuenta_id);

        require_once VIEWS_PATH . 'datos-bancarios/historial.php';
    }

    /**
     * Lista de cuentas pendientes de aprobación (solo Admin)
     */
    public function pendientes()
    {
        requireAdmin();
        $cuentasPendientes = $this->datosBancariosModel->getCuentasPendientes();
        require_once VIEWS_PATH . 'datos-bancarios/pendientes.php';
    }

    /**
     * Agregar adjunto a una cuenta
     */
    public function agregarAdjunto($cuenta_id)
    {
        verificarCSRF();

        try {
            $cuenta = $this->datosBancariosModel->getCuentaById($cuenta_id);

            if (!$cuenta) {
                throw new Exception('Cuenta no encontrada');
            }

            if (!isset($_FILES['Archivo']) || $_FILES['Archivo']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No se recibió ningún archivo');
            }

            $tipo_documento = $_POST['TipoDocumento'] ?? 'CARATULA';
            $ruta_archivo = $this->subirArchivo($_FILES['Archivo'], $cuenta['ProveedorId'], strtolower($tipo_documento));

            $datos_archivo = [
                'TipoDocumento' => $tipo_documento,
                'NombreArchivo' => $_FILES['Archivo']['name'],
                'RutaArchivo' => $ruta_archivo,
                'TamanoBytes' => $_FILES['Archivo']['size']
            ];

            $this->datosBancariosModel->agregarAdjunto(
                $cuenta_id,
                $cuenta['ProveedorId'],
                $datos_archivo,
                usuarioId()
            );

            setFlash('success', 'Documento adjuntado exitosamente');
            redirect('datos-bancarios/historial/' . $cuenta_id);

        } catch (Exception $e) {
            setFlash('error', 'Error al adjuntar documento: ' . $e->getMessage());
            redirect('datos-bancarios/index/' . ($cuenta['ProveedorId'] ?? ''));
        }
    }

    /**
     * Ver archivo PDF (CORREGIDO Y MEJORADO)
     * Implementa búsqueda inteligente en múltiples ubicaciones para evitar error 404 físico
     */
    public function verArchivo($tipo, $id)
    {
        try {
            $ruta = '';
            $cuenta = null;

            // Lógica para carátula principal
            if ($tipo === 'caratula') {
                $cuenta = $this->datosBancariosModel->getCuentaById($id);
                if (!$cuenta || empty($cuenta['RutaCaratula'])) {
                    die('Archivo no asociado en base de datos.');
                }
                $ruta = $cuenta['RutaCaratula'];
            }
            // Lógica para adjuntos adicionales
            elseif ($tipo === 'adjunto') {
                $db = (new Database())->getConnection();
                $stmt = $db->prepare("SELECT * FROM DatosBancarios_Adjuntos WHERE Id = ?");
                $stmt->execute([$id]);
                $adj = $stmt->fetch();
                if ($adj) {
                    $ruta = $adj['RutaArchivo'];
                    // Necesitamos datos del proveedor para la búsqueda inteligente
                    $cuenta = $this->datosBancariosModel->getCuentaById($adj['CuentaId']);
                } else {
                    die('Adjunto no encontrado.');
                }
            }

            // GOOGLE DRIVE CHECK
            // If route does not contain slashes, assume it is a Drive ID
            if (!empty($ruta) && strpos($ruta, '/') === false && strpos($ruta, '\\') === false) {
                if ($this->driveService->isConnected()) {
                    $content = $this->driveService->getFileContent($ruta);
                    if ($content) {
                        $meta = $this->driveService->getFileMetadata($ruta);
                        $mime = $meta->mimeType ?? 'application/pdf';
                        $filename = $meta->name ?? 'documento.pdf';

                        if (ob_get_level())
                            ob_end_clean();
                        header('Content-Type: ' . $mime);
                        header('Content-Disposition: inline; filename="' . $filename . '"');
                        echo $content;
                        exit;
                    }
                }
            }

            // Búsqueda robusta del archivo físico
            // 1. Ruta absoluta estándar
            $rutasPosibles = [
                UPLOADS_PATH . $ruta,
                UPLOADS_PATH . ltrim($ruta, '/'),
                str_replace('//', '/', UPLOADS_PATH . '/' . $ruta)
            ];

            // 2. Búsqueda Inteligente: Intentar construir la ruta basada en el RFC o ID Manual
            if ($cuenta) {
                $proveedor = $this->proveedorModel->getById($cuenta['ProveedorId']);
                if ($proveedor) {
                    $nombreArchivo = basename($ruta);

                    // Intentar en carpeta RFC
                    if (!empty($proveedor['RFC'])) {
                        $rutasPosibles[] = UPLOADS_PATH . $proveedor['RFC'] . '/cuentas/' . $nombreArchivo;
                        $rutasPosibles[] = UPLOADS_PATH . $proveedor['RFC'] . '/' . $nombreArchivo;
                    }
                    // Intentar en carpeta ID Manual
                    if (!empty($proveedor['IdManual'])) {
                        $rutasPosibles[] = UPLOADS_PATH . $proveedor['IdManual'] . '/cuentas/' . $nombreArchivo;
                        $rutasPosibles[] = UPLOADS_PATH . $proveedor['IdManual'] . '/' . $nombreArchivo;
                    }
                }
            }

            $archivoEncontrado = null;
            foreach ($rutasPosibles as $r) {
                if (file_exists($r) && is_file($r)) {
                    $archivoEncontrado = $r;
                    break;
                }
            }

            if (!$archivoEncontrado) {
                $msg = (defined('APP_DEBUG') && APP_DEBUG) ? " (Ruta buscada en BD: $ruta)" : "";
                die("Error: El archivo físico no se encuentra en el servidor$msg. Por favor, intente subirlo nuevamente.");
            }

            // Limpiar buffer de salida para evitar corrupción del PDF
            if (ob_get_level())
                ob_end_clean();

            // Detección dinámica de MIME type
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($archivoEncontrado);

            // Fallback si finfo falla
            if (!$mimeType) {
                $ext = strtolower(pathinfo($archivoEncontrado, PATHINFO_EXTENSION));
                $mimes = [
                    'pdf' => 'application/pdf',
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png'
                ];
                $mimeType = $mimes[$ext] ?? 'application/octet-stream';
            }

            // Servir el archivo
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: inline; filename="' . basename($archivoEncontrado) . '"');
            header('Content-Length: ' . filesize($archivoEncontrado));
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            readfile($archivoEncontrado);
            exit;

        } catch (Exception $e) {
            die('Error al cargar archivo: ' . $e->getMessage());
        }
    }

    /**
     * Subir archivo a Google Drive (en vez de local)
     */
    private function subirArchivo($archivo, $proveedor_id, $tipo)
    {
        // Validación MIME
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($archivo['tmp_name']);

        $permitidos = [
            'application/pdf',
            'image/jpeg',
            'image/png'
        ];

        if (!in_array($mime, $permitidos)) {
            throw new Exception('El archivo no es un formato válido (PDF, JPG, PNG).');
        }

        if ($archivo['size'] > 5 * 1024 * 1024) {
            throw new Exception('El archivo no debe exceder 5MB');
        }

        if (!$this->driveService->isConnected()) {
            throw new Exception('No se pudo conectar con Google Drive para subir el archivo bancario.');
        }

        $proveedor = $this->proveedorModel->getById($proveedor_id);
        if (!$proveedor)
            throw new Exception('Proveedor no encontrado');

        $nombreCarpeta = $proveedor['IdManual'] ?: $proveedor['RFC'];

        // Root Folder for Provider
        $driveFolderId = $this->driveService->createFolder($nombreCarpeta);
        if (!$driveFolderId) {
            throw new Exception("No se pudo acceder a la carpeta del proveedor en Drive.");
        }

        // Subfolder 'cuentas'
        $cuentasFolderId = $this->driveService->createFolder('cuentas', $driveFolderId);
        if (!$cuentasFolderId) {
            throw new Exception("No se pudo acceder a la carpeta de cuentas en Drive.");
        }

        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $prefijo = strtoupper($tipo);
        $nombre_archivo = $prefijo . '_' . time() . '_' . uniqid() . '.' . $extension;

        $fileId = $this->driveService->uploadFile(
            $archivo['tmp_name'],
            $cuentasFolderId,
            $nombre_archivo,
            $mime
        );

        if (!$fileId) {
            throw new Exception("Error al subir el archivo a Google Drive.");
        }

        return $fileId; // Retornamos el ID de Drive
    }
}