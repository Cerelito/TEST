-- =====================================================================
-- EK ACCESOS - BASE DE DATOS COMPLETA v1.0
-- Sistema de Gestión de Empleados, Permisos ERP y Accesos
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `ek_accesos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ek_accesos`;

-- =====================================================================
-- CATÁLOGOS BASE (migrados de erickedu_ekpermisos)
-- =====================================================================

CREATE TABLE IF NOT EXISTS `empresas` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre`      VARCHAR(200) NOT NULL,
    `codigo`      VARCHAR(20)  NULL,
    `activo`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `empresas` (`nombre`, `codigo`) VALUES
('Empresa 1 - CONDOR 31',                '1'),
('Empresa 2 - REVOLUCIÓN 1221',          '2'),
('Empresa 4 - SAM Y MOI',               '4'),
('Empresa 8 - LAS FLORES',              '8'),
('Empresa 13 - PLATINUM ROYALPURPLE',   '13'),
('Empresa 14 - BALM 2273',             '14'),
('Empresa 15 - FIDEICOMISO 1473',       '15'),
('Empresa 16 - LAS AGUILAS 2273',       '16'),
('Empresa 17 - PERSONAS FISICAS',       '17'),
('Empresa 18 - GIRASOLES 2020',         '18'),
('Empresa 70 - LAS FLORES / REVOLUCIÓN','70');

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

-- =====================================================================
-- MÓDULOS ERP Y ÁRBOL DE PERMISOS
-- =====================================================================

CREATE TABLE IF NOT EXISTS `modulos_erp` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `parent_id`   INT UNSIGNED NULL COMMENT 'NULL = módulo raíz',
    `clave`       VARCHAR(100) NOT NULL COMMENT 'ej: compras.maestros.paises',
    `nombre`      VARCHAR(200) NOT NULL,
    `orden`       SMALLINT     NOT NULL DEFAULT 0,
    `es_separador`TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '1 = línea separadora',
    `activo`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_clave` (`clave`),
    KEY `idx_parent` (`parent_id`),
    FOREIGN KEY (`parent_id`) REFERENCES `modulos_erp`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Árbol completo del módulo Compras (extraído de las imágenes)
INSERT INTO `modulos_erp` (`parent_id`, `clave`, `nombre`, `orden`) VALUES
-- Módulo raíz
(NULL, 'compras',                                      'Compras',                                          1),

-- Maestros
(1, 'compras.maestros',                                'Maestros',                                         1),
(2, 'compras.maestros.datos_compania',                 'Datos Compañía',                                   1),
(2, 'compras.maestros.paises',                         'Países',                                           2),
(2, 'compras.maestros.estados',                        'Estados',                                          3),
(2, 'compras.maestros.ciudades',                       'Ciudades',                                         4),
(2, 'compras.maestros.identificadores',                'Identificadores',                                  5),
(2, 'compras.maestros.centros_costo',                  'Centros de Costo',                                 6),
(2, 'compras.maestros.matriz_kontrol',                 'Matriz de Kontrol por Proyecto',                   7),
(9, 'compras.maestros.matriz_kontrol.val_compras',     'Validaciones por Compras',                         1),
(9, 'compras.maestros.matriz_kontrol.val_consumo',     'Validaciones por Consumo',                         2),
(9, 'compras.maestros.matriz_kontrol.val_cierre',      'Validaciones de Cierre de Obra',                   3),
(2, 'compras.maestros.puestos',                        'Puestos',                                          8),
(2, 'compras.maestros.empleados',                      'Empleados',                                        9),
(2, 'compras.maestros.almacenes',                      'Almacenes',                                       10),
(2, 'compras.maestros.area',                           'Area',                                            11),
(2, 'compras.maestros.area_cuenta',                    'Area / Cuenta',                                   12),
(2, 'compras.maestros.rel_area_cuenta',                'Relación Area-Cuenta con Frente-Partida',         13),
(2, 'compras.maestros.labs',                           'LABS',                                            14),
(2, 'compras.maestros.tipos_movimientos',              'Tipos de Movimientos',                            15),
(2, 'compras.maestros.proveedores',                    'Proveedores',                                     16),
(2, 'compras.maestros.monedas',                        'Monedas',                                         17),
(2, 'compras.maestros.tipo_cambio',                    'Tipo de Cambio',                                  18),
(2, 'compras.maestros.embarque',                       'Embarque',                                        19),
(2, 'compras.maestros.abc_regiones',                   'A,B,C a Regiones',                                20),
(2, 'compras.maestros.regionalizacion',                'Regionalización de Precios',                      21),
(25, 'compras.maestros.regionalizacion.prov_insumo',   'Por Proveedor-Insumo',                             1),
(25, 'compras.maestros.regionalizacion.region_pu',     'Por Región-Insumo PU',                             2),
(25, 'compras.maestros.regionalizacion.imp_reg_pu',    'Importación de Precios Regionalizados PU',         3),
(25, 'compras.maestros.regionalizacion.imp_prov_reg',  'Importación de Precios Por Proveedor-Región',      4),
(2, 'compras.maestros.retenciones',                    'Retenciones',                                     22),
(2, 'compras.maestros.lugar_compra',                   'Lugar de Compra',                                 23),
(2, 'compras.maestros.dias_compromiso',                'Días Compromiso',                                 24),
(2, 'compras.maestros.val_ppto_area',                  'Validacion de presupuesto por Área-Cuenta',       25),
(2, 'compras.maestros.copiar_rel_cc',                  'Copiar Relación C.C. Area-Cuenta',                26),
(2, 'compras.maestros.config_semanas',                 'Configuración de Semanas',                        27),
(2, 'compras.maestros.config_rep_ctrl',                'Configuración Reporte Control Presupuestal',      28),

-- Presupuesto
(1, 'compras.presupuesto',                             'Presupuesto',                                      2),
(35, 'compras.presupuesto.asig_requisitores',          'Asignación de Requisitores',                       1),
(35, 'compras.presupuesto.asig_compradores',           'Asignación de Compradores',                        2),
(35, 'compras.presupuesto.asig_insumos',               'Asignacion de Insumos',                            3),
(35, 'compras.presupuesto.tipos_insumo',               'Tipos de Insumo',                                  4),
(35, 'compras.presupuesto.grupos_insumo',              'Grupos de Insumo',                                 5),
(35, 'compras.presupuesto.insumos',                    'Insumos',                                          6),
(35, 'compras.presupuesto.rel_insumo',                 'Relación Insumo Genérico vs Detalle',              7),
(35, 'compras.presupuesto.explosion',                  'Explosión de Materiales',                          8),
(35, 'compras.presupuesto.config_insumos',             'Configuración Insumos',                            9),
(44, 'compras.presupuesto.config_insumos.iva',         'Insumos IVA',                                      1),
(44, 'compras.presupuesto.config_insumos.nomina',      'Insumos Nómina',                                   2),
(35, 'compras.presupuesto.aditivas',                   'Aditivas y Deductivas',                           10),
(35, 'compras.presupuesto.aditivas_cant',              'Aditivas y Deductivas Cantidad',                  11),
(35, 'compras.presupuesto.aditivas_precio',            'Aditivas y Deductivas Precio',                    12),
(35, 'compras.presupuesto.aditivas_precio_masivo',     'Aditivas y Deductivas Precio Masivo',             13),
(35, 'compras.presupuesto.escalatoria',                'Escalatoria Precios',                             14),
(35, 'compras.presupuesto.aut_escalatoria',            'Autorizacion de Escalatoria de Precios',          15),
(35, 'compras.presupuesto.imp_aditiva',                'Impresión de Aditiva/Deductiva',                  16),
(35, 'compras.presupuesto.imp_explosion',              'Impresión de Explosión de Materiales',            17),
(35, 'compras.presupuesto.abc_ppto_admin',             'ABC de Presupuestos Administrativos',             18),
(35, 'compras.presupuesto.copia_ppto',                 'Copia de Presupuesto',                            19),
(35, 'compras.presupuesto.captura_ppto',               'Captura de Presupuestos',                         20),
(35, 'compras.presupuesto.captura_adit_deduct',        'Captura de Presupuestos Aditivas y Deductivas',   21),
(35, 'compras.presupuesto.captura_adit_auto',          'Captura de Presupuestos Aditivas y Deductivas Automática', 22),
(35, 'compras.presupuesto.proceso_aut',                'Proceso de Autorización de Presupuestos',         23),
(35, 'compras.presupuesto.regen_ppto',                 'Re-Generación Presupuesto Administrativo',        24),
(35, 'compras.presupuesto.comp_vs_ppto',               'Comparativo vs Presupuesto',                      25),
(35, 'compras.presupuesto.rep_comp_ppto',              'Reporte Comparativo Ppto. Adm. vs Comprado',      26),
(35, 'compras.presupuesto.rep_comp_ppto_acum',         'Reporte Comparativo Ppto. Adm. vs Comprado Acumulado', 27),
(35, 'compras.presupuesto.imp_ppto',                   'Importación de Presupuestos',                     28),

-- Procesos Diarios
(1, 'compras.procesos',                                'Procesos Diarios',                                 3),
(65, 'compras.procesos.ctrl_ppto',                     'Control Presupuestal',                             1),
(65, 'compras.procesos.anticipos',                     'Anticipos',                                        2),
(67, 'compras.procesos.anticipos.abc',                 'A,B,C Anticipos Proveedor',                        1),
(67, 'compras.procesos.anticipos.reporte',             'Reporte Anticipos Proveedor',                      2),
(67, 'compras.procesos.anticipos.genera',              'Genera Anticipo Proveedor',                        3),
(67, 'compras.procesos.anticipos.edo_cuenta',          'Reporte Estado Cuenta Proveedor',                  4),
(65, 'compras.procesos.requisiciones',                 'Requisiciones',                                    3),
(65, 'compras.procesos.aut_requisiciones',             'Autorizacion de requisiciones',                    4),
(65, 'compras.procesos.cuadro_comp',                   'Cuadro Comparativo',                               5),
(65, 'compras.procesos.gen_oc',                        'Generación de O.C.',                               6),
(65, 'compras.procesos.ordenes_compra',                'Ordenes de Compra',                                7),
(65, 'compras.procesos.aut_oc',                        'Autorización OC',                                  8),
(65, 'compras.procesos.aut_oc_planeacion',             'Autorización OC Planeación',                       9),
(65, 'compras.procesos.act_oc_planeacion',             'Actualizar OC de Planeación',                     10),
(65, 'compras.procesos.email_oc',                      'Enviar email OC',                                 11),
(65, 'compras.procesos.aut_ordenes',                   'Autorización de Ordenes de Compra',               12),
(65, 'compras.procesos.ajustes_oc',                    'Ajustes/Cancelación O.C.',                        13),
(65, 'compras.procesos.ajustes_tolerancia',            'Ajustes OC - Tolerancia',                         14),
(65, 'compras.procesos.entradas_almacen',              'Entradas de Almacén',                             15),
(65, 'compras.procesos.traspasos_cc',                  'Traspasos a otro C.C.',                           16),
(65, 'compras.procesos.insumos_recibidos',             'Insumos Recibidos por Orden Compra',              17),
(65, 'compras.procesos.mov_no_inv',                    'Movimientos No Inventariables',                   18),
(85, 'compras.procesos.mov_no_inv.remision',           'Mtto. Remisión',                                   1),
(85, 'compras.procesos.mov_no_inv.rec_insumos',        'Recepción Insumos',                                2),
(85, 'compras.procesos.mov_no_inv.rec_global',         'Recepción Global Insumos',                         3),
(85, 'compras.procesos.mov_no_inv.cons_remision',      'Consulta Remisión',                                4),
(85, 'compras.procesos.mov_no_inv.rep_remision',       'Rep. Remisión',                                    5),
(85, 'compras.procesos.mov_no_inv.abc_modelos',        'ABC Modelos',                                      6),
(85, 'compras.procesos.mov_no_inv.abc_interfase',      'ABC Modelos de Interfase con Contabilidad Específica', 7),
(85, 'compras.procesos.mov_no_inv.rel_modelo_cc',      'Relación Modelo CC',                               8),
(85, 'compras.procesos.mov_no_inv.abc_if_cont',        'ABC Interfase Contabilidad',                       9),
(85, 'compras.procesos.mov_no_inv.def_transfer',       'Definición de Transferencia Poliza',              10),
(85, 'compras.procesos.mov_no_inv.gen_poliza',         'Proceso de Generación Póliza',                    11),
(85, 'compras.procesos.mov_no_inv.desact_poliza',      'Proceso de Desactualización Póliza',              12),

-- Consultas
(1, 'compras.consultas',                               'Consultas',                                        4),
(98, 'compras.consultas.requisiciones',                'Requisiciones',                                    1),
(98, 'compras.consultas.ordenes_compra',               'Ordenes de Compra',                                2),
(98, 'compras.consultas.entregas_prog',                'Entregas Programadas',                             3),
(98, 'compras.consultas.analisis_compras',             'Análisis de Compras',                              4),
(98, 'compras.consultas.insumos_excedidos',            'Insumos Excedidos',                                5),
(98, 'compras.consultas.req_comprador',                'Requisiciones por Comprador',                      6),
(98, 'compras.consultas.anal_cons_prov',               'Análisis Consolidado Proveedor',                   7),
(98, 'compras.consultas.comp_proveedores',             'Comportamiento de Proveedores',                    8),
(98, 'compras.consultas.flujos',                       'Flujos Comprometidos',                             9),
(98, 'compras.consultas.proyectos',                    'Proyectos',                                       10),
(109, 'compras.consultas.proyectos.estructura',        'Estructura por Proyecto',                          1),
(109, 'compras.consultas.proyectos.comp_proyecto',     'Comparativo por Proyecto',                         2),
(109, 'compras.consultas.proyectos.avance',            'Reporte de Avance Proyecto vs Real Promedio',      3),
(109, 'compras.consultas.proyectos.real_comp',         'Comparativo Real vs Comprometido',                 4),
(98, 'compras.consultas.anal_cons_cc',                 'Análisis Consolidado % CC',                       11),
(98, 'compras.consultas.comp_vs_ppto',                 'Comparativo VS Presupuesto',                      12),
(98, 'compras.consultas.comp_ppto_insumo',             'Comparativo VS Ppto por Tipo Insumo',             13);

-- Otros módulos raíz (se pueden expandir)
INSERT INTO `modulos_erp` (`parent_id`, `clave`, `nombre`, `orden`) VALUES
(NULL, 'contabilidad',    'Contabilidad',        2),
(NULL, 'proveedores',     'Proveedores',          3),
(NULL, 'precios_unit',    'Precios Unitarios',    4),
(NULL, 'bancos',          'Bancos',               5),
(NULL, 'blindaje_fiscal', 'Blindaje Fiscal',      6),
(NULL, 'clientes',        'Clientes',             7),
(NULL, 'vivienda',        'Vivienda',             8),
(NULL, 'inventarios',     'Inventarios',          9);

-- =====================================================================
-- PROGRAMA NIVEL (reemplaza perfiles con árbol de permisos ERP)
-- =====================================================================

CREATE TABLE IF NOT EXISTS `programa_nivel` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nivel`       SMALLINT     NOT NULL COMMENT 'Número de nivel (0=ADMON1, 1=EK, etc.)',
    `nombre`      VARCHAR(100) NOT NULL,
    `descripcion` VARCHAR(500) NULL,
    `activo`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`  TIMESTAMP    NULL,
    PRIMARY KEY (`id`),
    KEY `idx_nivel` (`nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Niveles del sistema EK (extraídos de W_EK031)
INSERT INTO `programa_nivel` (`nivel`, `nombre`) VALUES
(0,  'ADMON1'),
(1,  'EK'),
(2,  'Direcciones'),
(3,  'Comprador'),
(4,  'Comprador de obra'),
(5,  'Contador'),
(6,  'Gerente Contabilidad'),
(7,  'Presupuestos'),
(8,  'Obra'),
(9,  'Control presupuestal'),
(10, 'Almacenista'),
(11, 'Cuentas por cobrar'),
(12, 'Dirección de construcción'),
(13, 'Presupuesto de Kontrol'),
(14, 'Cuentas por Cobrar'),
(15, 'Coordinación Comercial'),
(16, 'Control de Obra'),
(17, 'GERENCIA COMERCIAL'),
(18, 'OPERACION COMERCIAL'),
(19, 'Aut-VoBo Externo'),
(20, 'Comprador Externo'),
(21, 'Consultas y reportes comercial'),
(22, 'Jefe de Almacén'),
(23, 'Gerente de Contabilidad'),
(24, 'Sub Dir General'),
(25, 'Dir Admon y Finanzas'),
(26, 'Jefe de R. H.'),
(27, 'Jefe de Tesorería');

-- Permisos del Programa Nivel sobre módulos ERP
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

-- =====================================================================
-- SISTEMA DE USUARIOS Y EMPLEADOS
-- =====================================================================

CREATE TABLE IF NOT EXISTS `usuarios` (
    `id`                   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username`             VARCHAR(80)  NOT NULL,
    `email`                VARCHAR(150) NOT NULL,
    `password_hash`        VARCHAR(255) NOT NULL,
    `nombre`               VARCHAR(100) NOT NULL,
    `apellido`             VARCHAR(100) NULL,
    `rol`                  ENUM('superadmin','admin','capturista','usuario') NOT NULL DEFAULT 'usuario',
    `activo`               TINYINT(1)   NOT NULL DEFAULT 1,
    `aprobado`             TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '0=pendiente aprobación',
    `token_aprobacion`     VARCHAR(64)  NULL,
    `token_recuperacion`   VARCHAR(64)  NULL,
    `token_expira`         DATETIME     NULL,
    `debe_cambiar_pwd`     TINYINT(1)   NOT NULL DEFAULT 0,
    `ultimo_acceso`        DATETIME     NULL,
    `intentos_fallidos`    TINYINT      NOT NULL DEFAULT 0,
    `bloqueado_hasta`      DATETIME     NULL,
    `created_by`           INT UNSIGNED NULL,
    `created_at`           TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    `updated_at`           TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`           TIMESTAMP    NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_username` (`username`),
    UNIQUE KEY `idx_email`    (`email`),
    FOREIGN KEY (`created_by`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin principal (password: Syndulla25@)
-- Password: Syndulla25@
INSERT INTO `usuarios` (`username`, `email`, `password_hash`, `nombre`, `apellido`, `rol`, `activo`, `aprobado`) VALUES
('ecruz', 'ecruz@urbanopark.com',
 '$2y$12$dSm6Ohs89uwW33qkWzYvSOtq/on9s1Us081W8grbZDnTq1Q8C8U2a',
 'Erick', 'Cruz', 'superadmin', 1, 1);

CREATE TABLE IF NOT EXISTS `empleados` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`          VARCHAR(20)  NULL COMMENT 'ID externo ERP (ej: 2, 11, 12)',
    `nombre`           VARCHAR(200) NOT NULL,
    `puesto`           VARCHAR(150) NULL,
    `empresa_id`       INT UNSIGNED NULL,
    `jefe_id`          INT UNSIGNED NULL COMMENT 'Jefe directo para organigrama',
    `email`            VARCHAR(150) NULL,
    `telefono`         VARCHAR(30)  NULL,
    `activo`           TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`       TIMESTAMP    NULL,
    PRIMARY KEY (`id`),
    KEY `idx_empresa` (`empresa_id`),
    KEY `idx_jefe`    (`jefe_id`),
    KEY `idx_user_id` (`user_id`),
    FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id`)   ON DELETE SET NULL,
    FOREIGN KEY (`jefe_id`)    REFERENCES `empleados`(`id`)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Empleados desde datos existentes de erickedu_ekpermisos
INSERT INTO `empleados` (`user_id`, `nombre`, `empresa_id`) VALUES
('2',   'MOISES ROMANO MICHA',               1),
('3',   'SAMUEL ALFILLE MIZRAHI',            1),
('11',  'LUZ GEORGINA HARO VERGARA',         1),
('12',  'LUISA FERNANDA BARAJAS ARCILA',     1),
('15',  'MANUEL CESAR GARCIA GUTIERREZ',     1),
('16',  'LUIS HORACIO ROSALES CASTILLO',     1),
('20',  'OMAR ZULUAGA SANTANDER',            1),
('38',  'JOSE LUIS SEGURA VICTORIA',         1),
('39',  'DANIEL GONZALEZ LUNA',              1),
('45',  'GIOVANNI ALVAREZ COLIN',            1),
('50',  'RUTH JIMENEZ MENDOZA',              1),
('52',  'EVELIN CORINA CONTRERAS RENTERIA',  1),
('61',  'ALEJANDRO RICO MOLINA',             1),
('81',  'RODRIGO ALONSO SEPULVEDA GONZALEZ', 1),
('95',  'GAMALIEL CABRERA GARRIDO',          1),
('96',  'SUSANA PEÑA ADAN',                  1),
('104', 'GABRIELA DEL CARMEN QUIJANO GONZALEZ', 1),
('108', 'MARIA ANTONIETA JOSE GONZALEZ',     1),
('110', 'ADAN GARCIA LINO',                  1),
('121', 'GISELA HERNANDEZ SANCHEZ',          1);

-- Relación empleado -> programa nivel
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

-- =====================================================================
-- ASIGNACIÓN DE CENTROS DE COSTO A EMPLEADOS
-- tipo: REQ = Requisición, OC = Orden de Compra, AMBOS = ambos
-- =====================================================================

CREATE TABLE IF NOT EXISTS `empleado_cc` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `empleado_id` INT UNSIGNED NOT NULL,
    `cc_id`       INT UNSIGNED NOT NULL,
    `tipo`        ENUM('REQ','OC','AMBOS') NOT NULL DEFAULT 'AMBOS',
    `elab`        TINYINT(1)   NOT NULL DEFAULT 0 COMMENT 'Elaboración',
    `vobo`        TINYINT(1)   NOT NULL DEFAULT 0 COMMENT 'Visto Bueno',
    `aut`         TINYINT(1)   NOT NULL DEFAULT 0 COMMENT 'Autorización',
    `monto`       DECIMAL(18,2) NOT NULL DEFAULT 0.00 COMMENT 'Monto máximo autorizado',
    `activo`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_emp_cc_tipo` (`empleado_id`, `cc_id`, `tipo`),
    KEY `idx_cc`  (`cc_id`),
    FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`id`)     ON DELETE CASCADE,
    FOREIGN KEY (`cc_id`)       REFERENCES `centros_costo`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- REQUISITORES (empleados que elaboran/visto bueno/autorizan REQ)
-- =====================================================================

CREATE TABLE IF NOT EXISTS `requisitores` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `empleado_id` INT UNSIGNED NOT NULL,
    `activo`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_emp` (`empleado_id`),
    FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- COMPRADORES (empleados que elaboran/visto bueno/autorizan OC)
-- =====================================================================

CREATE TABLE IF NOT EXISTS `compradores` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `empleado_id` INT UNSIGNED NOT NULL,
    `activo`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_emp` (`empleado_id`),
    FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- RELACIÓN USUARIO (login) <-> EMPLEADO
-- =====================================================================

CREATE TABLE IF NOT EXISTS `usuario_empleado` (
    `usuario_id`  INT UNSIGNED NOT NULL,
    `empleado_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`usuario_id`),
    FOREIGN KEY (`usuario_id`)  REFERENCES `usuarios`(`id`)  ON DELETE CASCADE,
    FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Relación usuario -> programa nivel (para acceso al sistema web)
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

-- =====================================================================
-- SEGURIDAD Y AUDITORÍA
-- =====================================================================

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

SET FOREIGN_KEY_CHECKS = 1;
