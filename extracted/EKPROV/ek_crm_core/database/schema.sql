-- =====================================================
-- SISTEMA EK PROVEEDORES - BASE DE DATOS COMPLETA v2.1
-- Con sistema de perfiles, permisos y gestión de proveedores
-- Compatible con Plantilla MVC v2.1
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- TABLAS DEL SISTEMA DE USUARIOS Y PERMISOS
-- =====================================================

-- -----------------------------------------------------
-- Tabla: perfiles (roles)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `perfiles` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(50) NOT NULL,
    `descripcion` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: permisos
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permisos` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `clave` VARCHAR(50) NOT NULL COMMENT 'ej: proveedores.crear',
    `nombre` VARCHAR(100) NOT NULL COMMENT 'ej: Crear proveedores',
    `modulo` VARCHAR(50) NOT NULL COMMENT 'ej: proveedores',
    `descripcion` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_clave` (`clave`),
    INDEX `idx_modulo` (`modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: perfil_permisos (pivote)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `perfil_permisos` (
    `perfil_id` INT UNSIGNED NOT NULL,
    `permiso_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`perfil_id`, `permiso_id`),
    FOREIGN KEY (`perfil_id`) REFERENCES `perfiles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permiso_id`) REFERENCES `permisos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: usuarios
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `perfil_id` INT UNSIGNED NULL COMMENT 'FK a perfiles',
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `nombre` VARCHAR(100) NOT NULL,
    `apellido` VARCHAR(100) NULL,
    `telefono` VARCHAR(20) NULL,
    `rol` ENUM('admin', 'supervisor', 'usuario') NOT NULL DEFAULT 'usuario' COMMENT 'Rol legacy, usar perfil_id',
    `activo` TINYINT(1) NOT NULL DEFAULT 1,
    `debe_cambiar_password` TINYINT(1) NOT NULL DEFAULT 0,
    `ultimo_acceso` DATETIME NULL,
    `intentos_fallidos` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `bloqueado_hasta` DATETIME NULL,
    `token_sesion` VARCHAR(64) NULL,
    `token_recuperacion` VARCHAR(64) NULL,
    `token_expira` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_username` (`username`),
    UNIQUE INDEX `idx_email` (`email`),
    UNIQUE INDEX `idx_token_sesion` (`token_sesion`),
    INDEX `idx_perfil_id` (`perfil_id`),
    INDEX `idx_activo` (`activo`),
    FOREIGN KEY (`perfil_id`) REFERENCES `perfiles`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS DE CATÁLOGOS
-- =====================================================

-- -----------------------------------------------------
-- Tabla: Cat_Bancos
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cat_Bancos` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `Nombre` VARCHAR(100) NOT NULL,
    `Activo` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: Cat_Cias (Compañías)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cat_Cias` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `Codigo` INT(11) NOT NULL,
    `Nombre` VARCHAR(100) NOT NULL,
    `Activo` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`Id`),
    UNIQUE INDEX `idx_codigo` (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: Cat_Regimenes (Regímenes Fiscales)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cat_Regimenes` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `CodigoSAT` VARCHAR(10) NULL,
    `Descripcion` VARCHAR(200) NOT NULL,
    `Activo` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: Cat_Estados
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cat_Estados` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `Nombre` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: Cat_Municipios
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cat_Municipios` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `EstadoId` INT(11) NOT NULL,
    `Nombre` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`Id`),
    INDEX `idx_estado_id` (`EstadoId`),
    FOREIGN KEY (`EstadoId`) REFERENCES `Cat_Estados`(`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS DE PROVEEDORES
-- =====================================================

-- -----------------------------------------------------
-- Tabla: Proveedores
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Proveedores` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `IdManual` VARCHAR(20) NULL COMMENT 'ID tipo EK-0001',
    `RFC` VARCHAR(13) NOT NULL,
    `TipoPersona` VARCHAR(10) NOT NULL COMMENT 'FISICA o MORAL',
    `RazonSocial` VARCHAR(255) NULL,
    `Nombre` VARCHAR(100) NULL,
    `ApellidoPaterno` VARCHAR(100) NULL,
    `ApellidoMaterno` VARCHAR(100) NULL,
    `RegimenFiscalId` INT(11) NULL,
    `CorreoPagosInterno` VARCHAR(150) NOT NULL,
    `CorreoProveedor` VARCHAR(150) NOT NULL,
    `Responsable` VARCHAR(150) NOT NULL,
    `LimiteCredito` DECIMAL(18,2) DEFAULT 0.00,
    `Calle` VARCHAR(200) NULL,
    `NumeroExterior` VARCHAR(20) NULL,
    `NumeroInterior` VARCHAR(20) NULL,
    `Colonia` VARCHAR(100) NULL,
    `CP` VARCHAR(10) NULL,
    `Estado` VARCHAR(100) NULL,
    `Municipio` VARCHAR(100) NULL,
    `EstadoId` INT(11) NULL,
    `MunicipioId` INT(11) NULL,
    `RutaConstancia` VARCHAR(500) NULL COMMENT 'Ruta archivo CSF',
    `RutaCaratula` VARCHAR(500) NULL COMMENT 'Ruta archivo bancario',
    `Estatus` VARCHAR(20) DEFAULT 'PENDIENTE' COMMENT 'PENDIENTE, APROBADO, RECHAZADO',
    `FechaRegistro` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `FechaModificacion` DATETIME NULL,
    `UsuarioCreadorId` INT UNSIGNED NULL,
    PRIMARY KEY (`Id`),
    UNIQUE INDEX `idx_rfc` (`RFC`),
    UNIQUE INDEX `idx_id_manual` (`IdManual`),
    INDEX `idx_regimen_fiscal` (`RegimenFiscalId`),
    INDEX `idx_estatus` (`Estatus`),
    INDEX `idx_usuario_creador` (`UsuarioCreadorId`),
    FOREIGN KEY (`RegimenFiscalId`) REFERENCES `Cat_Regimenes`(`Id`) ON DELETE SET NULL,
    FOREIGN KEY (`UsuarioCreadorId`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: Proveedor_Cias (Relación Proveedor-Compañía)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Proveedor_Cias` (
    `ProveedorId` INT(11) NOT NULL,
    `CiaId` INT(11) NOT NULL,
    PRIMARY KEY (`ProveedorId`, `CiaId`),
    FOREIGN KEY (`ProveedorId`) REFERENCES `Proveedores`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`CiaId`) REFERENCES `Cat_Cias`(`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: Proveedor_Cuentas (Matriz Bancaria por CIA)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Proveedor_Cuentas` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `ProveedorId` INT(11) NOT NULL,
    `CiaId` INT(11) NOT NULL,
    `BancoId` INT(11) NULL,
    `Cuenta` VARCHAR(30) NULL,
    `Clabe` VARCHAR(20) NULL,
    `Sucursal` VARCHAR(100) NULL,
    `Plaza` VARCHAR(100) NULL,
    PRIMARY KEY (`Id`),
    INDEX `idx_proveedor` (`ProveedorId`),
    INDEX `idx_cia` (`CiaId`),
    FOREIGN KEY (`ProveedorId`) REFERENCES `Proveedores`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`CiaId`) REFERENCES `Cat_Cias`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`BancoId`) REFERENCES `Cat_Bancos`(`Id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: Bitacora_Bancaria (Auditoría de Cambios)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Bitacora_Bancaria` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `ProveedorId` INT(11) NOT NULL,
    `CiaId` INT(11) NOT NULL,
    `BancoIdAntiguo` INT(11) NULL,
    `CuentaAntigua` VARCHAR(30) NULL,
    `ClabeAntigua` VARCHAR(20) NULL,
    `SucursalAntigua` VARCHAR(100) NULL,
    `PlazaAntigua` VARCHAR(100) NULL,
    `FechaCambio` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `UsuarioResponsable` INT UNSIGNED NULL,
    PRIMARY KEY (`Id`),
    INDEX `idx_proveedor` (`ProveedorId`),
    INDEX `idx_usuario` (`UsuarioResponsable`),
    FOREIGN KEY (`ProveedorId`) REFERENCES `Proveedores`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`UsuarioResponsable`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: Solicitudes_Cambios (Sistema de Aprobación)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Solicitudes_Cambios` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `ProveedorId` INT(11) NOT NULL,
    `SolicitanteId` INT UNSIGNED NOT NULL,
    `DatosJson` LONGTEXT NOT NULL COMMENT 'JSON con datos solicitados',
    `RutaConstanciaNueva` VARCHAR(500) NULL,
    `RutaCaratulaNueva` VARCHAR(500) NULL,
    `FechaSolicitud` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `Estatus` ENUM('PENDIENTE','APROBADO','RECHAZADO') DEFAULT 'PENDIENTE',
    `CiaObjetivo` INT(11) DEFAULT 0 COMMENT '0 = todas, N = solo esa CIA',
    `FechaRevision` DATETIME NULL,
    `RevisadoPor` INT UNSIGNED NULL,
    `MotivoRechazo` TEXT NULL,
    PRIMARY KEY (`Id`),
    INDEX `idx_proveedor` (`ProveedorId`),
    INDEX `idx_solicitante` (`SolicitanteId`),
    INDEX `idx_estatus` (`Estatus`),
    FOREIGN KEY (`ProveedorId`) REFERENCES `Proveedores`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`SolicitanteId`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`RevisadoPor`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: DatosBancarios (Sistema Inteligente de Cuentas Bancarias)
-- Con workflow Admin/Capturista y aprobación
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DatosBancarios` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `ProveedorId` INT(11) NOT NULL,
    `CiaId` INT(11) NOT NULL COMMENT 'Compañía asociada',
    `BancoId` INT(11) NULL COMMENT 'Banco de la cuenta',
    `Cuenta` VARCHAR(30) NULL COMMENT 'Número de cuenta',
    `Clabe` VARCHAR(18) NULL COMMENT 'CLABE interbancaria',
    `Sucursal` VARCHAR(100) NULL COMMENT 'Sucursal del banco',
    `Plaza` VARCHAR(100) NULL COMMENT 'Plaza bancaria',
    `RutaCaratula` VARCHAR(500) NULL COMMENT 'Ruta relativa del archivo PDF',
    `EsPrincipal` TINYINT(1) DEFAULT 0 COMMENT '1 = Cuenta principal del proveedor',
    `Estatus` ENUM('PENDIENTE','APROBADO','RECHAZADO') DEFAULT 'PENDIENTE',
    `Activo` TINYINT(1) DEFAULT 1 COMMENT '1 = Activa, 0 = Desactivada',
    `CreadoPor` INT UNSIGNED NULL COMMENT 'Usuario que creó la cuenta',
    `FechaCreacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `AprobadoPor` INT UNSIGNED NULL COMMENT 'Admin que aprobó',
    `FechaAprobacion` DATETIME NULL,
    `NotasAprobacion` TEXT NULL COMMENT 'Notas del admin al aprobar',
    `RechazadoPor` INT UNSIGNED NULL COMMENT 'Admin que rechazó',
    `FechaRechazo` DATETIME NULL,
    `MotivoRechazo` TEXT NULL,
    `DesactivadoPor` INT UNSIGNED NULL COMMENT 'Usuario que desactivó',
    `FechaDesactivacion` DATETIME NULL,
    `MotivoDesactivacion` TEXT NULL,
    `UltimaModificacion` DATETIME NULL,
    `ModificadoPor` INT UNSIGNED NULL,
    PRIMARY KEY (`Id`),
    INDEX `idx_proveedor` (`ProveedorId`),
    INDEX `idx_cia` (`CiaId`),
    INDEX `idx_banco` (`BancoId`),
    INDEX `idx_estatus` (`Estatus`),
    INDEX `idx_activo` (`Activo`),
    INDEX `idx_principal` (`EsPrincipal`),
    UNIQUE INDEX `idx_proveedor_cia_principal` (`ProveedorId`, `CiaId`, `EsPrincipal`),
    FOREIGN KEY (`ProveedorId`) REFERENCES `Proveedores`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`CiaId`) REFERENCES `Cat_Cias`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`BancoId`) REFERENCES `Cat_Bancos`(`Id`) ON DELETE SET NULL,
    FOREIGN KEY (`CreadoPor`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`AprobadoPor`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`RechazadoPor`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`DesactivadoPor`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`ModificadoPor`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: DatosBancarios_Historial (Auditoría de Cambios)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DatosBancarios_Historial` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `CuentaId` INT(11) NOT NULL COMMENT 'FK a DatosBancarios',
    `ProveedorId` INT(11) NOT NULL,
    `Accion` VARCHAR(50) NOT NULL COMMENT 'CREADO, APROBADO, RECHAZADO, MODIFICADO, DESACTIVADO, PRINCIPAL',
    `UsuarioId` INT UNSIGNED NULL COMMENT 'Usuario responsable',
    `DatosAnteriores` LONGTEXT NULL COMMENT 'JSON con datos previos',
    `DatosNuevos` LONGTEXT NULL COMMENT 'JSON con datos nuevos',
    `Notas` TEXT NULL COMMENT 'Notas u observaciones',
    `Fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `IP` VARCHAR(45) NULL COMMENT 'IP del usuario',
    PRIMARY KEY (`Id`),
    INDEX `idx_cuenta` (`CuentaId`),
    INDEX `idx_proveedor` (`ProveedorId`),
    INDEX `idx_accion` (`Accion`),
    INDEX `idx_fecha` (`Fecha`),
    FOREIGN KEY (`CuentaId`) REFERENCES `DatosBancarios`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`ProveedorId`) REFERENCES `Proveedores`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`UsuarioId`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: DatosBancarios_Adjuntos (Archivos Adicionales)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DatosBancarios_Adjuntos` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `CuentaId` INT(11) NOT NULL,
    `ProveedorId` INT(11) NOT NULL,
    `TipoDocumento` VARCHAR(50) NOT NULL COMMENT 'CARATULA, OTRO, etc',
    `NombreArchivo` VARCHAR(255) NOT NULL COMMENT 'Nombre original del archivo',
    `RutaArchivo` VARCHAR(500) NOT NULL COMMENT 'Ruta relativa del archivo',
    `TamanoBytes` INT(11) NULL COMMENT 'Tamaño del archivo en bytes',
    `SubidoPor` INT UNSIGNED NULL,
    `FechaSubida` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`Id`),
    INDEX `idx_cuenta` (`CuentaId`),
    INDEX `idx_proveedor` (`ProveedorId`),
    FOREIGN KEY (`CuentaId`) REFERENCES `DatosBancarios`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`ProveedorId`) REFERENCES `Proveedores`(`Id`) ON DELETE CASCADE,
    FOREIGN KEY (`SubidoPor`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS DE SEGURIDAD
-- =====================================================

-- -----------------------------------------------------
-- Tabla: rate_limits
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rate_limits` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `identificador` VARCHAR(100) NOT NULL,
    `accion` VARCHAR(50) NOT NULL,
    `ip` VARCHAR(45) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_identificador_accion` (`identificador`, `accion`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: logs_seguridad
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `logs_seguridad` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `evento` VARCHAR(50) NOT NULL,
    `descripcion` TEXT NULL,
    `usuario_id` INT UNSIGNED NULL,
    `ip` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(500) NULL,
    `url` VARCHAR(500) NULL,
    `metodo` VARCHAR(10) NULL,
    `nivel` ENUM('info', 'warning', 'error', 'critical') NOT NULL DEFAULT 'info',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_evento` (`evento`),
    INDEX `idx_usuario_id` (`usuario_id`),
    INDEX `idx_nivel` (`nivel`),
    INDEX `idx_created_at` (`created_at`),
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: configuraciones
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `configuraciones` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `clave` VARCHAR(50) NOT NULL,
    `valor` TEXT NULL,
    `tipo` ENUM('string', 'int', 'bool', 'json') DEFAULT 'string',
    `descripcion` VARCHAR(255) NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DATOS INICIALES
-- =====================================================

-- Perfiles base
INSERT INTO `perfiles` (`nombre`, `descripcion`) VALUES
('Administrador', 'Acceso total al sistema'),
('Supervisor', 'Puede revisar y aprobar solicitudes'),
('Capturista', 'Puede registrar y solicitar cambios de proveedores');

-- Permisos del sistema
INSERT INTO `permisos` (`clave`, `nombre`, `modulo`, `descripcion`) VALUES
-- Dashboard
('dashboard.ver', 'Ver dashboard', 'dashboard', 'Acceso al panel principal'),
-- Proveedores
('proveedores.ver', 'Ver proveedores', 'proveedores', 'Ver listado de proveedores'),
('proveedores.crear', 'Crear proveedores', 'proveedores', 'Dar de alta nuevos proveedores'),
('proveedores.editar', 'Editar proveedores', 'proveedores', 'Editar datos de proveedores (admin)'),
('proveedores.eliminar', 'Eliminar proveedores', 'proveedores', 'Eliminar proveedores'),
('proveedores.solicitar_cambio', 'Solicitar cambios', 'proveedores', 'Solicitar cambios en proveedores'),
('proveedores.asignar_id', 'Asignar ID EK', 'proveedores', 'Asignar ID oficial EK-XXXX'),
('proveedores.ver_archivos', 'Ver archivos', 'proveedores', 'Descargar PDFs de proveedores'),
-- Solicitudes
('solicitudes.ver', 'Ver solicitudes', 'solicitudes', 'Ver solicitudes de cambios'),
('solicitudes.aprobar', 'Aprobar solicitudes', 'solicitudes', 'Aprobar o rechazar solicitudes'),
('solicitudes.revisar', 'Revisar solicitudes', 'solicitudes', 'Revisar solicitudes pendientes'),
-- Usuarios
('usuarios.ver', 'Ver usuarios', 'usuarios', 'Ver listado de usuarios'),
('usuarios.crear', 'Crear usuarios', 'usuarios', 'Crear nuevos usuarios'),
('usuarios.editar', 'Editar usuarios', 'usuarios', 'Editar usuarios'),
('usuarios.eliminar', 'Eliminar usuarios', 'usuarios', 'Eliminar usuarios'),
('usuarios.activar', 'Activar/Desactivar usuarios', 'usuarios', 'Cambiar estado de usuarios'),
-- Perfiles
('perfiles.ver', 'Ver perfiles', 'perfiles', 'Ver perfiles del sistema'),
('perfiles.crear', 'Crear perfiles', 'perfiles', 'Crear nuevos perfiles'),
('perfiles.editar', 'Editar perfiles', 'perfiles', 'Editar perfiles'),
('perfiles.eliminar', 'Eliminar perfiles', 'perfiles', 'Eliminar perfiles'),
('perfiles.asignar_permisos', 'Asignar permisos', 'perfiles', 'Asignar permisos a perfiles'),
-- Catálogos
('catalogos.ver', 'Ver catálogos', 'catalogos', 'Ver catálogos del sistema'),
('catalogos.editar', 'Editar catálogos', 'catalogos', 'Editar bancos, CIAs, regímenes'),
-- Configuración
('configuracion.ver', 'Ver configuración', 'configuracion', 'Ver configuración del sistema'),
('configuracion.editar', 'Editar configuración', 'configuracion', 'Editar configuración'),
('configuracion.logs', 'Ver logs', 'configuracion', 'Ver logs de seguridad'),
-- Reportes
('reportes.ver', 'Ver reportes', 'reportes', 'Ver reportes del sistema'),
('reportes.exportar', 'Exportar reportes', 'reportes', 'Exportar reportes a Excel/PDF');

-- Asignar todos los permisos al perfil Administrador
INSERT INTO `perfil_permisos` (`perfil_id`, `permiso_id`)
SELECT 1, id FROM `permisos`;

-- Asignar permisos al perfil Supervisor
INSERT INTO `perfil_permisos` (`perfil_id`, `permiso_id`)
SELECT 2, id FROM `permisos` WHERE clave IN (
    'dashboard.ver',
    'proveedores.ver',
    'proveedores.ver_archivos',
    'solicitudes.ver',
    'solicitudes.aprobar',
    'solicitudes.revisar',
    'reportes.ver',
    'reportes.exportar'
);

-- Asignar permisos al perfil Capturista
INSERT INTO `perfil_permisos` (`perfil_id`, `permiso_id`)
SELECT 3, id FROM `permisos` WHERE clave IN (
    'dashboard.ver',
    'proveedores.ver',
    'proveedores.crear',
    'proveedores.solicitar_cambio',
    'solicitudes.ver'
);

-- Usuario administrador por defecto
-- Password: Admin123!@
INSERT INTO `usuarios` (`perfil_id`, `username`, `email`, `password_hash`, `nombre`, `rol`, `debe_cambiar_password`) VALUES
(1, 'admin', 'admin@ekproveedores.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/X4D6AroMZRMFD4D9i', 'Administrador', 'admin', 1);

-- Catálogo de Bancos
INSERT INTO `Cat_Bancos` (`Nombre`, `Activo`) VALUES
('BBVA MEXICO', 1),
('BANAMEX', 1),
('SANTANDER', 1),
('HSBC', 1),
('BANORTE', 1),
('SCOTIABANK', 1),
('INBURSA', 1),
('AZTECA', 1);

-- Catálogo de Compañías
INSERT INTO `Cat_Cias` (`Codigo`, `Nombre`, `Activo`) VALUES
(1, 'CIA 1', 1),
(2, 'CIA 2', 1),
(3, 'CIA 3', 1),
(4, 'CIA 4', 1),
(5, 'CIA 5', 1),
(6, 'CIA 6', 1),
(7, 'CIA 7', 1),
(8, 'CIA 8', 1),
(9, 'CIA 9', 1),
(10, 'CIA 10', 1),
(11, 'CIA 11', 1),
(12, 'CIA 12', 1),
(13, 'CIA 13', 1),
(14, 'CIA 14', 1),
(15, 'CIA 15', 1),
(16, 'CIA 16', 1),
(17, 'CIA 17', 1),
(70, 'CIA 70 (ESPECIAL)', 1),
(81, 'CIA 81 (ESPECIAL)', 1);

-- Catálogo de Regímenes Fiscales
INSERT INTO `Cat_Regimenes` (`CodigoSAT`, `Descripcion`, `Activo`) VALUES
('601', 'PERSONA FÍSICA', 1),
('603', 'S.A. DE C.V.', 1),
('605', 'S. DE R.L. DE C.V.', 1),
('607', 'S.A.P.I. DE C.V.', 1),
('608', 'S.A.S.', 1),
('610', 'S.C.', 1),
('620', 'A.C.', 1),
('621', 'S.N.C.', 1),
('625', 'RESICO (PF)', 1),
('626', 'RESICO (PM)', 1);

-- Configuraciones iniciales
INSERT INTO `configuraciones` (`clave`, `valor`, `tipo`, `descripcion`) VALUES
('app_nombre', 'EK Proveedores', 'string', 'Nombre de la aplicación'),
('app_logo', 'logo.png', 'string', 'Logo de la aplicación'),
('app_favicon', 'favicon.ico', 'string', 'Favicon'),
('tema_default', 'light', 'string', 'Tema por defecto (light/dark)'),
('session_timeout', '30', 'int', 'Timeout de sesión en minutos'),
('max_intentos_login', '5', 'int', 'Máximo de intentos de login'),
('bloqueo_minutos', '30', 'int', 'Minutos de bloqueo por intentos fallidos'),
('email_admin', 'admin@ekproveedores.com', 'string', 'Email del administrador'),
('notificar_nuevos_proveedores', '1', 'bool', 'Notificar al admin nuevos proveedores'),
('notificar_solicitudes', '1', 'bool', 'Notificar al admin nuevas solicitudes');

SET FOREIGN_KEY_CHECKS = 1;
