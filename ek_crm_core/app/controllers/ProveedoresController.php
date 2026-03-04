<?php
// app/controllers/ProveedoresController.php

require_once ROOT_PATH . 'app/config/db.php';
require_once ROOT_PATH . 'app/helpers/session.php';
require_once ROOT_PATH . 'app/helpers/functions.php';
require_once ROOT_PATH . 'app/helpers/logs.php';
require_once ROOT_PATH . 'app/helpers/Email.php';
require_once ROOT_PATH . 'app/models/DatosBancarios.php';
require_once ROOT_PATH . 'app/models/Proveedor.php';
require_once ROOT_PATH . 'app/models/Catalogo.php';
require_once ROOT_PATH . 'app/models/User.php';
require_once ROOT_PATH . 'app/models/Solicitud.php';
require_once ROOT_PATH . 'app/helpers/auth.php';
require_once ROOT_PATH . 'app/helpers/csrf.php';

class ProveedoresController
{
    private $proveedorModel;
    private $catalogoModel;
    private $userModel;
    private $solicitudModel;
    private $datosBancariosModel;
    private $driveService;

    public function __construct()
    {
        requireAuth();
        $this->proveedorModel = new Proveedor();
        $this->catalogoModel = new Catalogo();
        $this->userModel = new User();
        $this->solicitudModel = new Solicitud();
        $this->datosBancariosModel = new DatosBancarios();
        $this->driveService = new GoogleDriveService();
    }

    public function index()
    {
        requirePermiso('proveedores.ver');

        $termino = $_GET['q'] ?? '';
        $estatus = $_GET['estatus'] ?? '';
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $perPage = 20; // Registros por página

        $filtros = [];
        if (!empty($termino)) {
            $filtros['buscar'] = $termino;
        }
        if (!empty($estatus)) {
            $filtros['estatus'] = $estatus;
        }

        if (!esAdmin()) {
            $filtros['excluir_estatus'] = 'PENDIENTE';
        }

        // Contar total para paginación
        $totalProveedores = $this->proveedorModel->contarTodos($filtros);
        $totalPages = ceil($totalProveedores / $perPage);

        // Asegurar que la página no exceda el total
        $page = min($page, max(1, $totalPages));

        // Aplicar paginación
        $filtros['limit'] = $perPage;
        $filtros['offset'] = ($page - 1) * $perPage;

        $proveedores = $this->proveedorModel->getAll($filtros);

        // Datos de paginación para la vista
        $pagination = [
            'current' => $page,
            'total' => $totalPages,
            'perPage' => $perPage,
            'totalRecords' => $totalProveedores,
            'from' => ($page - 1) * $perPage + 1,
            'to' => min($page * $perPage, $totalProveedores)
        ];

        require_once VIEWS_PATH . 'proveedores/index.php';
    }

    public function crear()
    {
        requirePermiso('proveedores.crear');

        $bancos = $this->catalogoModel->getBancos(1);
        $cias = $this->catalogoModel->getCias(1);
        $regimenes = $this->catalogoModel->getRegimenes(1);
        $estados = $this->catalogoModel->getEstados();

        $proveedor = [];

        // Obtener último consecutivo de RFC genérico (solo para admin)
        $ultimoConsecutivoRFC = null;
        if (esAdmin()) {
            $ultimoConsecutivoRFC = $this->proveedorModel->getUltimoConsecutivoRFCGenerico();
        }

        require_once VIEWS_PATH . 'proveedores/crear.php';
    }

    public function guardar()
    {
        requirePermiso('proveedores.crear');
        verificarCSRF();

        try {
            $db = (new Database())->getConnection();
            $db->beginTransaction();

            $rfc = strtoupper(trim($_POST['RFC'] ?? ''));
            $esGenerico = isset($_POST['EsGenerico']) && $_POST['EsGenerico'] == '1';

            if ($esGenerico && esAdmin()) {
                // Generar RFC Genérico único
                $ultimoConsecutivo = $this->proveedorModel->getUltimoConsecutivoRFCGenerico();
                $nuevoConsecutivo = str_pad($ultimoConsecutivo + 1, 3, '0', STR_PAD_LEFT);
                $rfc = "XAXX010101" . $nuevoConsecutivo;
            } else {
                if (empty($rfc))
                    throw new Exception('El RFC es obligatorio.');
                if (!preg_match('/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/', $rfc))
                    throw new Exception('El formato del RFC no es válido.');
                if ($this->proveedorModel->existeRFC($rfc))
                    throw new Exception('El RFC ya está registrado.');
            }

            $idManual = null;
            if (esAdmin() && !empty($_POST['IdManual'])) {
                $idManual = strtoupper(trim($_POST['IdManual']));
                if ($this->proveedorModel->existeIdManual($idManual))
                    throw new Exception('El Código Interno ya existe.');
            }

            // Permitir selección manual de TipoPersona para RFC genérico
            if ($esGenerico && !empty($_POST['TipoPersona'])) {
                $tipoPersona = $_POST['TipoPersona'];
            } else {
                $tipoPersona = (strlen($rfc) === 12) ? 'MORAL' : 'FISICA';
            }

            $nombreCarpeta = !empty($idManual) ? $idManual : $rfc;

            // GOOGLE DRIVE INTEGRATION
            $driveFolderId = null;
            if (!$this->driveService->isConnected()) {
                throw new Exception('No se pudo conectar con Google Drive. El servicio es obligatorio.');
            }

            // Create/Get RFC Folder
            $driveFolderId = $this->driveService->createFolder($nombreCarpeta);
            if (!$driveFolderId) {
                throw new Exception("No se pudo crear/acceder a la carpeta del proveedor en Google Drive ($nombreCarpeta).");
            }

            $rutaConstancia = null;
            $sinCSF = isset($_POST['SinCSF']) && $_POST['SinCSF'] == '1';

            if (isset($_FILES['fileConstancia']) && $_FILES['fileConstancia']['error'] === UPLOAD_ERR_OK) {
                $rutaConstancia = $this->driveService->uploadFile(
                    $_FILES['fileConstancia']['tmp_name'],
                    $driveFolderId,
                    'CSF_' . $rfc . '.pdf',
                    $_FILES['fileConstancia']['type']
                );
            } else if (!esAdmin() || (!$sinCSF)) {
                // Si no es admin, o si es admin pero no marcó "Sin CSF", es obligatoria
                if (!esAdmin() || !isset($_FILES['fileConstancia']) || $_FILES['fileConstancia']['error'] !== UPLOAD_ERR_OK) {
                    // Solo arrojar si realmente falta y no es admin con bypass
                    if (!esAdmin())
                        throw new Exception('La Constancia de Situación Fiscal es obligatoria.');
                    else if (!$sinCSF)
                        throw new Exception('Debe subir la CSF o marcar la opción "Sin CSF".');
                }
            }

            $estatus = esAdmin() ? 'APROBADO' : 'PENDIENTE';

            // MEJORA: Limpiar formato de Límite de Crédito
            $limiteCredito = str_replace(['$', ','], '', $_POST['LimiteCredito'] ?? '0');

            $datosProveedor = [
                'IdManual' => $idManual,
                'RFC' => $rfc,
                'TipoPersona' => $tipoPersona,
                'TipoProveedor' => $_POST['TipoProveedor'] ?? null,
                'RazonSocial' => strtoupper($_POST['RazonSocial'] ?? ''),
                'NombreComercial' => strtoupper($_POST['NombreComercial'] ?? ''),
                'Nombre' => strtoupper($_POST['Nombre'] ?? ''),
                'ApellidoPaterno' => strtoupper($_POST['ApellidoPaterno'] ?? ''),
                'ApellidoMaterno' => strtoupper($_POST['ApellidoMaterno'] ?? ''),
                'RegimenFiscalId' => !empty($_POST['RegimenFiscalId']) ? $_POST['RegimenFiscalId'] : null,
                'CorreoPagosInterno' => strtolower($_POST['CorreoPagosInterno'] ?? ''),
                'CorreoProveedor' => strtolower($_POST['CorreoProveedor'] ?? ''),
                'Responsable' => strtoupper($_POST['Responsable'] ?? ''),
                'LimiteCredito' => $limiteCredito,
                'Calle' => strtoupper($_POST['Calle'] ?? ''),
                'NumeroExterior' => $_POST['NumeroExterior'] ?? '',
                'NumeroInterior' => $_POST['NumeroInterior'] ?? '',
                'Colonia' => strtoupper($_POST['Colonia'] ?? ''),
                'CP' => $_POST['CP'] ?? '',
                'Estado' => $_POST['Estado'] ?? null,
                'Municipio' => strtoupper(trim($_POST['Municipio'] ?? '')),
                'RutaConstancia' => $rutaConstancia,
                'RutaCaratula' => null,
                'Estatus' => $estatus,
                'UsuarioCreadorId' => usuarioId()
            ];

            $proveedor_id = $this->proveedorModel->create($datosProveedor);
            if (!$proveedor_id)
                throw new Exception('Error al guardar el proveedor.');

            $ciasData = $_POST['Cias'] ?? [];
            $rutaMaestra = null;

            if (isset($_FILES['archivo_maestro']) && $_FILES['archivo_maestro']['error'] === UPLOAD_ERR_OK) {
                $cuentasFolderId = $this->driveService->createFolder('cuentas', $driveFolderId);
                if (!$cuentasFolderId) {
                    throw new Exception('No se pudo crear la subcarpeta de cuentas en Google Drive.');
                }

                $rutaMaestra = $this->driveService->uploadFile(
                    $_FILES['archivo_maestro']['tmp_name'],
                    $cuentasFolderId,
                    'BANC_GEN_' . time() . '.pdf',
                    $_FILES['archivo_maestro']['type']
                );
            }

            $countCias = 0;
            foreach ($ciasData as $ciaId => $data) {
                if (!isset($data['selected']) || $data['selected'] != 1)
                    continue;
                $countCias++;

                $rutaCaratulaFinal = $rutaMaestra;

                if (!empty($_FILES['Cias']['name'][$ciaId]['archivo_propio'])) {
                    $cuentasFolderId = $this->driveService->createFolder('cuentas', $driveFolderId);

                    if (!$cuentasFolderId) {
                        throw new Exception("No se pudo crear la subcarpeta de cuentas para la Cía $ciaId en Google Drive.");
                    }

                    $rutaCaratulaFinal = $this->driveService->uploadFile(
                        $_FILES['Cias']['tmp_name'][$ciaId]['archivo_propio'],
                        $cuentasFolderId,
                        'BANC_CIA' . $ciaId . '_' . time() . '.pdf',
                        $_FILES['Cias']['type'][$ciaId]['archivo_propio']
                    );
                }

                $sinCaratula = isset($_POST['SinCaratula']) && $_POST['SinCaratula'] == '1';

                if (!$rutaCaratulaFinal && (!esAdmin() || !$sinCaratula)) {
                    throw new Exception("Falta documento bancario para la compañía seleccionada.");
                }

                $this->proveedorModel->asignarCia($proveedor_id, $ciaId);

                $this->datosBancariosModel->crearCuenta([
                    'ProveedorId' => $proveedor_id,
                    'CiaId' => $ciaId,
                    'RutaCaratula' => $rutaCaratulaFinal,
                    'EsPrincipal' => 1,
                    'Estatus' => esAdmin() ? 'APROBADO' : 'PENDIENTE',
                    'Activo' => 1,
                    'BancoId' => (esAdmin() && !empty($_POST['BancoId'])) ? $_POST['BancoId'] : null,
                    'Cuenta' => !empty($_POST['Cuenta']) ? $_POST['Cuenta'] : null,
                    'Clabe' => !empty($_POST['Clabe']) ? $_POST['Clabe'] : null,
                    'Sucursal' => !empty($_POST['Sucursal']) ? $_POST['Sucursal'] : null,
                    'Plaza' => !empty($_POST['Plaza']) ? $_POST['Plaza'] : null
                ], usuarioId());
            }

            if ($countCias === 0)
                throw new Exception("Debe seleccionar al menos una compañía.");

            if (!esAdmin()) {
                $this->solicitudModel->create([
                    'ProveedorId' => $proveedor_id,
                    'SolicitanteId' => usuarioId(),
                    'DatosJson' => [
                        'TipoCambio' => 'ALTA NUEVO PROVEEDOR',
                        'Urgencia' => 'Alta',
                        'CiasObjetivoIds' => array_keys(array_filter($ciasData, function ($c) {
                            return isset($c['selected']);
                        }))
                    ],
                    'RutaConstanciaNueva' => $rutaConstancia,
                    'RutaCaratulaNueva' => $rutaMaestra,
                    'CiaObjetivo' => 0,
                    'Estatus' => 'PENDIENTE'
                ]);
            }

            $db->commit();

            try {
                $mailer = new EmailHelper();
                $adminEmail = $_ENV['ADMIN_EMAIL'] ?? 'ecruz@urbanopark.com';
                $ciasNombres = "Varias ($countCias)";
                $mailer->solicitudAltaProceso(usuarioActual()['email'], $datosProveedor, $ciasNombres, [$adminEmail]);
            } catch (Exception $e) {
                error_log("Error al enviar correo de alta: " . $e->getMessage());
            }

            setFlash('success', esAdmin() ? 'Proveedor registrado y aprobado.' : 'Proveedor registrado. Solicitud de Alta generada exitosamente.');
            redirect('proveedores');

        } catch (Exception $e) {
            if (isset($db))
                $db->rollBack();
            error_log("Error in ProveedoresController::guardar: " . $e->getMessage());
            setFlash('error', 'Error al guardar el proveedor: ' . $e->getMessage());
            redirect('proveedores/crear');
        }
    }

    public function ver($id)
    {
        requirePermiso('proveedores.ver');
        $proveedor = $this->proveedorModel->getById($id);

        if (!$proveedor) {
            setFlash('error', 'Proveedor no encontrado.');
            redirect('proveedores');
        }

        $cias_asignadas = $this->proveedorModel->getCias($id);
        $cuentas = $this->datosBancariosModel->getCuentasByProveedor($id);

        $solicitudPendiente = null;
        if (method_exists($this->solicitudModel, 'getPendientePorProveedor')) {
            $solicitudPendiente = $this->solicitudModel->getPendientePorProveedor($id);
        }

        require_once VIEWS_PATH . 'proveedores/ver.php';
    }

    public function editar($id)
    {
        requirePermiso('proveedores.editar');
        $proveedor = $this->proveedorModel->getById($id);
        if (!$proveedor)
            redirect('proveedores');

        $bancos = $this->catalogoModel->getBancos(1);
        $cias = $this->catalogoModel->getCias(1);
        $regimenes = $this->catalogoModel->getRegimenes(1);
        $estados = $this->catalogoModel->getEstados();

        // Obtener IDs de compañías asignadas actualmente
        $ciasActuales = $this->proveedorModel->getCias($id);
        $ciasIds = array_column($ciasActuales, 'Id');

        $solicitudPendiente = null;
        if (method_exists($this->solicitudModel, 'getPendientePorProveedor')) {
            $solicitudPendiente = $this->solicitudModel->getPendientePorProveedor($id);
        }

        require_once VIEWS_PATH . 'proveedores/editar.php';
    }

    public function actualizar($id)
    {
        requirePermiso('proveedores.editar');
        verificarCSRF();

        try {
            $proveedor = $this->proveedorModel->getById($id);
            $rfc = strtoupper(trim($_POST['RFC']));
            $idManual = strtoupper(trim($_POST['IdManual'] ?? ''));

            $folderOld = !empty($proveedor['IdManual']) ? $proveedor['IdManual'] : $proveedor['RFC'];
            $folderNew = !empty($idManual) ? $idManual : $rfc;

            if ($folderOld !== $folderNew) {
                if ($this->driveService->isConnected()) {
                    // Try to find old folder
                    $rootFolderId = defined('GOOGLE_DRIVE_ROOT_FOLDER_ID') ? GOOGLE_DRIVE_ROOT_FOLDER_ID : 'root';
                    $oldFolderId = $this->driveService->findFileIdByName($folderOld, $rootFolderId, true);

                    if ($oldFolderId) {
                        $this->driveService->renameFile($oldFolderId, $folderNew);
                    }
                }
                // Legacy rename
                if (is_dir(UPLOADS_PATH . $folderOld)) {
                    rename(UPLOADS_PATH . $folderOld, UPLOADS_PATH . $folderNew);
                }
            }

            $rutaConstancia = $proveedor['RutaConstancia'];
            $sinCSF = isset($_POST['SinCSF']) && $_POST['SinCSF'] == '1';

            if (isset($_FILES['ArchivoCSF']) && $_FILES['ArchivoCSF']['error'] === UPLOAD_ERR_OK) {
                if (!$this->driveService->isConnected()) {
                    throw new Exception('No se pudo conectar con Google Drive para actualizar la Constancia.');
                }

                $driveFolderId = $this->driveService->createFolder($folderNew);
                if (!$driveFolderId) {
                    throw new Exception("No se pudo acceder a la carpeta del proveedor en Google Drive ($folderNew).");
                }

                $rutaConstancia = $this->driveService->uploadFile(
                    $_FILES['ArchivoCSF']['tmp_name'],
                    $driveFolderId,
                    'CSF_' . $rfc . '.pdf',
                    $_FILES['ArchivoCSF']['type']
                );
            } elseif ($folderOld !== $folderNew && $rutaConstancia && strpos($rutaConstancia, '/') !== false) {
                // Only replace path if it looks like a local path (contains /)
                $rutaConstancia = str_replace($folderOld, $folderNew, $rutaConstancia);
            }

            // Validar si falta CSF y no es admin con bypass
            if (!$rutaConstancia && (!esAdmin() || !$sinCSF)) {
                throw new Exception("La Constancia de Situación Fiscal es obligatoria.");
            }

            // MEJORA: Limpiar formato de Límite de Crédito
            $limiteLimpio = str_replace(['$', ','], '', $_POST['LimiteCredito'] ?? '0');

            $datos = [
                'IdManual' => $idManual,
                'RFC' => $rfc,
                'RazonSocial' => strtoupper($_POST['RazonSocial'] ?? ''),
                'NombreComercial' => strtoupper($_POST['NombreComercial'] ?? ''),
                'Nombre' => strtoupper($_POST['Nombre'] ?? ''),
                'ApellidoPaterno' => strtoupper($_POST['ApellidoPaterno'] ?? ''),
                'ApellidoMaterno' => strtoupper($_POST['ApellidoMaterno'] ?? ''),
                'RegimenFiscalId' => !empty($_POST['RegimenFiscalId']) ? $_POST['RegimenFiscalId'] : null,
                'Estatus' => $_POST['Estatus'],
                'Responsable' => strtoupper($_POST['Responsable']),
                'CorreoProveedor' => strtolower($_POST['CorreoProveedor']),
                'CorreoPagosInterno' => strtolower($_POST['CorreoPagosInterno']),
                'Calle' => strtoupper($_POST['Calle']),
                'NumeroExterior' => $_POST['NumeroExterior'] ?? '',
                'NumeroInterior' => $_POST['NumeroInterior'] ?? '',
                'Colonia' => strtoupper($_POST['Colonia']),
                'CP' => $_POST['CP'],
                'Estado' => $_POST['Estado'],
                'Municipio' => strtoupper($_POST['Municipio']),
                'TipoPersona' => esAdmin() && !empty($_POST['TipoPersonaManual']) ? $_POST['TipoPersonaManual'] : ((strlen($rfc) === 12) ? 'MORAL' : 'FISICA'),
                'TipoProveedor' => $_POST['TipoProveedor'] ?? null,
                'LimiteCredito' => $limiteLimpio, // Asignación del valor numérico limpio
                'Cias' => $_POST['Cias'] ?? []
            ];

            if ($this->proveedorModel->update($id, $datos)) {
                $this->proveedorModel->updateArchivos($id, ['RutaConstancia' => $rutaConstancia]);

                // SINCRONIZACIÓN BIDIRECCIONAL: Propagar compañías a cuentas bancarias
                $this->datosBancariosModel->sincronizarConProveedor($id, $datos['Cias'], usuarioId());

                // AUTO-APROBAR SOLICITUDES PENDIENTES (Si es Admin y pone APROBADO)
                if (esAdmin() && $_POST['Estatus'] === 'APROBADO') {
                    if (method_exists($this->solicitudModel, 'getPendientePorProveedor')) {
                        $pendiente = $this->solicitudModel->getPendientePorProveedor($id);
                        if ($pendiente && in_array($pendiente['TipoCambio'], ['ALTA NUEVO PROVEEDOR', 'Datos Generales', 'Datos de Contacto'])) {
                            $this->solicitudModel->aprobar($pendiente['Id']);
                        }
                    }
                }

                // MEJORA: Envío de Ficha Técnica Completa (Generales + Contacto + Bancos)
                if (isset($_POST['enviar_ficha']) && $_POST['enviar_ficha'] == '1') {
                    try {
                        $mailer = new EmailHelper();

                        // 1. Datos frescos del proveedor (incluye descripción de régimen)
                        $provFull = $this->proveedorModel->getById($id);

                        // 2. Obtener compañías asignadas
                        $ciasAsignadas = $this->proveedorModel->getCias($id);
                        $provFull['cias_nombres'] = implode(', ', array_column($ciasAsignadas, 'Nombre'));

                        // 3. Obtener todas las cuentas bancarias aprobadas
                        $cuentas = $this->datosBancariosModel->getCuentasByProveedor($id);

                        // 4. Envío profesional integrado (Se envía al CAPTURISTA que creó el registro)
                        $capturista = $this->userModel->getById($provFull['UsuarioCreadorId']);
                        if ($capturista && !empty($capturista['email'])) {
                            $mailer->solicitudAprobada($capturista['email'], $provFull, $cuentas);
                        }

                        // 5. Envío opcional a correo adicional
                        $emailAdicional = trim($_POST['email_adicional'] ?? '');
                        if (!empty($emailAdicional) && filter_var($emailAdicional, FILTER_VALIDATE_EMAIL)) {
                            $mailer->solicitudAprobada($emailAdicional, $provFull, $cuentas);
                        }

                    } catch (Exception $e) {
                        error_log("Error al enviar ficha técnica completa: " . $e->getMessage());
                    }
                }

                setFlash('success', 'Proveedor actualizado correctamente.');
                redirect('proveedores/editar/' . $id);
            } else {
                // Si llegamos aquí, el modelo retornó false (error capturado en el try/catch del modelo)
                throw new Exception("El modelo no pudo completar la actualización. Verifique los registros del sistema.");
            }
        } catch (Throwable $e) {
            error_log("Error in ProveedoresController::actualizar: " . $e->getMessage());
            setFlash('error', 'Error crítico al actualizar: ' . $e->getMessage());
            redirect('proveedores/editar/' . $id);
        }
    }

    public function solicitarCambio($id)
    {
        requirePermiso('proveedores.solicitar_cambio');
        $proveedor = $this->proveedorModel->getById($id);
        $cias_asignadas = $this->proveedorModel->getCias($id);
        $todas_cias = $this->catalogoModel->getCias(1);
        require_once VIEWS_PATH . 'proveedores/solicitar_cambio.php';
    }

    public function guardarSolicitud()
    {
        requirePermiso('proveedores.solicitar_cambio');
        verificarCSRF();
        try {
            $proveedorId = $_POST['IdProveedor'];
            $tipoCambio = $_POST['tipo_cambio'];

            // MEJORA: Limpiar formato de Límite de Crédito
            $limiteCredito = str_replace(['$', ','], '', $_POST['LimiteCredito'] ?? '0');

            $datosJson = [
                'TipoCambio' => $tipoCambio,
                'Urgencia' => $_POST['urgencia'],
                'LimiteCredito' => $limiteCredito
            ];

            $rutaConstancia = null;
            if ($tipoCambio === 'Datos Generales' && !empty($_FILES['fileConstancia']['name'])) {
                if (!$this->driveService->isConnected()) {
                    throw new Exception('No se pudo conectar con Google Drive para subir la Constancia.');
                }

                $tempFolderId = $this->driveService->createFolder('temp_solicitudes');
                if (!$tempFolderId) {
                    throw new Exception('No se pudo crear/acceder a la carpeta temporal en Google Drive.');
                }

                $fileId = $this->driveService->uploadFile(
                    $_FILES['fileConstancia']['tmp_name'],
                    $tempFolderId,
                    'SOL_CSF_' . time() . '.pdf',
                    $_FILES['fileConstancia']['type']
                );

                if (!$fileId) {
                    throw new Exception('Error al subir Constancia solicitada a Google Drive.');
                }
                $rutaConstancia = $fileId;
            }

            $rutaCaratula = null;
            if ($tipoCambio === 'Cuentas Bancarias') {
                $datosJson['CiasObjetivoIds'] = $_POST['CiasObjetivo'] ?? [];

                if (!$this->driveService->isConnected()) {
                    throw new Exception('No se pudo conectar con Google Drive para subir archivos de la solicitud.');
                }

                $tempFolderId = $this->driveService->createFolder('temp_solicitudes');
                if (!$tempFolderId) {
                    throw new Exception('No se pudo crear/acceder a la carpeta temporal en Google Drive.');
                }

                if (!empty($_FILES['archivo_maestro']['name'])) {
                    $fileId = $this->driveService->uploadFile(
                        $_FILES['archivo_maestro']['tmp_name'],
                        $tempFolderId,
                        'SOL_GEN_' . time() . '.pdf',
                        $_FILES['archivo_maestro']['type']
                    );
                    if (!$fileId) {
                        throw new Exception('Error al subir Carátula General solicitada a Google Drive.');
                    }
                    $rutaCaratula = $fileId;
                }

                $archivosPorCia = [];
                foreach ($datosJson['CiasObjetivoIds'] as $ciaId) {
                    if (!empty($_FILES['ArchivosCias']['name'][$ciaId])) {
                        $fileId = $this->driveService->uploadFile(
                            $_FILES['ArchivosCias']['tmp_name'][$ciaId],
                            $tempFolderId,
                            'SOL_CIA_' . $ciaId . '_' . time() . '.pdf',
                            $_FILES['ArchivosCias']['type'][$ciaId]
                        );
                        if (!$fileId) {
                            throw new Exception("Error al subir Carátula para Cía $ciaId a Google Drive.");
                        }
                        $archivosPorCia[$ciaId] = $fileId;
                    }
                }
                $datosJson['Archivos'] = $archivosPorCia;
            }

            if ($tipoCambio === 'Datos de Contacto') {
                $datosJson['Responsable'] = $_POST['Responsable'];
                $datosJson['NombreComercial'] = $_POST['NombreComercial'];
                $datosJson['CorreoPagosInterno'] = $_POST['CorreoPagosInterno'];
                $datosJson['CorreoProveedor'] = $_POST['CorreoProveedor'];
            }

            $solicitudData = [
                'ProveedorId' => $proveedorId,
                'SolicitanteId' => usuarioId(),
                'DatosJson' => $datosJson,
                'RutaConstanciaNueva' => $rutaConstancia,
                'RutaCaratulaNueva' => $rutaCaratula,
                'CiaObjetivo' => 0
            ];

            if ($this->solicitudModel->create($solicitudData)) {
                try {
                    $mailer = new EmailHelper();
                    $prov = $this->proveedorModel->getById($proveedorId);
                    $mailer->solicitudCambio(usuarioActual()['email'], $prov, "Cambio solicitado: $tipoCambio", [$_ENV['ADMIN_EMAIL']]);
                } catch (Exception $e) {
                    error_log("Error al enviar correo de solicitud: " . $e->getMessage());
                }

                setFlash('success', 'Solicitud enviada al Administrador.');
                redirect('proveedores/ver/' . $proveedorId);
            }
        } catch (Exception $e) {
            setFlash('error', $e->getMessage());
            redirect('proveedores/solicitarCambio/' . $_POST['IdProveedor']);
        }
    }

    public function verArchivo($tipo, $id)
    {
        requirePermiso('proveedores.ver_archivos');
        $proveedor = $this->proveedorModel->getById($id);

        if (!$proveedor) {
            die('Proveedor no encontrado');
        }

        $esConstancia = ($tipo === 'csf');
        $ruta_db = $esConstancia ? $proveedor['RutaConstancia'] : $proveedor['RutaCaratula'];

        // GOOGLE DRIVE CHECK
        // If route does not contain slashes, assume it is a Drive ID
        if (!empty($ruta_db) && strpos($ruta_db, '/') === false && strpos($ruta_db, '\\') === false) {
            if ($this->driveService->isConnected()) {
                $content = $this->driveService->getFileContent($ruta_db);
                if ($content) {
                    $meta = $this->driveService->getFileMetadata($ruta_db);
                    $mime = $meta->mimeType ?? 'application/pdf';
                    $filename = $meta->name ?? 'documento.pdf';

                    header('Content-Type: ' . $mime);
                    header('Content-Disposition: inline; filename="' . $filename . '"');
                    // header('Content-Length: ' . strlen($content)); // Optional
                    echo $content;
                    exit;
                }
            }
        }

        // LEGACY / LOCAL FALLBACK
        $ruta_db = str_replace(['../', '..\\'], '', $ruta_db ?? '');
        $nombreArchivoDb = basename($ruta_db);

        $prefijo = $esConstancia ? 'CSF_' : 'BANC_';

        $directorios = [];

        if (!empty($proveedor['IdManual'])) {
            $directorios[] = UPLOADS_PATH . $proveedor['IdManual'] . '/';
            $directorios[] = UPLOADS_PATH . $proveedor['IdManual'] . '/cuentas/';
        }

        if (!empty($proveedor['RFC'])) {
            $directorios[] = UPLOADS_PATH . $proveedor['RFC'] . '/';
            $directorios[] = UPLOADS_PATH . $proveedor['RFC'] . '/cuentas/';
        }

        $archivo_encontrado = null;

        if (!empty($ruta_db)) {
            $rutasDirectas = [
                UPLOADS_PATH . $ruta_db,
                UPLOADS_PATH . ltrim($ruta_db, '/')
            ];
            foreach ($rutasDirectas as $r) {
                if (file_exists($r) && is_file($r)) {
                    $archivo_encontrado = $r;
                    break;
                }
            }
        }

        if (!$archivo_encontrado && !empty($nombreArchivoDb)) {
            foreach ($directorios as $dir) {
                if (file_exists($dir . $nombreArchivoDb)) {
                    $archivo_encontrado = $dir . $nombreArchivoDb;
                    break;
                }
            }
        }

        if (!$archivo_encontrado) {
            foreach ($directorios as $dir) {
                if (is_dir($dir)) {
                    $patron = $dir . $prefijo . '*.*'; // Buscar cualquier extensión
                    $coincidencias = glob($patron);

                    if (!empty($coincidencias)) {
                        usort($coincidencias, function ($a, $b) {
                            return filemtime($b) - filemtime($a);
                        });
                        $archivo_encontrado = $coincidencias[0];
                        break;
                    }
                }
            }
        }

        if ($archivo_encontrado) {
            if (ob_get_length())
                ob_clean();

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
            header('Cache-Control: private, max-age=0, must-revalidate');

            readfile($archivo_encontrado);
            exit;
        } else {
            echo '<div style="font-family:monospace; background:#f8d7da; color:#721c24; padding:20px; border:1px solid #f5c6cb; border-radius:5px;">';
            echo '<h3>Error: Archivo físico no encontrado</h3>';
            echo '<p>El sistema intentó buscar en las carpetas del servidor y no encontró coincidencias.</p>';
            echo '<strong>Directorios explorados:</strong><ul>';
            foreach ($directorios as $d)
                echo "<li>$d</li>";
            echo '</ul>';
            echo '<strong>Buscando patrón:</strong> ' . $prefijo . '*.pdf<br>';
            echo '<strong>Ruta BD original:</strong> ' . htmlspecialchars($ruta_db) . '<br>';
            echo '<strong>ID Manual:</strong> ' . htmlspecialchars($proveedor['IdManual'] ?? 'N/A') . '<br>';
            echo '<strong>RFC:</strong> ' . htmlspecialchars($proveedor['RFC']) . '<br>';
            echo '</div>';
            exit;
        }
    }

    public function eliminar($id)
    {
        requirePermiso('proveedores.eliminar');
        verificarCSRF();
        if ($this->proveedorModel->delete($id)) {
            setFlash('success', 'Proveedor eliminado correctamente.');
        } else {
            setFlash('error', 'Error al eliminar el proveedor.');
        }
        redirect('proveedores');
    }

    public function importar()
    {
        requirePermiso('proveedores.crear');

        if (esCapturista()) {
            setFlash('error', 'No tiene permisos para realizar importaciones masivas.');
            redirect('proveedores');
        }

        require_once VIEWS_PATH . 'proveedores/importar.php';
    }

    public function descargarPlantilla()
    {
        requirePermiso('proveedores.crear');

        if (esCapturista()) {
            die('Acceso denegado');
        }

        $filename = 'plantilla_proveedores_v3.csv';

        if (ob_get_level())
            ob_end_clean();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, [
            'ID Interno (Opcional)',
            'RFC (Obligatorio)',
            'Tipo Persona (FISICA/MORAL)',
            'Tipo Proveedor (1-5)',
            'Razón Social',
            'Nombre Comercial',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Clave Régimen Fiscal',
            'Calle',
            'No. Exterior',
            'No. Interior',
            'Colonia',
            'Código Postal',
            'Estado',
            'Municipio',
            'Nombre Responsable',
            'Correo Proveedor',
            'Correo Pagos Interno',
            'Límite Crédito',
            'IDs Compañías',
            'Banco',
            'No. Cuenta',
            'CLABE',
            'Sucursal',
            'Plaza'
        ]);

        fclose($output);
        exit;
    }

    public function descargarPlantillaConDatos()
    {
        requirePermiso('proveedores.ver');

        if (esCapturista()) {
            die('Acceso denegado');
        }

        $filename = 'plantilla_proveedores_con_datos_' . date('Y-m-d') . '.csv';

        if (ob_get_level())
            ob_end_clean();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        $headers = [
            'ID Interno (Opcional)',
            'RFC (Obligatorio)',
            'Tipo Persona (FISICA/MORAL)',
            'Tipo Proveedor (1-5)',
            'Razón Social',
            'Nombre Comercial',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Clave Régimen Fiscal',
            'Calle',
            'No. Exterior',
            'No. Interior',
            'Colonia',
            'Código Postal',
            'Estado',
            'Municipio',
            'Nombre Responsable',
            'Correo Proveedor',
            'Correo Pagos Interno',
            'Límite Crédito',
            'IDs Compañías',
            'Banco',
            'No. Cuenta',
            'CLABE',
            'Sucursal',
            'Plaza'
        ];
        fputcsv($output, $headers);

        // Obtener datos detallados
        $db = (new Database())->getConnection();
        // Detectar si la columna en Cat_Regimenes es 'Clave' o 'CodigoSAT'
        $colRegimen = 'CodigoSAT'; // Default según schema.sql
        try {
            $stmtTest = $db->query("SHOW COLUMNS FROM Cat_Regimenes LIKE 'Clave'");
            if ($stmtTest->rowCount() > 0) {
                $colRegimen = 'Clave';
            }
        } catch (Exception $e) {
        }

        $sql = "
            SELECT p.*, r.{$colRegimen} as ClaveRegimen,
                   (SELECT GROUP_CONCAT(pc.CiaId) FROM Proveedor_Cias pc WHERE pc.ProveedorId = p.Id) as cias_ids
            FROM Proveedores p
            LEFT JOIN Cat_Regimenes r ON p.RegimenFiscalId = r.Id
        ";

        try {
            $stmt = $db->query($sql);
            $proveedores = $stmt->fetchAll();
        } catch (Exception $e) {
            // Auto-migración si falta la columna
            if (strpos($e->getMessage(), 'NombreComercial') !== false) {
                try {
                    $db->exec("ALTER TABLE Proveedores ADD COLUMN NombreComercial VARCHAR(255) NULL AFTER RazonSocial");
                    $stmt = $db->query($sql);
                    $proveedores = $stmt->fetchAll();
                } catch (Exception $e2) {
                    die("Error crítico de base de datos. Póngase en contacto con el administrador para añadir la columna NombreComercial a la tabla Proveedores. Error: " . $e2->getMessage());
                }
            } else {
                throw $e;
            }
        }

        $dbBancarios = new DatosBancarios();

        foreach ($proveedores as $p) {
            // Obtener cuenta principal para los datos bancarios del CSV
            $cuentas = $dbBancarios->getCuentasByProveedor($p['Id']);
            $cuentaP = null;
            foreach ($cuentas as $c) {
                if ($c['EsPrincipal']) {
                    $cuentaP = $c;
                    break;
                }
            }
            if (!$cuentaP && !empty($cuentas))
                $cuentaP = $cuentas[0];

            fputcsv($output, [
                $p['IdManual'] ?: $p['Id'],
                $p['RFC'],
                $p['TipoPersona'],
                $p['TipoProveedor'],
                $p['RazonSocial'],
                $p['NombreComercial'],
                $p['Nombre'],
                $p['ApellidoPaterno'],
                $p['ApellidoMaterno'],
                $p['ClaveRegimen'],
                $p['Calle'],
                $p['NumeroExterior'],
                $p['NumeroInterior'],
                $p['Colonia'],
                $p['CP'],
                $p['Estado'],
                $p['Municipio'],
                $p['Responsable'],
                $p['CorreoProveedor'],
                $p['CorreoPagosInterno'],
                $p['LimiteCredito'],
                $p['cias_ids'] ?: '',
                $cuentaP['BancoNombre'] ?? '',
                $cuentaP['Cuenta'] ?? '',
                $cuentaP['Clabe'] ?? '',
                $cuentaP['Sucursal'] ?? '',
                $cuentaP['Plaza'] ?? ''
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportar()
    {
        requirePermiso('proveedores.ver');

        if (esCapturista()) {
            setFlash('error', 'No tiene permisos para exportar reportes.');
            redirect('proveedores');
        }

        $filename = 'proveedores_completo_' . date('Y-m-d') . '.csv';
        if (ob_get_level())
            ob_end_clean();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['ID Interno', 'Tipo Proveedor', 'RFC', 'Razón Social', 'Nombre Comercial', 'Contacto', 'Email', 'Ubicación', 'Estatus', 'Compañías Asignadas', 'Banco Principal', 'Cuenta', 'CLABE']);

        $proveedores = $this->proveedorModel->getAll();
        $dbModel = new DatosBancarios();
        foreach ($proveedores as $p) {
            $cuentas = $dbModel->getCuentasByProveedor($p['Id']);
            $cuentaPrincipal = null;
            foreach ($cuentas as $c) {
                if ($c['EsPrincipal']) {
                    $cuentaPrincipal = $c;
                    break;
                }
            }
            if (!$cuentaPrincipal && !empty($cuentas))
                $cuentaPrincipal = $cuentas[0];

            fputcsv($output, [
                $p['IdManual'] ?? $p['Id'],
                $p['TipoProveedor'] ?? '',
                $p['RFC'] ?? '',
                $p['RazonSocial'] ?? '',
                $p['NombreComercial'] ?? '',
                $p['Responsable'] ?? '',
                $p['CorreoProveedor'] ?? '',
                ($p['Municipio'] ?? '') . ', ' . ($p['Estado'] ?? ''),
                $p['Estatus'] ?? '',
                $p['cias_nombres'] ?? 'Sin asignación',
                $cuentaPrincipal['BancoNombre'] ?? '',
                $cuentaPrincipal['Cuenta'] ?? '',
                $cuentaPrincipal['Clabe'] ?? ' '
            ]);
        }
        fclose($output);
        exit;
    }

    public function procesarImportacion()
    {
        requirePermiso('proveedores.crear');
        verificarCSRF();

        if (esCapturista()) {
            setFlash('error', 'No tiene permisos para realizar importaciones.');
            redirect('proveedores');
        }

        if (!isset($_FILES['archivo_csv']) || $_FILES['archivo_csv']['error'] !== UPLOAD_ERR_OK) {
            setFlash('error', 'Error al subir el archivo.');
            redirect('proveedores/importar');
        }

        $handle = fopen($_FILES['archivo_csv']['tmp_name'], 'r');
        if ($handle === false) {
            setFlash('error', 'No se pudo abrir el archivo CSV.');
            redirect('proveedores/importar');
        }

        $dbModel = new DatosBancarios();
        $row = 0;
        $importados = 0;
        $actualizados = 0;
        $errores_count = 0;
        $lista_errores = [];
        $db = (new Database())->getConnection();

        // --- AUTO-REPARACIÓN DE ESQUEMA ---
        // --- AUTO-REPARACIÓN DE ESQUEMA ---
        try {
            // 1. Asegurar columna NombreComercial
            try {
                $db->query("SELECT NombreComercial FROM Proveedores LIMIT 1");
            } catch (Exception $e) {
                $db->exec("ALTER TABLE Proveedores ADD COLUMN NombreComercial VARCHAR(255) NULL AFTER RazonSocial");
            }

            // 2. Corregir TipoProveedor (INT -> VARCHAR)
            try {
                $db->exec("ALTER TABLE Proveedores MODIFY COLUMN TipoProveedor VARCHAR(255) NULL");
            } catch (Exception $e) {
            }

            // 3. Actualizar valores numéricos a texto completo
            $mapFix = [
                '1' => '1- PROVEEDOR DE BIENES Y SERVICIOS',
                '2' => '2- CONTRATISTA',
                '3' => '3- ACREEDOR DIVERSO',
                '4' => '4- HONORARIOS',
                '5' => '5- ARRENDAMIENTO'
            ];
            foreach ($mapFix as $num => $txt) {
                $stmtFix = $db->prepare("UPDATE Proveedores SET TipoProveedor = ? WHERE TipoProveedor = ?");
                $stmtFix->execute([$txt, $num]);
            }
        } catch (Exception $e) {
            error_log("Error Schema Fix: " . $e->getMessage());
        }

        while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
            $row++;
            if ($row == 1)
                continue;

            $rfc = strtoupper(trim($data[1] ?? ''));
            if (empty($rfc) || strlen($rfc) < 10)
                continue;

            $regimenId = $this->buscarIdRegimen(trim($data[9] ?? ''));
            $tipoPersona = strtoupper(trim($data[2] ?? ''));
            if (empty($tipoPersona)) {
                $tipoPersona = (strlen($rfc) === 12) ? 'MORAL' : 'FISICA';
            }

            try {
                $db->beginTransaction();
                $proveedorExistente = $this->proveedorModel->getByRFC($rfc);

                // Mapear datos comunes con valores por defecto seguros
                $datosProveedor = [
                    'IdManual' => trim($data[0] ?? '') ?: null,
                    'RFC' => $rfc,
                    'TipoPersona' => $tipoPersona,
                    'TipoProveedor' => (function ($val) {
                        $val = trim($val ?? '');
                        $map = [
                            '1' => '1- PROVEEDOR DE BIENES Y SERVICIOS',
                            '2' => '2- CONTRATISTA',
                            '3' => '3- ACREEDOR DIVERSO',
                            '4' => '4- HONORARIOS',
                            '5' => '5- ARRENDAMIENTO'
                        ];
                        // Si es un número del 1 al 5, devolver descripción completa
                        return $map[$val] ?? ($val ?: '1- PROVEEDOR DE BIENES Y SERVICIOS');
                    })($data[3] ?? ''),
                    'RazonSocial' => strtoupper(trim($data[4] ?? '')) ?: (trim($data[6] ?? '') . ' ' . trim($data[7] ?? '')) ?: 'SIN RAZON SOCIAL',
                    'NombreComercial' => strtoupper(trim($data[5] ?? '')) ?: null,
                    'Nombre' => strtoupper(trim($data[6] ?? '')) ?: '',
                    'ApellidoPaterno' => strtoupper(trim($data[7] ?? '')) ?: '',
                    'ApellidoMaterno' => strtoupper(trim($data[8] ?? '')) ?: '',
                    'RegimenFiscalId' => $regimenId ?: null,
                    'Calle' => strtoupper(trim($data[10] ?? '')) ?: 'SIN CALLE',
                    'NumeroExterior' => trim($data[11] ?? '') ?: 'S/N',
                    'NumeroInterior' => trim($data[12] ?? '') ?: '',
                    'Colonia' => strtoupper(trim($data[13] ?? '')) ?: 'SIN COLONIA',
                    'CP' => trim($data[14] ?? '') ?: '00000',
                    'Estado' => strtoupper(trim($data[15] ?? '')) ?: 'NO ESPECIFICADO',
                    'Municipio' => strtoupper(trim($data[16] ?? '')) ?: 'NO ESPECIFICADO',
                    'Responsable' => strtoupper(trim($data[17] ?? '')) ?: 'ADMINISTRACION',
                    'CorreoProveedor' => strtolower(trim($data[18] ?? '')) ?: 'sin_correo@proveedor.com',
                    'CorreoPagosInterno' => strtolower(trim($data[19] ?? '')) ?: 'pagos@interno.com',
                    'LimiteCredito' => floatval(str_replace(['$', ','], '', $data[20] ?? '0')),
                    'Estatus' => 'APROBADO'
                ];

                if ($proveedorExistente) {
                    $proveedor_id = $proveedorExistente['Id'];
                    // Actualizar datos generales
                    $this->proveedorModel->update($proveedor_id, $datosProveedor);
                    $actualizados++;
                } else {
                    $datosProveedor['UsuarioCreadorId'] = usuarioId();
                    $datosProveedor['RutaConstancia'] = null;
                    $datosProveedor['RutaCaratula'] = null;

                    $proveedor_id = $this->proveedorModel->create($datosProveedor);
                    $importados++;
                }
                if ($proveedor_id && !empty(trim($data[21] ?? ''))) {
                    $ciasArray = explode(',', trim($data[21] ?? ''));
                    $bancoId = $this->buscarBancoPorNombre(trim($data[22] ?? ''));

                    foreach ($ciasArray as $ciaId) {
                        $ciaId = trim($ciaId);
                        if (empty($ciaId))
                            continue;
                        $this->proveedorModel->asignarCia($proveedor_id, $ciaId);

                        if ($bancoId && (trim($data[23] ?? '') || trim($data[24] ?? ''))) {
                            $dbModel->crearCuenta([
                                'ProveedorId' => $proveedor_id,
                                'CiaId' => $ciaId,
                                'BancoId' => $bancoId,
                                'Cuenta' => trim($data[23] ?? ''),
                                'Clabe' => trim($data[24] ?? ''),
                                'Sucursal' => strtoupper(trim($data[25] ?? '')),
                                'Plaza' => strtoupper(trim($data[26] ?? '')),
                                'RutaCaratula' => null,
                                'EsPrincipal' => 1,
                                'Estatus' => 'APROBADO',
                                'Activo' => 1
                            ], usuarioId());
                        }
                    }
                }
                $db->commit();
            } catch (Exception $e) {
                // Verificar si hay transacción activa antes de rollback
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                $errores_count++;
                $lista_errores[] = "Fila $row (RFC: $rfc): " . $e->getMessage();
            }
        }
        fclose($handle);

        $mensaje = "Proceso finalizado. Nuevos: $importados. Actualizados: $actualizados.";
        if ($errores_count > 0) {
            $mensaje .= "<br><strong>Errores detectados ($errores_count):</strong><br>" . implode('<br>', $lista_errores);
            setFlash('warning', $mensaje); // Usar Warning para que resalte
        } else {
            setFlash('success', $mensaje);
        }

        redirect('proveedores');
    }

    private function buscarIdRegimen($clave)
    {
        if (empty($clave))
            return null;
        $db = (new Database())->getConnection();

        // Detectar columna
        $col = 'CodigoSAT';
        try {
            $db->query("SELECT Clave FROM Cat_Regimenes LIMIT 1");
            $col = 'Clave';
        } catch (Exception $e) {
        }

        $stmt = $db->prepare("SELECT Id FROM Cat_Regimenes WHERE {$col} LIKE ? OR Descripcion LIKE ? LIMIT 1");
        $stmt->execute(["$clave%", "%$clave%"]);
        $res = $stmt->fetch();
        return $res ? $res['Id'] : null;
    }

    private function buscarBancoPorNombre($dato)
    {
        if (empty($dato))
            return null;
        $dato = strtoupper(trim($dato));
        $db = (new Database())->getConnection();

        // Detectar si existe CLABE
        $hasClabe = false;
        try {
            $db->query("SELECT CLABE FROM Cat_Bancos LIMIT 1");
            $hasClabe = true;
        } catch (Exception $e) {
        }

        if ($hasClabe) {
            $stmt = $db->prepare("SELECT Id FROM Cat_Bancos WHERE CLABE = ? OR Nombre LIKE ? LIMIT 1");
            $stmt->execute([$dato, "%$dato%"]);
        } else {
            $stmt = $db->prepare("SELECT Id FROM Cat_Bancos WHERE Nombre LIKE ? LIMIT 1");
            $stmt->execute(["%$dato%"]);
        }

        $res = $stmt->fetch();
        return $res ? $res['Id'] : null;
    }

}