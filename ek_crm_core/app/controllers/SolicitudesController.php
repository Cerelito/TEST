<?php
// app/controllers/SolicitudesController.php

// Carga manual de helpers y modelos necesarios
require_once ROOT_PATH . 'app/helpers/Email.php';
require_once ROOT_PATH . 'app/models/DatosBancarios.php';

class SolicitudesController
{
    private $solicitudModel;
    private $proveedorModel;
    private $datosBancariosModel;
    private $driveService;

    public function __construct()
    {
        requireAuth();
        requirePermiso('solicitudes.ver');
        $this->solicitudModel = new Solicitud();
        $this->proveedorModel = new Proveedor();
        $this->datosBancariosModel = new DatosBancarios();
        $this->driveService = new GoogleDriveService();
    }

    public function index()
    {
        $filtros = [];

        // Si no es admin, solo ver sus propias solicitudes
        if (!tienePermiso('solicitudes.aprobar')) {
            $filtros['solicitante_id'] = usuarioId();
        }

        if (!empty($_GET['estatus'])) {
            $filtros['estatus'] = $_GET['estatus'];
        }

        if (!empty($_GET['busqueda'])) {
            $filtros['busqueda'] = $_GET['busqueda'];
        }

        // --- LÓGICA DE PAGINACIÓN ---
        $limit = 10;
        $pagina = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        if ($pagina < 1)
            $pagina = 1;
        $offset = ($pagina - 1) * $limit;

        $total_registros = $this->solicitudModel->countAll($filtros);
        $total_paginas = ceil($total_registros / $limit);

        // Si la página actual es mayor que el total de páginas (y hay páginas), ir a la última
        if ($total_paginas > 0 && $pagina > $total_paginas) {
            redirect('solicitudes?p=' . $total_paginas . (isset($filtros['estatus']) ? '&estatus=' . $filtros['estatus'] : '') . (isset($filtros['busqueda']) ? '&busqueda=' . $filtros['busqueda'] : ''));
        }

        $solicitudes = $this->solicitudModel->getAll($filtros, $limit, $offset);
        $stats = $this->solicitudModel->getEstadisticas();

        require_once VIEWS_PATH . 'solicitudes/index.php';
    }

    public function revisar($id)
    {
        requirePermiso('solicitudes.aprobar');

        $solicitud = $this->solicitudModel->getById($id);

        if (!$solicitud) {
            setFlash('error', 'Solicitud no encontrada.');
            redirect('solicitudes');
        }

        $proveedor = $this->proveedorModel->getById($solicitud['ProveedorId']);

        // Cargar bancos para el select en caso de edición bancaria
        $catalogoModel = new Catalogo();
        $bancos = $catalogoModel->getBancos(true);

        require_once VIEWS_PATH . 'solicitudes/revisar.php';
    }

    public function aprobar($id)
    {
        requirePermiso('solicitudes.aprobar');
        verificarCSRF();

        $solicitud = $this->solicitudModel->getById($id);

        if (!$solicitud) {
            setFlash('error', 'Solicitud no encontrada.');
            redirect('solicitudes');
        }

        if ($solicitud['Estatus'] !== 'PENDIENTE') {
            setFlash('error', 'Esta solicitud ya fue procesada anteriormente.');
            redirect('solicitudes');
        }

        // Obtener datos solicitados del JSON original (Decodificación segura)
        $datos_solicitados = $solicitud['DatosSolicitados'] ?? [];
        if (is_string($datos_solicitados)) {
            $datos_solicitados = json_decode($datos_solicitados, true);
        }

        $exito_operacion = false;

        // ---------------------------------------------------------
        // CASO 1: APROBACIÓN DE CAMBIO BANCARIO / ASIGNACIÓN CIAS
        // ---------------------------------------------------------
        if (isset($_POST['es_cambio_bancario']) && $_POST['es_cambio_bancario'] == 1) {
            try {
                // Obtener lista de Cías y mapa de Archivos desde el JSON
                $ciasIds = $datos_solicitados['CiasObjetivoIds'] ?? [];
                // IMPORTANTE: Recuperar el mapa de archivos específicos
                $archivosPorCia = $datos_solicitados['Archivos'] ?? [];

                if (empty($_POST['BancoId']) || (empty($_POST['Cuenta']) && empty($_POST['Clabe']))) {
                    throw new Exception("Debe capturar Banco y al menos Cuenta o CLABE.");
                }

                // Datos capturados por el Admin (General para el lote)
                $datosAdmin = [
                    'BancoId' => !empty($_POST['BancoId']) ? $_POST['BancoId'] : null,
                    'Cuenta' => !empty($_POST['Cuenta']) ? $_POST['Cuenta'] : null,
                    'Clabe' => !empty($_POST['Clabe']) ? $_POST['Clabe'] : null,
                    'Sucursal' => !empty($_POST['Sucursal']) ? $_POST['Sucursal'] : null,
                    'Plaza' => !empty($_POST['Plaza']) ? $_POST['Plaza'] : null,
                    'Estatus' => 'APROBADO',
                    'Activo' => 1
                ];

                $db = (new Database())->getConnection();

                foreach ($ciasIds as $ciaId) {
                    // 1. GARANTIZAR RELACIÓN EN PROVEEDOR_CIAS
                    $stmtCheck = $db->prepare("SELECT COUNT(*) FROM Proveedor_Cias WHERE ProveedorId = ? AND CiaId = ?");
                    $stmtCheck->execute([$solicitud['ProveedorId'], $ciaId]);
                    if ($stmtCheck->fetchColumn() == 0) {
                        $stmtInsert = $db->prepare("INSERT INTO Proveedor_Cias (ProveedorId, CiaId) VALUES (?, ?)");
                        $stmtInsert->execute([$solicitud['ProveedorId'], $ciaId]);
                    }

                    // 2. DETERMINAR EL ARCHIVO A USAR
                    $rutaFinal = $archivosPorCia[$ciaId] ?? $solicitud['RutaCaratulaNueva'];

                    // 3. ACTUALIZAR O CREAR LA CUENTA BANCARIA
                    $cuentaExistente = $this->datosBancariosModel->getCuentaPrincipal($solicitud['ProveedorId'], $ciaId);

                    if ($cuentaExistente) {
                        // Actualizar cuenta existente
                        $datosUpdate = $datosAdmin;
                        if ($rutaFinal) {
                            $datosUpdate['RutaCaratula'] = $rutaFinal;
                        }

                        $this->datosBancariosModel->actualizarCuenta($cuentaExistente['Id'], $datosUpdate, usuarioId());
                        $this->datosBancariosModel->aprobarCuenta($cuentaExistente['Id'], usuarioId(), 'Aprobación masiva desde Solicitud #' . $id);
                    } else {
                        // Crear nueva cuenta
                        $datosNueva = $datosAdmin;
                        $datosNueva['ProveedorId'] = $solicitud['ProveedorId'];
                        $datosNueva['CiaId'] = $ciaId;
                        $datosNueva['EsPrincipal'] = 1;
                        if ($rutaFinal) {
                            $datosNueva['RutaCaratula'] = $rutaFinal;
                        }

                        $this->datosBancariosModel->crearCuenta($datosNueva, usuarioId());
                    }
                }

                setFlash('success', 'Compañías asignadas y cuentas bancarias actualizadas correctamente.');
                $exito_operacion = true;

            } catch (Exception $e) {
                error_log("Error al aprobar cambio bancario Solicitud #$id: " . $e->getMessage());
                setFlash('error', 'Error al procesar las cuentas: ' . $e->getMessage());
                redirect('solicitudes/revisar/' . $id);
            }
        }
        // ---------------------------------------------------------
        // CASO 2: APROBACIÓN DE DATOS GENERALES (MEJORADO)
        // ---------------------------------------------------------
        elseif (isset($_POST['es_datos_generales']) && $_POST['es_datos_generales'] == 1) {

            $datos_admin = [
                'RFC' => strtoupper(trim($_POST['RFC'] ?? '')),
                'RegimenFiscalId' => !empty($_POST['RegimenFiscalId']) ? $_POST['RegimenFiscalId'] : null,
            ];

            // Lógica para distinguir Física vs Moral y limpiar campos opuestos
            if (!empty($_POST['RazonSocial'])) {
                // Es MORAL
                $datos_admin['RazonSocial'] = strtoupper(trim($_POST['RazonSocial']));
                $datos_admin['Nombre'] = null;
                $datos_admin['ApellidoPaterno'] = null;
                $datos_admin['ApellidoMaterno'] = null;
                $datos_admin['TipoPersona'] = 'MORAL';
            } else {
                // Es FÍSICA
                $datos_admin['Nombre'] = strtoupper(trim($_POST['Nombre'] ?? ''));
                $datos_admin['ApellidoPaterno'] = strtoupper(trim($_POST['ApellidoPaterno'] ?? ''));
                $datos_admin['ApellidoMaterno'] = strtoupper(trim($_POST['ApellidoMaterno'] ?? ''));
                $datos_admin['RazonSocial'] = null;
                $datos_admin['TipoPersona'] = 'FISICA';
            }

            if (empty($datos_admin['RFC'])) {
                setFlash('error', 'El RFC es obligatorio.');
                redirect('solicitudes/revisar/' . $id);
            }

            if (!empty($solicitud['RutaConstanciaNueva'])) {
                $this->proveedorModel->updateArchivos($solicitud['ProveedorId'], [
                    'RutaConstancia' => $solicitud['RutaConstanciaNueva']
                ]);
            }

            $proveedorActual = $this->proveedorModel->getById($solicitud['ProveedorId']);

            // Usamos array_merge para sobreescribir solo los campos nuevos sobre los actuales
            if ($this->proveedorModel->update($solicitud['ProveedorId'], array_merge($proveedorActual, $datos_admin))) {
                setFlash('success', 'Datos fiscales actualizados correctamente.');
                $exito_operacion = true;
            } else {
                setFlash('error', 'Error al actualizar los datos generales en la base de datos.');
            }
        }
        // ---------------------------------------------------------
        // CASO 3: APROBACIÓN AUTOMÁTICA / ESTÁNDAR (Contacto, etc)
        // ---------------------------------------------------------
        else {
            $datos_actualizar = [
                'Responsable' => $_POST['Responsable'] ?? ($datos_solicitados['Responsable'] ?? ''),
                'NombreComercial' => $_POST['NombreComercial'] ?? ($datos_solicitados['NombreComercial'] ?? ''),
                'LimiteCredito' => str_replace(['$', ','], '', $_POST['LimiteCredito'] ?? ($datos_solicitados['LimiteCredito'] ?? '0')),
                'CorreoPagosInterno' => $_POST['CorreoPagosInterno'] ?? ($datos_solicitados['CorreoPagosInterno'] ?? ''),
                'CorreoProveedor' => $_POST['CorreoProveedor'] ?? ($datos_solicitados['CorreoProveedor'] ?? ''),
                'RegimenFiscalId' => $_POST['RegimenFiscalId'] ?? ($datos_solicitados['RegimenFiscalId'] ?? null),
                // 'Telefono' => $datos_solicitados['Telefono'] ?? '', // Eliminado
                'Calle' => $datos_solicitados['Calle'] ?? '',
                'Colonia' => $datos_solicitados['Colonia'] ?? '',
                'CP' => $datos_solicitados['CP'] ?? '',
                'Estado' => $datos_solicitados['Estado'] ?? '',
                'Municipio' => $datos_solicitados['Municipio'] ?? '',
                'MunicipioId' => null,
                'Cias' => $datos_solicitados['Cias'] ?? []
            ];

            if (isset($datos_solicitados['TipoCambio']) && $datos_solicitados['TipoCambio'] === 'ALTA NUEVO PROVEEDOR') {
                $datos_actualizar['Estatus'] = 'APROBADO';

                if (!empty($datos_solicitados['IdManual'])) {
                    $datos_actualizar['IdManual'] = $datos_solicitados['IdManual'];
                }

                if (!empty($solicitud['RutaConstanciaNueva'])) {
                    $this->proveedorModel->updateArchivos($solicitud['ProveedorId'], ['RutaConstancia' => $solicitud['RutaConstanciaNueva']]);
                }
                if (!empty($solicitud['RutaCaratulaNueva'])) {
                    $this->proveedorModel->updateArchivos($solicitud['ProveedorId'], ['RutaCaratula' => $solicitud['RutaCaratulaNueva']]);
                }
            }

            $proveedorActual = $this->proveedorModel->getById($solicitud['ProveedorId']);

            // Limpieza de datos vacíos
            foreach ($datos_actualizar as $key => $val) {
                if (empty($val) && $val !== 0 && $val !== '0') {
                    if ($key !== 'Cias') {
                        unset($datos_actualizar[$key]);
                    }
                }
            }
            if (isset($datos_solicitados['Cias'])) {
                $datos_actualizar['Cias'] = $datos_solicitados['Cias'];
            }

            if ($this->proveedorModel->update($solicitud['ProveedorId'], array_merge($proveedorActual, $datos_actualizar))) {
                setFlash('success', 'Solicitud aprobada y datos actualizados.');
                $exito_operacion = true;
            } else {
                setFlash('error', 'Error al actualizar la base de datos.');
            }
        }

        // ---------------------------------------------------------
        // FINALIZAR: MARCAR SOLICITUD COMO APROBADA Y NOTIFICAR
        // ---------------------------------------------------------
        if ($exito_operacion) {
            if ($this->solicitudModel->aprobar($id)) {

                // --- ENVIAR CORREO TIPO 3: SOLICITUD APROBADA ---
                try {
                    $mailer = new EmailHelper();

                    // Obtener datos frescos del proveedor
                    $proveedor = $this->proveedorModel->getById($solicitud['ProveedorId']);

                    // Obtener lista de Cías asignadas para mostrarlas
                    $cias = $this->proveedorModel->getCias($solicitud['ProveedorId']);
                    $nombresCias = implode(', ', array_column($cias, 'Nombre'));
                    $proveedor['cias_nombres'] = $nombresCias;

                    // Obtener cuentas bancarias
                    $cuentas = $this->datosBancariosModel->getCuentasByProveedor($solicitud['ProveedorId']);

                    // Obtener email del solicitante
                    $stmt_user = (new Database())->getConnection()->prepare("SELECT email FROM usuarios WHERE id = :id");
                    $stmt_user->execute([':id' => $solicitud['SolicitanteId']]);
                    $user = $stmt_user->fetch();

                    if ($user && !empty($user['email'])) {
                        // Enviar al capturista (según tu instrucción)
                        $mailer->solicitudAprobada($user['email'], $proveedor, $cuentas);
                    }
                } catch (Exception $e) {
                    error_log("Error enviando correo de aprobación: " . $e->getMessage());
                }
            }
        }

        redirect('solicitudes');
    }

    public function rechazar($id)
    {
        requirePermiso('solicitudes.aprobar');
        verificarCSRF();

        $solicitud = $this->solicitudModel->getById($id);

        if (!$solicitud) {
            setFlash('error', 'Solicitud no encontrada.');
            redirect('solicitudes');
        }

        if ($solicitud['Estatus'] !== 'PENDIENTE') {
            setFlash('error', 'Esta solicitud ya fue procesada.');
            redirect('solicitudes');
        }

        $motivo = $_POST['motivo_rechazo'] ?? $_POST['motivo'] ?? 'Sin motivo especificado';

        if ($this->solicitudModel->rechazar($id, $motivo)) {

            // --- ENVIAR CORREO TIPO 4: SOLICITUD RECHAZADA ---
            try {
                $mailer = new EmailHelper();
                $proveedor = $this->proveedorModel->getById($solicitud['ProveedorId']);

                $datosJson = $solicitud['DatosSolicitados'] ?? [];
                if (is_string($datosJson))
                    $datosJson = json_decode($datosJson, true);

                // Si era un alta nueva, marcamos al proveedor como rechazado
                if (isset($datosJson['TipoCambio']) && $datosJson['TipoCambio'] === 'ALTA NUEVO PROVEEDOR') {
                    $this->proveedorModel->update($solicitud['ProveedorId'], ['Estatus' => 'RECHAZADO']);
                }

                // Recuperar Cías solicitadas para mostrar en el correo
                $ciasStr = "";
                if (isset($datosJson['CiasObjetivoIds'])) {
                    $idsStr = implode(',', $datosJson['CiasObjetivoIds']);
                    if (!empty($idsStr)) {
                        $stmt = (new Database())->getConnection()->query("SELECT Nombre FROM Cat_Cias WHERE Id IN ($idsStr)");
                        $ciasStr = implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN));
                    }
                }

                $stmt_user = (new Database())->getConnection()->prepare("SELECT email FROM usuarios WHERE id = :id");
                $stmt_user->execute([':id' => $solicitud['SolicitanteId']]);
                $user = $stmt_user->fetch();

                if ($user && !empty($user['email'])) {
                    $mailer->solicitudRechazada($user['email'], $proveedor, $motivo, $ciasStr);
                }
            } catch (Exception $e) {
                error_log("Error enviando correo de rechazo: " . $e->getMessage());
            }

            setFlash('success', 'Solicitud rechazada correctamente.');
        } else {
            setFlash('error', 'Error al rechazar la solicitud.');
        }

        redirect('solicitudes');
    }

    // ========================================================
    // FUNCIÓN PARA VER ARCHIVOS SEGUROS (SOLUCIÓN IFRAME)
    // ========================================================
    public function verArchivo($tipo, $id)
    {
        requirePermiso('solicitudes.ver');

        $solicitud = $this->solicitudModel->getById($id);

        if (!$solicitud) {
            die('Solicitud no encontrada');
        }

        $ruta = '';
        if ($tipo === 'constancia') {
            $ruta = $solicitud['RutaConstanciaNueva'];
        } elseif ($tipo === 'caratula') {
            $ruta = $solicitud['RutaCaratulaNueva'];
        }

        if (empty($ruta)) {
            die('No hay archivo adjunto en esta solicitud.');
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

        // Limpieza de rutas relativas
        $ruta = str_replace(['../', '..\\'], '', $ruta);

        // Búsqueda robusta del archivo
        $posibles_rutas = [
            UPLOADS_PATH . $ruta,
            UPLOADS_PATH . ltrim($ruta, '/'),
            str_replace('//', '/', UPLOADS_PATH . '/' . $ruta)
        ];

        // Intento de recuperación si la carpeta cambió de nombre o es temporal
        if (strpos($ruta, '/') !== false) {
            $nombreArchivo = basename($ruta);
            // Buscar en temporales
            $posibles_rutas[] = UPLOADS_PATH . 'temp_solicitudes/' . $nombreArchivo;
        }

        $archivo_encontrado = null;
        foreach ($posibles_rutas as $r) {
            if (file_exists($r) && is_file($r)) {
                $archivo_encontrado = $r;
                break;
            }
        }

        if (!$archivo_encontrado) {
            die('Error físico: El archivo no se encuentra en el servidor. Buscado en: ' . implode(', ', $posibles_rutas));
        }

        if (ob_get_level())
            ob_end_clean();

        // Detección dinámica de MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($archivo_encontrado);

        // Fallback si finfo falla
        if (!$mimeType) {
            $ext = strtolower(pathinfo($archivo_encontrado, PATHINFO_EXTENSION));
            $mimes = [
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png'
            ];
            $mimeType = $mimes[$ext] ?? 'application/octet-stream';
        }

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . basename($archivo_encontrado) . '"');
        header('Content-Length: ' . filesize($archivo_encontrado));

        readfile($archivo_encontrado);
        exit;
    }

    public function eliminar($id)
    {
        requirePermiso('solicitudes.aprobar');
        verificarCSRF();

        $solicitud = $this->solicitudModel->getById($id);
        if (!$solicitud) {
            setFlash('error', 'Solicitud no encontrada.');
            redirect('solicitudes');
        }

        if ($this->solicitudModel->delete($id)) {
            setFlash('success', 'Solicitud eliminada correctamente.');
        } else {
            setFlash('error', 'Error al eliminar la solicitud.');
        }

        redirect('solicitudes');
    }
}