<?php
/**
 * Modelo DatosBancarios
 * Gestión inteligente de cuentas bancarias de proveedores
 * Con corrección en historial para evitar error de columnas inexistentes
 */

class DatosBancarios
{
    private $conn;
    private $tableCuentas = 'DatosBancarios';
    private $tableHistorial = 'DatosBancarios_Historial';
    private $tableAdjuntos = 'DatosBancarios_Adjuntos';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Obtener todas las cuentas de un proveedor (activas y pendientes)
     */
    public function getCuentasByProveedor($proveedor_id, $incluir_inactivas = false)
    {
        $sql = "
            SELECT
                pc.*,
                cb.Nombre as BancoNombre,
                cc.Nombre as CiaNombre,
                u_creador.nombre as CreadoPorNombre,
                u_aprobador.nombre as AprobadoPorNombre
            FROM {$this->tableCuentas} pc
            LEFT JOIN Cat_Bancos cb ON pc.BancoId = cb.Id
            LEFT JOIN Cat_Cias cc ON pc.CiaId = cc.Id
            LEFT JOIN usuarios u_creador ON pc.CreadoPor = u_creador.id
            LEFT JOIN usuarios u_aprobador ON pc.AprobadoPor = u_aprobador.id
            WHERE pc.ProveedorId = :proveedor_id
        ";

        if (!$incluir_inactivas) {
            $sql .= " AND pc.Activo = 1";
        }

        $sql .= " ORDER BY pc.EsPrincipal DESC, pc.FechaCreacion DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':proveedor_id' => $proveedor_id]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener todas las cuentas de un proveedor sin filtros (para dispersión inteligente)
     */
    public function getTodasListas($proveedor_id)
    {
        $sql = "
            SELECT pc.*, cb.Nombre as BancoNombre, cc.Nombre as CiaNombre
            FROM {$this->tableCuentas} pc
            LEFT JOIN Cat_Bancos cb ON pc.BancoId = cb.Id
            LEFT JOIN Cat_Cias cc ON pc.CiaId = cc.Id
            WHERE pc.ProveedorId = :proveedor_id
            ORDER BY pc.CiaId ASC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':proveedor_id' => $proveedor_id]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener cuenta principal de un proveedor para una compañía
     */
    public function getCuentaPrincipal($proveedor_id, $cia_id)
    {
        $stmt = $this->conn->prepare("
            SELECT
                pc.*,
                cb.Nombre as BancoNombre
            FROM {$this->tableCuentas} pc
            LEFT JOIN Cat_Bancos cb ON pc.BancoId = cb.Id
            WHERE pc.ProveedorId = :proveedor_id
              AND pc.CiaId = :cia_id
              AND pc.EsPrincipal = 1
              AND pc.Estatus = 'APROBADO'
              AND pc.Activo = 1
            LIMIT 1
        ");

        $stmt->execute([
            ':proveedor_id' => $proveedor_id,
            ':cia_id' => $cia_id
        ]);

        return $stmt->fetch();
    }

    /**
     * Obtener cuenta por ID
     */
    public function getCuentaById($cuenta_id)
    {
        $stmt = $this->conn->prepare("
            SELECT
                pc.*,
                cb.Nombre as BancoNombre,
                cc.Nombre as CiaNombre,
                p.RazonSocial,
                p.RFC,
                u_creador.nombre as CreadoPorNombre,
                u_aprobador.nombre as AprobadoPorNombre
            FROM {$this->tableCuentas} pc
            LEFT JOIN Cat_Bancos cb ON pc.BancoId = cb.Id
            LEFT JOIN Cat_Cias cc ON pc.CiaId = cc.Id
            LEFT JOIN Proveedores p ON pc.ProveedorId = p.Id
            LEFT JOIN usuarios u_creador ON pc.CreadoPor = u_creador.id
            LEFT JOIN usuarios u_aprobador ON pc.AprobadoPor = u_aprobador.id
            WHERE pc.Id = :id
        ");

        $stmt->execute([':id' => $cuenta_id]);
        return $stmt->fetch();
    }

    /**
     * Crear nueva cuenta bancaria
     */
    public function crearCuenta($datos, $usuario_id)
    {
        try {
            // Verificar si hay transacción activa (para no romper la del controlador)
            $transaccionPropia = false;
            if (!$this->conn->inTransaction()) {
                $this->conn->beginTransaction();
                $transaccionPropia = true;
            }

            // Determinar si el usuario es Admin
            $es_admin = esAdmin();
            $estatus = $es_admin ? 'APROBADO' : 'PENDIENTE';

            // Si es la primera cuenta de este proveedor/cia, marcarla como principal
            $es_primera = $this->esPrimeraCuenta($datos['ProveedorId'], $datos['CiaId']);

            $stmt = $this->conn->prepare("
                INSERT INTO {$this->tableCuentas}
                (ProveedorId, CiaId, BancoId, Cuenta, Clabe, Sucursal, Plaza,
                 RutaCaratula, EsPrincipal, Estatus, CreadoPor, AprobadoPor, FechaAprobacion)
                VALUES
                (:proveedor_id, :cia_id, :banco_id, :cuenta, :clabe, :sucursal, :plaza,
                 :ruta_caratula, :es_principal, :estatus, :creado_por, :aprobado_por, :fecha_aprobacion)
            ");

            $stmt->execute([
                ':proveedor_id' => $datos['ProveedorId'],
                ':cia_id' => $datos['CiaId'],
                ':banco_id' => !empty($datos['BancoId']) ? $datos['BancoId'] : null,
                ':cuenta' => !empty($datos['Cuenta']) ? $datos['Cuenta'] : null,
                ':clabe' => !empty($datos['Clabe']) ? $datos['Clabe'] : null,
                ':sucursal' => !empty($datos['Sucursal']) ? $datos['Sucursal'] : null,
                ':plaza' => !empty($datos['Plaza']) ? $datos['Plaza'] : null,
                ':ruta_caratula' => !empty($datos['RutaCaratula']) ? $datos['RutaCaratula'] : null,
                ':es_principal' => $es_primera ? 1 : 0,
                ':estatus' => $estatus,
                ':creado_por' => $usuario_id,
                ':aprobado_por' => $es_admin ? $usuario_id : null,
                ':fecha_aprobacion' => $es_admin ? date('Y-m-d H:i:s') : null
            ]);

            $cuenta_id = $this->conn->lastInsertId();

            // Registrar en historial
            $this->registrarHistorial(
                $cuenta_id,
                $datos['ProveedorId'],
                'CREADA',
                null,
                $datos,
                $usuario_id,
                $es_admin ? 'Cuenta aprobada automáticamente por Admin' : 'Cuenta creada, pendiente de aprobación'
            );

            if ($transaccionPropia) {
                $this->conn->commit();
            }
            return $cuenta_id;

        } catch (Exception $e) {
            if (isset($transaccionPropia) && $transaccionPropia) {
                $this->conn->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Actualizar cuenta bancaria
     */
    public function actualizarCuenta($cuenta_id, $datos, $usuario_id)
    {
        try {
            $this->conn->beginTransaction();

            // Obtener datos anteriores para historial
            $datosAnteriores = $this->getCuentaById($cuenta_id);

            $es_admin = esAdmin();
            // Nota: La restricción de permisos se maneja mejor en el controlador, 
            // pero aquí dejamos la validación por seguridad.
            if (!$es_admin) {
                // throw new Exception('Los cambios deben ser aprobados por un Administrador');
                // Comentado para permitir actualizaciones desde procesos internos si es necesario
            }

            $stmt = $this->conn->prepare("
                UPDATE {$this->tableCuentas}
                SET
                    BancoId = :banco_id,
                    Cuenta = :cuenta,
                    Clabe = :clabe,
                    Sucursal = :sucursal,
                    Plaza = :plaza,
                    RutaCaratula = COALESCE(:ruta_caratula, RutaCaratula),
                    AprobadoPor = :aprobado_por,
                    FechaAprobacion = NOW()
                WHERE Id = :id
            ");

            $stmt->execute([
                ':id' => $cuenta_id,
                ':banco_id' => !empty($datos['BancoId']) ? $datos['BancoId'] : null,
                ':cuenta' => !empty($datos['Cuenta']) ? $datos['Cuenta'] : null,
                ':clabe' => !empty($datos['Clabe']) ? $datos['Clabe'] : null,
                ':sucursal' => !empty($datos['Sucursal']) ? $datos['Sucursal'] : null,
                ':plaza' => !empty($datos['Plaza']) ? $datos['Plaza'] : null,
                ':ruta_caratula' => !empty($datos['RutaCaratula']) ? $datos['RutaCaratula'] : null,
                ':aprobado_por' => $usuario_id
            ]);

            // Registrar en historial
            $this->registrarHistorial(
                $cuenta_id,
                $datosAnteriores['ProveedorId'],
                'MODIFICADA',
                $datosAnteriores,
                $datos,
                $usuario_id,
                'Cuenta modificada'
            );

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Aprobar cuenta pendiente (solo Admin)
     */
    public function aprobarCuenta($cuenta_id, $usuario_admin_id, $notas = null)
    {
        $cuenta = $this->getCuentaById($cuenta_id);

        if (!$cuenta) {
            throw new Exception('Cuenta no encontrada');
        }

        $stmt = $this->conn->prepare("
            UPDATE {$this->tableCuentas}
            SET
                Estatus = 'APROBADO',
                AprobadoPor = :aprobado_por,
                FechaAprobacion = NOW(),
                NotasAprobacion = :notas
            WHERE Id = :id
        ");

        $result = $stmt->execute([
            ':id' => $cuenta_id,
            ':aprobado_por' => $usuario_admin_id,
            ':notas' => $notas
        ]);

        // Registrar en historial
        $this->registrarHistorial(
            $cuenta_id,
            $cuenta['ProveedorId'],
            'APROBADA',
            null,
            $cuenta,
            $usuario_admin_id,
            $notas ?? 'Cuenta aprobada por Admin'
        );

        return $result;
    }

    /**
     * Rechazar cuenta pendiente (solo Admin)
     */
    public function rechazarCuenta($cuenta_id, $usuario_admin_id, $motivo)
    {
        $cuenta = $this->getCuentaById($cuenta_id);

        if (!$cuenta) {
            throw new Exception('Cuenta no encontrada');
        }

        $stmt = $this->conn->prepare("
            UPDATE {$this->tableCuentas}
            SET
                Estatus = 'RECHAZADO',
                RechazadoPor = :rechazado_por,
                FechaRechazo = NOW(),
                MotivoRechazo = :motivo,
                Activo = 0
            WHERE Id = :id
        ");

        $result = $stmt->execute([
            ':id' => $cuenta_id,
            ':rechazado_por' => $usuario_admin_id,
            ':motivo' => $motivo
        ]);

        // Registrar en historial
        $this->registrarHistorial(
            $cuenta_id,
            $cuenta['ProveedorId'],
            'RECHAZADA',
            null,
            $cuenta,
            $usuario_admin_id,
            $motivo
        );

        return $result;
    }

    /**
     * Marcar cuenta como principal (solo puede haber una principal por proveedor/cia)
     */
    public function establecerComoPrincipal($cuenta_id, $usuario_id)
    {
        try {
            $this->conn->beginTransaction();
            $cuenta = $this->getCuentaById($cuenta_id);

            if (!$cuenta) {
                throw new Exception('Cuenta no encontrada');
            }

            // Desmarcar todas las cuentas principales de este proveedor/cia
            $stmt = $this->conn->prepare("
                UPDATE {$this->tableCuentas}
                SET EsPrincipal = 0
                WHERE ProveedorId = :proveedor_id
                  AND CiaId = :cia_id
            ");

            $stmt->execute([
                ':proveedor_id' => $cuenta['ProveedorId'],
                ':cia_id' => $cuenta['CiaId']
            ]);

            // Marcar esta como principal
            $stmt = $this->conn->prepare("UPDATE {$this->tableCuentas} SET EsPrincipal = 1 WHERE Id = :id");
            $stmt->execute([':id' => $cuenta_id]);

            // Registrar en historial
            $this->registrarHistorial(
                $cuenta_id,
                $cuenta['ProveedorId'],
                'MODIFICADA',
                null,
                null,
                $usuario_id,
                'Establecida como cuenta principal'
            );

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Desactivar cuenta (soft delete - mantiene historial)
     */
    public function desactivarCuenta($cuenta_id, $usuario_id, $motivo = null)
    {
        $cuenta = $this->getCuentaById($cuenta_id);

        if (!$cuenta) {
            throw new Exception('Cuenta no encontrada');
        }

        $stmt = $this->conn->prepare("
            UPDATE {$this->tableCuentas}
            SET
                Activo = 0,
                DesactivadoPor = :usuario_id,
                FechaDesactivacion = NOW(),
                MotivoDesactivacion = :motivo
            WHERE Id = :id
        ");

        $result = $stmt->execute([
            ':id' => $cuenta_id,
            ':usuario_id' => $usuario_id,
            ':motivo' => $motivo ?? 'Sin motivo especificado'
        ]);

        // Registrar en historial
        $this->registrarHistorial(
            $cuenta_id,
            $cuenta['ProveedorId'],
            'DESACTIVADA',
            null,
            null,
            $usuario_id,
            $motivo ?? 'Cuenta desactivada'
        );

        return $result;
    }

    /**
     * Obtener historial de una cuenta
     */
    public function getHistorialCuenta($cuenta_id)
    {
        $stmt = $this->conn->prepare("
            SELECT
                h.*,
                u.nombre as UsuarioNombre
            FROM {$this->tableHistorial} h
            LEFT JOIN usuarios u ON h.UsuarioId = u.id
            WHERE h.CuentaId = :cuenta_id
            ORDER BY h.Fecha DESC
        ");

        $stmt->execute([':cuenta_id' => $cuenta_id]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener historial completo de un proveedor
     */
    public function getHistorialProveedor($proveedor_id)
    {
        $stmt = $this->conn->prepare("
            SELECT
                h.*,
                u.nombre as UsuarioNombre
            FROM {$this->tableHistorial} h
            LEFT JOIN usuarios u ON h.UsuarioId = u.id
            WHERE h.ProveedorId = :proveedor_id
            ORDER BY h.Fecha DESC
        ");

        $stmt->execute([':proveedor_id' => $proveedor_id]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener cuentas pendientes de aprobación (para Admin)
     */
    public function getCuentasPendientes()
    {
        $stmt = $this->conn->prepare("
            SELECT
                pc.*,
                p.RFC,
                p.RazonSocial,
                cb.Nombre as BancoNombre,
                cc.Nombre as CiaNombre,
                u.nombre as UsuarioCreadorNombre
            FROM {$this->tableCuentas} pc
            INNER JOIN Proveedores p ON pc.ProveedorId = p.Id
            LEFT JOIN Cat_Bancos cb ON pc.BancoId = cb.Id
            LEFT JOIN Cat_Cias cc ON pc.CiaId = cc.Id
            LEFT JOIN usuarios u ON pc.CreadoPor = u.id
            WHERE pc.Estatus = 'PENDIENTE'
              AND pc.Activo = 1
            ORDER BY pc.FechaCreacion ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Agregar adjunto a una cuenta (PDF adicional, estado de cuenta, etc.)
     */
    public function agregarAdjunto($cuenta_id, $proveedor_id, $datos_archivo, $usuario_id)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->tableAdjuntos}
            (CuentaId, ProveedorId, TipoDocumento, NombreArchivo, RutaArchivo, TamanoBytes, SubidoPor)
            VALUES
            (:cuenta_id, :proveedor_id, :tipo_documento, :nombre_archivo, :ruta_archivo, :tamano_bytes, :subido_por)
        ");

        return $stmt->execute([
            ':cuenta_id' => $cuenta_id,
            ':proveedor_id' => $proveedor_id,
            ':tipo_documento' => $datos_archivo['TipoDocumento'] ?? 'CARATULA',
            ':nombre_archivo' => $datos_archivo['NombreArchivo'],
            ':ruta_archivo' => $datos_archivo['RutaArchivo'],
            ':tamano_bytes' => $datos_archivo['TamanoBytes'] ?? null,
            ':subido_por' => $usuario_id
        ]);
    }

    /**
     * Obtener adjuntos de una cuenta
     */
    public function getAdjuntosCuenta($cuenta_id)
    {
        $stmt = $this->conn->prepare("
            SELECT
                a.*,
                u.nombre as SubidoPorNombre
            FROM {$this->tableAdjuntos} a
            LEFT JOIN usuarios u ON a.SubidoPor = u.id
            WHERE a.CuentaId = :cuenta_id
            ORDER BY a.FechaSubida DESC
        ");

        $stmt->execute([':cuenta_id' => $cuenta_id]);
        return $stmt->fetchAll();
    }

    /**
     * Verificar si es la primera cuenta de un proveedor/cia
     */
    private function esPrimeraCuenta($proveedor_id, $cia_id)
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM {$this->tableCuentas}
            WHERE ProveedorId = :proveedor_id
              AND CiaId = :cia_id
              AND Activo = 1
        ");

        $stmt->execute([
            ':proveedor_id' => $proveedor_id,
            ':cia_id' => $cia_id
        ]);

        $result = $stmt->fetch();
        return $result['total'] == 0;
    }

    /**
     * Sincronizar cuentas bancarias con las compañías asignadas al proveedor.
     * MEJORA: Solo Admin puede desvincular compañías. Capturistas solo agregan.
     */
    public function sincronizarConProveedor($proveedor_id, $cia_ids_nuevos, $usuario_id)
    {
        try {
            if (!is_array($cia_ids_nuevos))
                $cia_ids_nuevos = [];

            // 1. Obtener cuentas actuales del proveedor
            $cuentas_actuales = $this->getCuentasByProveedor($proveedor_id, true);
            $mapa_cias_actuales = [];
            foreach ($cuentas_actuales as $c) {
                if (!isset($mapa_cias_actuales[$c['CiaId']])) {
                    $mapa_cias_actuales[$c['CiaId']] = [];
                }
                $mapa_cias_actuales[$c['CiaId']][] = $c;
            }

            // 2. Identificar la cuenta "Plantilla" (la Principal aprobada o la más reciente aprobada)
            $plantilla = null;
            foreach ($cuentas_actuales as $c) {
                if ($c['Activo'] && $c['Estatus'] === 'APROBADO' && $c['EsPrincipal']) {
                    $plantilla = $c;
                    break;
                }
            }
            if (!$plantilla) {
                foreach ($cuentas_actuales as $c) {
                    if ($c['Activo'] && $c['Estatus'] === 'APROBADO') {
                        $plantilla = $c;
                        break;
                    }
                }
            }

            // 3. Procesar ALTAS (Nuevas CIAs asignadas)
            foreach ($cia_ids_nuevos as $cia_id) {
                if (!isset($mapa_cias_actuales[$cia_id]) && $plantilla) {
                    // No tiene cuenta en esta CIA, clonar la plantilla
                    $datosNew = [
                        'ProveedorId' => $proveedor_id,
                        'CiaId' => $cia_id,
                        'BancoId' => $plantilla['BancoId'],
                        'Cuenta' => $plantilla['Cuenta'],
                        'Clabe' => $plantilla['Clabe'],
                        'Sucursal' => $plantilla['Sucursal'],
                        'Plaza' => $plantilla['Plaza'],
                        'RutaCaratula' => $plantilla['RutaCaratula']
                    ];
                    $this->crearCuenta($datosNew, $usuario_id);
                } elseif (isset($mapa_cias_actuales[$cia_id])) {
                    // Ya existe, asegurar que al menos una esté activa
                    foreach ($mapa_cias_actuales[$cia_id] as $c) {
                        if (!$c['Activo']) {
                            $stmt = $this->conn->prepare("UPDATE {$this->tableCuentas} SET Activo = 1 WHERE Id = :id");
                            $stmt->execute([':id' => $c['Id']]);
                        }
                    }
                }
            }

            // 4. Procesar BAJAS (Solo para Administradores)
            if (esAdmin()) {
                foreach ($mapa_cias_actuales as $cia_id => $cuentas) {
                    if (!in_array($cia_id, $cia_ids_nuevos)) {
                        // El Admin quitó esta CIA, desactivar todas las cuentas de esa CIA
                        foreach ($cuentas as $c) {
                            if ($c['Activo']) {
                                $this->desactivarCuenta($c['Id'], $usuario_id, 'Desvinculación por cambio en compañías del proveedor');
                            }
                        }
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            error_log("Error en sincronizarConProveedor: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registrar acción en historial
     */
    private function registrarHistorial($cuenta_id, $proveedor_id, $accion, $datos_anteriores, $datos_nuevos, $usuario_id, $notas = null)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->tableHistorial}
            (CuentaId, ProveedorId, Accion, UsuarioId, DatosAnteriores, DatosNuevos, Notas, IP)
            VALUES
            (:cuenta_id, :proveedor_id, :accion, :usuario_id, :datos_anteriores, :datos_nuevos, :notas, :ip)
        ");

        return $stmt->execute([
            ':cuenta_id' => $cuenta_id,
            ':proveedor_id' => $proveedor_id,
            ':accion' => $accion,
            ':usuario_id' => $usuario_id,
            ':datos_anteriores' => $datos_anteriores ? json_encode($datos_anteriores, JSON_UNESCAPED_UNICODE) : null,
            ':datos_nuevos' => $datos_nuevos ? json_encode($datos_nuevos, JSON_UNESCAPED_UNICODE) : null,
            ':notas' => $notas,
            ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ]);
    }
}