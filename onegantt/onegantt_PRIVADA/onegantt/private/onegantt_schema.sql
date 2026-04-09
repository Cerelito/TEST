SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `roles` (
  `id`          TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(50)      NOT NULL,
  `slug`        VARCHAR(50)      NOT NULL,
  `descripcion` VARCHAR(255)         NULL,
  `created_at`  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ══════════════════════════════════════════════════════════════════
--  ROLES DEL SISTEMA
--  admin      → Acceso total (catálogos, usuarios, configuración)
--  director   → Ve tareas de TODOS los usuarios en el dashboard,
--               gestiona proyectos y tareas. Sin acceso a catálogos.
--  colaborador→ Ve solo sus propias tareas en el dashboard,
--               gestiona proyectos y tareas. Sin acceso a catálogos.
-- ══════════════════════════════════════════════════════════════════
INSERT INTO `roles` (`nombre`, `slug`, `descripcion`) VALUES
  ('Administrador', 'admin',       'Acceso total: catálogos, usuarios y configuración'),
  ('Director',      'director',    'Ve pendientes de todos los usuarios, gestiona proyectos y tareas'),
  ('Colaborador',   'colaborador', 'Ve y gestiona sus propias tareas y proyectos');

CREATE TABLE IF NOT EXISTS `users` (
  `id`           INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `rol_id`       TINYINT UNSIGNED NOT NULL DEFAULT 3,
  `nombre`       VARCHAR(100)     NOT NULL,
  `email`        VARCHAR(150)     NOT NULL,
  `password`     VARCHAR(255)     NOT NULL,
  `activo`       TINYINT(1)       NOT NULL DEFAULT 1,
  `token_reset`  VARCHAR(100)         NULL,
  `token_expira` DATETIME             NULL,
  `ultimo_login` DATETIME             NULL,
  `created_at`   TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `fk_users_rol` (`rol_id`),
  CONSTRAINT `fk_users_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contraseña inicial: Admin1234!
-- Regenerar con: password_hash('Admin1234!', PASSWORD_BCRYPT)
INSERT INTO `users` (`rol_id`, `nombre`, `email`, `password`) VALUES
  (1, 'Administrador', 'erick@apotemaone.com',
   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

CREATE TABLE IF NOT EXISTS `projects` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(150) NOT NULL,
  `descripcion` TEXT             NULL,
  `color`       VARCHAR(7)   NOT NULL DEFAULT '#5563DE',
  `activo`      TINYINT(1)   NOT NULL DEFAULT 1,
  `created_by`  INT UNSIGNED NOT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_projects_user` (`created_by`),
  CONSTRAINT `fk_projects_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `statuses` (
  `id`     TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80)      NOT NULL,
  `color`  VARCHAR(7)       NOT NULL DEFAULT '#888888',
  `orden`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `statuses` (`nombre`, `color`, `orden`) VALUES
  ('Pendiente',   '#F59E0B', 1),
  ('En progreso', '#3B82F6', 2),
  ('En revisión', '#8B5CF6', 3),
  ('Completado',  '#10B981', 4),
  ('Cancelado',   '#EF4444', 5);

CREATE TABLE IF NOT EXISTS `tasks` (
  `id`           INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `padre_id`     INT UNSIGNED         NULL,
  `proyecto_id`  INT UNSIGNED     NOT NULL,
  `estatus_id`   TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `titulo`       VARCHAR(255)     NOT NULL,
  `descripcion`  TEXT                 NULL,
  `asignado_a`   INT UNSIGNED         NULL,
  `creado_por`   INT UNSIGNED     NOT NULL,
  `fecha_inicio` DATE                 NULL,
  `fecha_fin`    DATE                 NULL,
  `prioridad`    TINYINT UNSIGNED NOT NULL DEFAULT 2,
  `progreso`     TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `orden`        SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`   TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tasks_padre`    (`padre_id`),
  KEY `idx_tasks_proyecto` (`proyecto_id`),
  KEY `idx_tasks_asignado` (`asignado_a`),
  KEY `idx_tasks_estatus`  (`estatus_id`),
  KEY `idx_tasks_fechas`   (`fecha_inicio`, `fecha_fin`),
  CONSTRAINT `fk_tasks_padre`    FOREIGN KEY (`padre_id`)    REFERENCES `tasks`    (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_tasks_proyecto` FOREIGN KEY (`proyecto_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tasks_estatus`  FOREIGN KEY (`estatus_id`)  REFERENCES `statuses` (`id`),
  CONSTRAINT `fk_tasks_asignado` FOREIGN KEY (`asignado_a`)  REFERENCES `users`    (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_tasks_creador`  FOREIGN KEY (`creado_por`)  REFERENCES `users`    (`id`),
  CONSTRAINT `chk_progreso`      CHECK (`progreso` BETWEEN 0 AND 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `task_dependencies` (
  `tarea_id`      INT UNSIGNED NOT NULL,
  `depende_de_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`tarea_id`, `depende_de_id`),
  CONSTRAINT `fk_dep_tarea`  FOREIGN KEY (`tarea_id`)      REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dep_origen` FOREIGN KEY (`depende_de_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `task_notes` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tarea_id`   INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `nota`       TEXT         NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_notes_tarea`   (`tarea_id`),
  KEY `fk_notes_usuario` (`usuario_id`),
  CONSTRAINT `fk_notes_tarea`   FOREIGN KEY (`tarea_id`)   REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `attachments` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `tarea_id`     INT UNSIGNED  NOT NULL,
  `usuario_id`   INT UNSIGNED  NOT NULL,
  `nombre_orig`  VARCHAR(255)  NOT NULL,
  `nombre_disco` VARCHAR(255)  NOT NULL,
  `mime_type`    VARCHAR(100)  NOT NULL,
  `tamano`       INT UNSIGNED  NOT NULL,
  `created_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_att_tarea`   (`tarea_id`),
  KEY `fk_att_usuario` (`usuario_id`),
  CONSTRAINT `fk_att_tarea`   FOREIGN KEY (`tarea_id`)   REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_att_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` VARCHAR(128) NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `ip`         VARCHAR(45)      NULL,
  `user_agent` VARCHAR(255)     NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` DATETIME     NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `fk_sess_usuario` (`usuario_id`),
  CONSTRAINT `fk_sess_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
