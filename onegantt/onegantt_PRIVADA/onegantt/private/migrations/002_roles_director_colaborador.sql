-- ══════════════════════════════════════════════════════════════════
--  MIGRACIÓN 002 · Actualización de Roles
--  Apotema Lab · OneGantt
--  Fecha: 2025-04
--
--  ANTES: admin | gestor | usuario
--  DESPUÉS: admin | director | colaborador
--
--  EJECUTAR UNA SOLA VEZ en la base de datos de producción.
-- ══════════════════════════════════════════════════════════════════

SET NAMES utf8mb4;

-- 1. Actualizar slug y nombre del rol "gestor" → "director"
UPDATE `roles`
SET
  `nombre`      = 'Director',
  `slug`        = 'director',
  `descripcion` = 'Ve pendientes de todos los usuarios, gestiona proyectos y tareas'
WHERE `slug` = 'gestor';

-- 2. Actualizar slug y nombre del rol "usuario" → "colaborador"
UPDATE `roles`
SET
  `nombre`      = 'Colaborador',
  `slug`        = 'colaborador',
  `descripcion` = 'Ve y gestiona sus propias tareas y proyectos'
WHERE `slug` = 'usuario';

-- 3. Verificar resultado
SELECT id, nombre, slug, descripcion FROM `roles` ORDER BY id;
