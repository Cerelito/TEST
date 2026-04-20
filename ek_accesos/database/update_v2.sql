-- =====================================================================
-- EK ACCESOS — Script de actualización v2
-- Ejecutar sobre una base de datos existente (controlekup).
-- Todas las operaciones usan IF NOT EXISTS / IF EXISTS para ser
-- idempotentes (puedes correrlo más de una vez sin duplicar datos).
-- =====================================================================

USE `controlekup`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ─────────────────────────────────────────────────────────────────────
-- 1. TABLA: empresas
--    Asegura columna 'activo' (ya incluida en schema v1).
-- ─────────────────────────────────────────────────────────────────────

ALTER TABLE `empresas`
    MODIFY COLUMN `activo` TINYINT(1) NOT NULL DEFAULT 1;

-- ─────────────────────────────────────────────────────────────────────
-- 2. TABLA: usuarios — nuevos campos ERP
-- ─────────────────────────────────────────────────────────────────────

ALTER TABLE `usuarios`
    ADD COLUMN IF NOT EXISTS `puesto`           VARCHAR(150)  NULL          AFTER `apellido`,
    ADD COLUMN IF NOT EXISTS `num_usuario_ek`   VARCHAR(20)   NULL          AFTER `puesto`
        COMMENT 'Número de usuario en ERP',
    ADD COLUMN IF NOT EXISTS `password_ek`      VARCHAR(255)  NULL          AFTER `num_usuario_ek`
        COMMENT 'Contraseña ERP cifrada (10 chars)',
    ADD COLUMN IF NOT EXISTS `pin_ek`           VARCHAR(255)  NULL          AFTER `password_ek`
        COMMENT 'PIN ERP cifrado (4 chars, solo admin)',
    ADD COLUMN IF NOT EXISTS `aprobado`         TINYINT(1)    NOT NULL DEFAULT 0 AFTER `rol`
        COMMENT '0 = pendiente aprobación',
    ADD COLUMN IF NOT EXISTS `token_aprobacion` VARCHAR(64)   NULL          AFTER `aprobado`,
    ADD COLUMN IF NOT EXISTS `debe_cambiar_pwd` TINYINT(1)    NOT NULL DEFAULT 0 AFTER `token_expira`,
    ADD COLUMN IF NOT EXISTS `intentos_fallidos`TINYINT       NOT NULL DEFAULT 0 AFTER `ultimo_acceso`,
    ADD COLUMN IF NOT EXISTS `bloqueado_hasta`  DATETIME      NULL          AFTER `intentos_fallidos`,
    ADD COLUMN IF NOT EXISTS `created_by`       INT UNSIGNED  NULL          AFTER `bloqueado_hasta`;

-- Índice único de email (puede que ya exista)
ALTER TABLE `usuarios`
    ADD UNIQUE KEY IF NOT EXISTS `idx_email` (`email`);

-- ─────────────────────────────────────────────────────────────────────
-- 3. TABLA: empleados — asegurar que tiene deleted_at y updated_at
-- ─────────────────────────────────────────────────────────────────────

ALTER TABLE `empleados`
    ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL
        DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        AFTER `created_at`,
    ADD COLUMN IF NOT EXISTS `deleted_at` TIMESTAMP NULL AFTER `updated_at`;

-- ─────────────────────────────────────────────────────────────────────
-- 4. TABLA: centros_costo — sin cambios estructurales en v2
--    Verificar que la tabla existe (creación segura).
-- ─────────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `centros_costo` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `empresa_id`  INT UNSIGNED NOT NULL,
    `codigo`      VARCHAR(50)  NOT NULL,
    `descripcion` VARCHAR(300) NOT NULL,
    `activo`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_empresa` (`empresa_id`),
    KEY `idx_codigo`  (`codigo`),
    FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────────────
-- 5. TABLA: empleado_cc — agregar tipo_insumo si no existe
-- ─────────────────────────────────────────────────────────────────────

ALTER TABLE `empleado_cc`
    ADD COLUMN IF NOT EXISTS `tipo_insumo` TINYINT UNSIGNED NOT NULL DEFAULT 0
        COMMENT '0=TODOS,1=MATERIALES,2=MANO DE OBRA,3=HERRAMIENTA Y EQUIPO,4=SUBCONTRATOS,5=INDIRECTOS,6=ADMINISTRATIVOS,7=TRAMITES Y PROYECTOS,8=BASICOS,9=COMERCIAL'
        AFTER `tipo`;

-- Recrear índice único que incluye tipo_insumo
-- (si no existe o si cambió)
ALTER TABLE `empleado_cc`
    DROP INDEX IF EXISTS `idx_emp_cc_tipo`,
    DROP INDEX IF EXISTS `idx_emp_cc_tipo_ins`;

ALTER TABLE `empleado_cc`
    ADD UNIQUE KEY `idx_emp_cc_tipo_ins` (`empleado_id`, `cc_id`, `tipo`, `tipo_insumo`);

-- ─────────────────────────────────────────────────────────────────────
-- 6. TABLA: programa_nivel — asegurar nivel y deleted_at
-- ─────────────────────────────────────────────────────────────────────

ALTER TABLE `programa_nivel`
    ADD COLUMN IF NOT EXISTS `nivel`      SMALLINT     NOT NULL DEFAULT 0 AFTER `id`,
    ADD COLUMN IF NOT EXISTS `deleted_at` TIMESTAMP    NULL     AFTER `updated_at`;

-- ─────────────────────────────────────────────────────────────────────
-- 7. TABLA: programa_nivel_permisos — crear si no existe
-- ─────────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `programa_nivel_permisos` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `programa_nivel_id`INT UNSIGNED NOT NULL,
    `modulo_erp_id`    INT UNSIGNED NOT NULL,
    `activo`           TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_pnivel_modulo` (`programa_nivel_id`, `modulo_erp_id`),
    FOREIGN KEY (`programa_nivel_id`) REFERENCES `programa_nivel`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`modulo_erp_id`)     REFERENCES `modulos_erp`(`id`)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────────────
-- 8. TABLA: empleado_programa_nivel — crear si no existe
-- ─────────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `empleado_programa_nivel` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `empleado_id`      INT UNSIGNED NOT NULL,
    `programa_nivel_id`INT UNSIGNED NOT NULL,
    `asignado_por`     INT UNSIGNED NULL,
    `created_at`       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_emp_pnivel` (`empleado_id`),
    FOREIGN KEY (`empleado_id`)       REFERENCES `empleados`(`id`)       ON DELETE CASCADE,
    FOREIGN KEY (`programa_nivel_id`) REFERENCES `programa_nivel`(`id`)  ON DELETE CASCADE,
    FOREIGN KEY (`asignado_por`)      REFERENCES `usuarios`(`id`)        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────────────
-- 9. TABLA: usuario_programa_nivel — crear si no existe
-- ─────────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `usuario_programa_nivel` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `usuario_id`       INT UNSIGNED NOT NULL,
    `programa_nivel_id`INT UNSIGNED NOT NULL,
    `asignado_por`     INT UNSIGNED NULL,
    `aprobado`         TINYINT(1)   NOT NULL DEFAULT 0,
    `created_at`       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_usr_pnivel` (`usuario_id`),
    FOREIGN KEY (`usuario_id`)        REFERENCES `usuarios`(`id`)       ON DELETE CASCADE,
    FOREIGN KEY (`programa_nivel_id`) REFERENCES `programa_nivel`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`asignado_por`)      REFERENCES `usuarios`(`id`)       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────────────
-- 10. TABLA: usuario_empleado — crear si no existe
-- ─────────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `usuario_empleado` (
    `usuario_id`  INT UNSIGNED NOT NULL,
    `empleado_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`usuario_id`),
    FOREIGN KEY (`usuario_id`)  REFERENCES `usuarios`(`id`)  ON DELETE CASCADE,
    FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────────────
-- 11. TABLA: requisitores / compradores — crear si no existen
-- ─────────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `requisitores` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `empleado_id` INT UNSIGNED NOT NULL,
    `activo`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_emp` (`empleado_id`),
    FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `compradores` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `empleado_id` INT UNSIGNED NOT NULL,
    `activo`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_emp` (`empleado_id`),
    FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────────────
-- 12. TABLA: logs_acceso y rate_limits — crear si no existen
-- ─────────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `logs_acceso` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `usuario_id`  INT UNSIGNED NULL,
    `evento`      VARCHAR(50)  NOT NULL,
    `descripcion` TEXT         NULL,
    `ip`          VARCHAR(45)  NOT NULL,
    `user_agent`  VARCHAR(500) NULL,
    `nivel`       ENUM('info','warning','error','critical') DEFAULT 'info',
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_usuario` (`usuario_id`),
    KEY `idx_evento`  (`evento`),
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rate_limits` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `identificador`VARCHAR(100) NOT NULL,
    `accion`       VARCHAR(50)  NOT NULL,
    `ip`           VARCHAR(45)  NOT NULL,
    `created_at`   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_id_accion` (`identificador`, `accion`),
    KEY `idx_created`   (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────────────
-- 13. ÍNDICES de rendimiento adicionales
-- ─────────────────────────────────────────────────────────────────────

-- Mejorar búsquedas de empleados por nombre
ALTER TABLE `empleados`
    ADD INDEX IF NOT EXISTS `idx_nombre` (`nombre`(100)),
    ADD INDEX IF NOT EXISTS `idx_activo` (`activo`, `deleted_at`);

-- Mejorar búsquedas de usuarios
ALTER TABLE `usuarios`
    ADD INDEX IF NOT EXISTS `idx_aprobado` (`aprobado`, `activo`);

-- ─────────────────────────────────────────────────────────────────────
-- 14. Datos iniciales de seguridad
--     Solo inserta si no existe el usuario admin.
-- ─────────────────────────────────────────────────────────────────────

INSERT IGNORE INTO `usuarios`
    (`username`, `email`, `password_hash`, `nombre`, `apellido`, `rol`, `activo`, `aprobado`)
VALUES
    ('ecruz', 'ecruz@urbanopark.com',
     '$2y$12$dSm6Ohs89uwW33qkWzYvSOtq/on9s1Us081W8grbZDnTq1Q8C8U2a',
     'Erick', 'Cruz', 'superadmin', 1, 1);

SET FOREIGN_KEY_CHECKS = 1;

-- ─────────────────────────────────────────────────────────────────────
-- Fin del script v2
-- ─────────────────────────────────────────────────────────────────────
SELECT CONCAT('✅ update_v2.sql ejecutado correctamente — ', NOW()) AS resultado;
