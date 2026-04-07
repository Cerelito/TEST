-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 07-04-2026 a las 09:35:30
-- Versión del servidor: 10.6.24-MariaDB-cll-lve
-- Versión de PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ekuora_db`
--
CREATE DATABASE IF NOT EXISTS `ekuora_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ekuora_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes`
--

CREATE TABLE `ajustes` (
  `id` int(10) UNSIGNED NOT NULL,
  `clave` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ajustes`
--

INSERT INTO `ajustes` (`id`, `clave`, `valor`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'site_title', 'Ekuora - Catálogo', 'Título del sitio', '2026-01-27 17:12:07', '2026-01-27 17:12:07'),
(2, 'contact_email', 'contacto@ekuora.com', 'Email de contacto', '2026-01-27 17:12:07', '2026-01-27 17:12:07'),
(3, 'promo_titulo', 'PROMO TEST', NULL, '2026-01-27 19:53:37', '2026-01-28 04:09:38'),
(4, 'promo_subtitulo', 'test 2', NULL, '2026-01-27 19:53:37', '2026-01-27 19:53:37'),
(5, 'promo_texto_boton', 'test boton', NULL, '2026-01-27 19:53:37', '2026-01-27 20:17:30'),
(6, 'promo_enlace', '', NULL, '2026-01-27 19:53:37', '2026-01-27 19:53:37'),
(7, 'promo_imagen', 'uploads_privados/ajustes/1774974230_promo__nk3vfj2w12a36fjkkoob_0.png', NULL, '2026-01-27 19:53:37', '2026-03-31 16:23:52'),
(12, 'footer_texto', '\"Elevamos lo cotidiano a extraordinario a través del diseño\"', NULL, '2026-01-27 20:06:11', '2026-01-28 23:52:20'),
(13, 'footer_facebook', 'www.cerelit.com', NULL, '2026-01-27 20:06:11', '2026-01-27 20:22:38'),
(14, 'footer_instagram', 'www.cerelit.com', NULL, '2026-01-27 20:06:11', '2026-01-27 20:22:38'),
(15, 'footer_youtube', 'www.cerelit.com', NULL, '2026-01-27 20:06:11', '2026-01-27 20:22:38'),
(16, 'footer_tiktok', 'www.cerelit.com', NULL, '2026-01-27 20:06:11', '2026-01-27 20:22:38'),
(17, 'logo_navbar', 'uploads_privados/ajustes/1769545954_logo_logo nuevo.svg', NULL, '2026-01-27 20:06:11', '2026-01-27 20:32:34'),
(62, 'footer_email', 'hola@ekuora.com.mx', NULL, '2026-01-27 20:22:05', '2026-01-27 20:22:05'),
(63, 'footer_telefono', '(52) 5527778799', NULL, '2026-01-27 20:22:05', '2026-01-27 20:22:05'),
(64, 'footer_direccion', 'AV. DE LA PRUEBA 123, DEPTO 101', NULL, '2026-01-27 20:22:05', '2026-01-27 20:22:05'),
(111, 'about_badge', 'Nuestra Esencia', NULL, '2026-01-28 22:24:27', '2026-01-28 22:24:27'),
(112, 'about_titulo', '\"Elevamos lo cotidiano a extraordinario a través del diseño\"', NULL, '2026-01-28 22:24:27', '2026-01-28 23:59:29'),
(113, 'about_descripcion', '', NULL, '2026-01-28 22:24:27', '2026-01-28 22:24:27'),
(114, 'about_f1_titulo', 'Innovación cotidiana.', NULL, '2026-01-28 22:24:27', '2026-01-28 23:59:29'),
(115, 'about_f1_texto', 'Creemos en la innovación cotidiana como parte esencial de lo que hacemos.', NULL, '2026-01-28 22:24:27', '2026-01-28 23:59:29'),
(116, 'about_f2_titulo', 'Excelencia accesible.', NULL, '2026-01-28 22:24:27', '2026-01-28 23:59:29'),
(117, 'about_f2_texto', 'Creemos que el buen diseño no debe de ser un lujo, si no algo accesible para todos.', NULL, '2026-01-28 22:24:27', '2026-01-28 23:59:29'),
(118, 'about_f3_titulo', 'Ingenioso, Curioso, Práctico, Brillante Apasionado.', NULL, '2026-01-28 22:24:27', '2026-01-28 23:59:29'),
(119, 'about_f3_texto', 'El que se atreve a cuestionarse la cotidianidad y mejorarla día a día.', NULL, '2026-01-28 22:24:27', '2026-01-28 23:59:29'),
(120, 'about_f4_titulo', '', NULL, '2026-01-28 22:24:27', '2026-01-28 23:59:29'),
(121, 'about_f4_texto', '', NULL, '2026-01-28 22:24:27', '2026-01-28 23:59:29'),
(131, 'about_imagen', 'uploads_privados/ajustes/1774974231_about__lp8t5jjpc2mnx29kdlen_0.png', NULL, '2026-01-28 22:24:27', '2026-03-31 16:23:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banners_home`
--

CREATE TABLE `banners_home` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `subtitulo` varchar(255) DEFAULT NULL,
  `texto_boton` varchar(50) DEFAULT 'VER MÁS',
  `enlace` varchar(255) DEFAULT NULL,
  `seccion` varchar(50) DEFAULT 'hero',
  `imagen` varchar(255) NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `banners_home`
--

INSERT INTO `banners_home` (`id`, `titulo`, `subtitulo`, `texto_boton`, `enlace`, `seccion`, `imagen`, `orden`, `activo`, `created_at`, `updated_at`) VALUES
(4, '', '', 'LANZAMIENTOS', '', 'hero', 'uploads_privados/banners/banner_69cbf38694ec0.png', 0, 1, '2026-01-27 23:42:01', '2026-03-31 16:35:12'),
(5, 'Recomendado test', 'test', 'EXPLORAR COLECCION', '', 'recommend', 'uploads_privados/banners/banner_69cbf4bb89fb6.png', 1, 1, '2026-01-28 04:03:46', '2026-03-31 16:22:19'),
(6, 'Hola', 'Test', 'EXPLORAR COLECCION', 'https://www.ekuora.com.mx/productos/categoria/contenedores', 'hero', 'uploads_privados/banners/banner_69cc5b836eeaa.jpeg', 1, 1, '2026-03-31 23:40:51', '2026-03-31 23:41:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_productos`
--

CREATE TABLE `categorias_productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `icono` varchar(50) DEFAULT 'bi-box-seam',
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias_productos`
--

INSERT INTO `categorias_productos` (`id`, `nombre`, `slug`, `descripcion`, `imagen`, `icono`, `orden`, `activo`, `destacado`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cook & Bake test', 'cook-bake-test', 'Organizadores para cocinar', NULL, 'bi-fire', 1, 0, 0, '2026-01-27 16:32:22', '2026-01-27 17:16:02', '2026-01-27 17:16:02'),
(2, 'RENAMED_ID_2', 'test-1', 'Organización para botellas', NULL, 'bi-droplet', 3, 1, 1, '2026-01-27 16:32:22', '2026-01-27 22:41:39', '2026-01-27 22:41:39'),
(3, 'Cleanup', 'cleanup', 'Organizadores para limpieza', NULL, 'bi-stars', 3, 0, 0, '2026-01-27 16:32:22', '2026-01-27 17:29:19', '2026-01-27 17:29:19'),
(4, 'Food Storage', 'food-storage', 'Almacenamiento de alimentos', NULL, 'bi-box2', 4, 0, 0, '2026-01-27 16:32:22', '2026-01-27 17:28:18', '2026-01-27 17:28:18'),
(5, 'Stash', 'stash', 'Almacenamiento general', NULL, 'bi-folder', 5, 0, 0, '2026-01-27 16:32:22', '2026-01-27 17:20:23', '2026-01-27 17:20:23'),
(6, 'Fresh for Bath', 'fresh-for-bath', 'Organizadores para el baño', NULL, 'bi-moisture', 6, 0, 0, '2026-01-27 16:32:22', '2026-01-27 17:15:57', '2026-01-27 17:15:57'),
(7, 'Hidratación', 'hidrataci-n', 'descripcion didratacion test', 'uploads_privados/categorias/categorias_697aa01f65f26.jpg', 'bi-box-seam', 1, 1, 1, '2026-01-27 16:53:42', '2026-01-28 23:47:43', NULL),
(8, 'Contenedores', 'contenedores', '', 'uploads_privados/categorias/categorias_69caaa6bc0aef.jpg', 'bi-box-seam', 4, 1, 1, '2026-01-27 17:28:42', '2026-03-30 16:52:59', NULL),
(9, 'test4', 'test4', '', NULL, 'bi-box-seam', 4, 1, 1, '2026-01-27 19:50:54', '2026-01-27 20:56:26', '2026-01-27 20:56:26'),
(10, 'Organización', 'organizaci-n', 'test', 'uploads_privados/categorias/categorias_697aa02eb61b2.jpg', 'bi-box-seam', 3, 1, 1, '2026-01-27 22:42:16', '2026-01-28 23:47:58', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colecciones`
--

CREATE TABLE `colecciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `colecciones`
--

INSERT INTO `colecciones` (`id`, `nombre`, `slug`, `descripcion`, `imagen`, `orden`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Nuevo producto', 'nuevo-producto', '', 'uploads_privados/colecciones/colecciones_69cbf8cf47f77.png', 0, 1, '2026-03-31 16:39:43', '2026-03-31 16:39:43', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familias_productos`
--

CREATE TABLE `familias_productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `categoria_id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `orden` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `familias_productos`
--

INSERT INTO `familias_productos` (`id`, `categoria_id`, `nombre`, `slug`, `descripcion`, `imagen`, `activo`, `orden`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Kitchen Organizers', 'kitchen-organizers', 'Organizadores para cocina', NULL, 1, 0, '2026-01-27 16:38:56', '2026-01-27 16:54:08', '2026-01-27 16:54:08'),
(2, 2, 'Hydration Solutions', 'hydration-solutions', 'Soluciones de hidratación', NULL, 1, 0, '2026-01-27 16:38:56', '2026-01-27 16:54:04', '2026-01-27 16:54:04'),
(3, 4, 'Food Storage Systems', 'food-storage-systems', 'Sistemas de almacenamiento', NULL, 1, 0, '2026-01-27 16:38:56', '2026-01-27 16:54:11', '2026-01-27 16:54:11'),
(4, 7, 'Botellas Infantiles', 'botellas-infantiles', '', 'uploads_privados/familias/familias_697aa0b29946f.jpg', 1, 1, '2026-01-27 16:55:30', '2026-01-28 23:50:10', NULL),
(5, 7, 'UpdatedFam 4684', 'slug-8720', 'Updated Desc', NULL, 1, 2, '2026-01-27 18:15:30', '2026-01-27 18:15:30', '2026-01-27 18:15:30'),
(6, 7, 'Botellas Casuales', 'botellas-casuales', 'test botellas casuales', 'uploads_privados/familias/familias_697aa09592a3d.jpg', 1, 1, '2026-01-28 22:30:11', '2026-01-28 23:49:41', NULL),
(7, 7, 'Botella Deportiva', 'botella-deportiva', 'Test Botellas Deportivas', 'uploads_privados/familias/familias_697aa06c10831.jpg', 1, 0, '2026-01-28 22:30:47', '2026-01-28 23:49:00', NULL),
(8, 10, 'Ice', 'ice', 'test Ice', 'uploads_privados/familias/familias_697aa081b8e82.jpg', 1, 0, '2026-01-28 22:31:30', '2026-01-28 23:49:21', NULL),
(9, 10, 'Pastillero', 'pastillero', 'test Pastillero', 'uploads_privados/familias/familias_697aa08ad6951.jpg', 1, 0, '2026-01-28 22:31:58', '2026-01-28 23:49:30', NULL),
(10, 8, 'Cube', 'cube', 'test Cube', 'uploads_privados/familias/familias_69c6a71212f17.jpg', 1, 0, '2026-01-28 22:33:22', '2026-03-27 15:49:38', NULL),
(11, 8, 'Balance', 'balance', 'test Balance', 'uploads_privados/familias/familias_697aa05ddb85c.jpg', 1, 0, '2026-01-28 22:33:48', '2026-01-28 23:48:45', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_productos`
--

CREATE TABLE `imagenes_productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `alt_text` varchar(200) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `imagenes_productos`
--

INSERT INTO `imagenes_productos` (`id`, `producto_id`, `ruta`, `alt_text`, `orden`, `created_at`) VALUES
(1, 3, 'img/productos/placeholder.png', 'Water Bottle Storage Rack', 1, '2026-01-27 17:06:24'),
(2, 4, 'img/productos/placeholder.png', 'Under Sink Organizer', 1, '2026-01-27 17:06:24'),
(3, 5, 'img/productos/placeholder.png', 'Food Container Organizer', 1, '2026-01-27 17:06:24'),
(4, 6, 'uploads_privados/productos/productos_69790a42ae771.png', 'Botella Infantil Muuk 500ml', 0, '2026-01-27 18:56:02'),
(5, 6, 'uploads_privados/productos/productos_69790a597055f.png', 'Botella Infantil Muuk 500ml', 1, '2026-01-27 18:56:25'),
(6, 8, 'uploads_privados/productos/productos_69c314b010fea.jpg', 'Contenedor Cube Chico 500 ml', 0, '2026-03-24 22:48:16'),
(7, 8, 'uploads_privados/productos/productos_69c314b011dc8.jpg', 'Contenedor Cube Chico 500 ml', 1, '2026-03-24 22:48:16'),
(8, 8, 'uploads_privados/productos/productos_69c314b012a0a.jpg', 'Contenedor Cube Chico 500 ml', 2, '2026-03-24 22:48:16'),
(12, 11, 'uploads_privados/productos/productos_69c5a81a7eb1d.jpg', 'Contenedor Cube Chico 650 ml', 0, '2026-03-26 21:41:46'),
(13, 11, 'uploads_privados/productos/productos_69c5a81a8037b.jpg', 'Contenedor Cube Chico 650 ml', 1, '2026-03-26 21:41:46'),
(14, 11, 'uploads_privados/productos/productos_69c5a81a819ba.jpg', 'Contenedor Cube Chico 650 ml', 2, '2026-03-26 21:41:46'),
(15, 26, 'uploads_privados/productos/productos_69c5b2f69413c.jpg', 'Cube Chico 500 ml', 0, '2026-03-26 22:28:06'),
(16, 26, 'uploads_privados/productos/productos_69c5b2f695205.jpg', 'Cube Chico 500 ml', 1, '2026-03-26 22:28:06'),
(17, 26, 'uploads_privados/productos/productos_69c5b2f695efd.jpg', 'Cube Chico 500 ml', 2, '2026-03-26 22:28:06'),
(20, 9, 'uploads_privados/productos/productos_69c69aaeca83e.jpg', 'Contenedor Cube Chico 350 ml', 1, '2026-03-27 14:56:46'),
(21, 9, 'uploads_privados/productos/productos_69c69aaecc3fe.jpg', 'Contenedor Cube Chico 350 ml', 2, '2026-03-27 14:56:46'),
(28, 9, 'uploads_privados/productos/productos_69c69ab946bf6.jpg', 'Contenedor Cube Chico 350 ml', 0, '2026-03-27 14:56:57'),
(30, 27, 'uploads_privados/productos/productos_69c69b7eda679.jpg', 'Contenedor Cube Chico 500  ml', 1, '2026-03-27 15:00:14'),
(31, 27, 'uploads_privados/productos/productos_69c69b7edcbc3.jpg', 'Contenedor Cube Chico 500  ml', 2, '2026-03-27 15:00:14'),
(32, 27, 'uploads_privados/productos/productos_69c69b7ede188.jpg', 'Contenedor Cube Chico 500  ml', 0, '2026-03-27 15:00:14'),
(33, 28, 'uploads_privados/productos/productos_69c69c0ebf935.jpg', 'Contenedor Cube Chico 650 ml', 1, '2026-03-27 15:02:38'),
(34, 28, 'uploads_privados/productos/productos_69c69c0ec178e.jpg', 'Contenedor Cube Chico 650 ml', 2, '2026-03-27 15:02:38'),
(35, 28, 'uploads_privados/productos/productos_69c69c0ec37ce.jpg', 'Contenedor Cube Chico 650 ml', 0, '2026-03-27 15:02:38'),
(37, 29, 'uploads_privados/productos/productos_69c69cc39bd42.jpg', 'Contenedor Cube Chico 650 ml', 1, '2026-03-27 15:05:39'),
(38, 29, 'uploads_privados/productos/productos_69c69cc3a127f.jpg', 'Contenedor Cube Chico 650 ml', 2, '2026-03-27 15:05:39'),
(39, 29, 'uploads_privados/productos/productos_69c69cf590178.jpg', 'Contenedor Cube Chico 650 ml', 0, '2026-03-27 15:06:29'),
(40, 30, 'uploads_privados/productos/productos_69c69e1b4e45a.jpg', 'Contenedor Cube Mediano 850 ml', 2, '2026-03-27 15:11:23'),
(42, 30, 'uploads_privados/productos/productos_69c69e1b53462.jpg', 'Contenedor Cube Mediano 850 ml', 0, '2026-03-27 15:11:23'),
(43, 30, 'uploads_privados/productos/productos_69c69e1b558ed.jpg', 'Contenedor Cube Mediano 850 ml', 1, '2026-03-27 15:11:23'),
(45, 31, 'uploads_privados/productos/productos_69c69eaa06309.jpg', 'Contenedor Cube Mediano 1100 ml', 2, '2026-03-27 15:13:46'),
(46, 31, 'uploads_privados/productos/productos_69c69eaa087a1.jpg', 'Contenedor Cube Mediano 1100 ml', 0, '2026-03-27 15:13:46'),
(47, 31, 'uploads_privados/productos/productos_69c69eaa0f2aa.jpg', 'Contenedor Cube Mediano 1100 ml', 1, '2026-03-27 15:13:46'),
(48, 32, 'uploads_privados/productos/productos_69c6a40f48c8a.jpg', 'Set Contenedores Cube Chicos 350 ml, 500 ml y 650 ml', 0, '2026-03-27 15:36:47'),
(50, 33, 'uploads_privados/productos/productos_69c6a4699a5c6.jpg', NULL, 99, '2026-03-27 15:44:56'),
(53, 7, 'uploads_privados/productos/productos_69c6b7627eae0.jpeg', 'Botella Nuuk 2 L', 1, '2026-03-27 16:59:14'),
(55, 7, 'uploads_privados/productos/productos_69c6b7627a247.png', NULL, 0, '2026-03-27 16:59:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_seguridad`
--

CREATE TABLE `logs_seguridad` (
  `id` int(10) UNSIGNED NOT NULL,
  `evento` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `usuario_id` int(10) UNSIGNED DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `metodo` varchar(10) DEFAULT NULL,
  `nivel` enum('info','warning','error','critical') DEFAULT 'info',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `logs_seguridad`
--

INSERT INTO `logs_seguridad` (`id`, `evento`, `descripcion`, `usuario_id`, `ip`, `user_agent`, `url`, `metodo`, `nivel`, `created_at`) VALUES
(1, 'producto_eliminado', 'Producto ID: 3', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/productos/eliminar/3', 'POST', 'info', '2026-01-27 17:13:06'),
(2, 'producto_eliminado', 'Producto ID: 4', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/productos/eliminar/4', 'POST', 'info', '2026-01-27 17:13:10'),
(3, 'producto_eliminado', 'Producto ID: 5', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/productos/eliminar/5', 'POST', 'info', '2026-01-27 17:13:13'),
(4, 'categoria_eliminada', 'Categoría ID: 6', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/productos/eliminar-categoria/6', 'POST', 'info', '2026-01-27 17:15:57'),
(5, 'categoria_eliminada', 'Categoría ID: 1', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/productos/eliminar-categoria/1', 'POST', 'info', '2026-01-27 17:16:02'),
(6, 'categoria_eliminada', 'Categoría ID: 5', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/productos/eliminar-categoria/5', 'POST', 'info', '2026-01-27 17:20:23'),
(7, 'categoria_eliminada', 'Categoría ID: 4', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/productos/eliminar-categoria/4', 'POST', 'info', '2026-01-27 17:28:18'),
(8, 'categoria_eliminada', 'Categoría ID: 3', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/productos/eliminar-categoria/3', 'POST', 'info', '2026-01-27 17:29:19'),
(9, 'login_exitoso', 'Usuario admin inició sesión', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/auth/procesar', 'POST', 'info', '2026-01-27 17:32:08'),
(10, 'login_exitoso', 'Usuario admin inició sesión', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/auth/procesar', 'POST', 'info', '2026-01-27 19:28:39'),
(11, 'login_exitoso', 'Usuario admin inició sesión', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/auth/procesar', 'POST', 'info', '2026-01-27 20:13:41'),
(12, 'login_exitoso', 'Usuario admin inició sesión', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/auth/procesar', 'POST', 'info', '2026-01-27 20:55:31'),
(13, 'usuario_actualizado', 'Usuario ID: 2 actualizado', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/usuarios/actualizar/2', 'POST', 'info', '2026-01-27 20:55:56'),
(14, 'logout', 'Usuario admin cerró sesión', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/logout', 'GET', 'info', '2026-01-27 20:56:00'),
(15, 'login_exitoso', 'Usuario  inició sesión', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/auth/procesar', 'POST', 'info', '2026-01-27 20:56:03'),
(16, 'categoria_eliminada', 'Categoría ID: 2', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/Ekuora/CARPETA_PUBLICA/productos/eliminar-categoria/2', 'POST', 'info', '2026-01-27 22:41:39'),
(17, 'login_fallido', 'Intento de login fallido para: erick@apotemaone.com', NULL, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'warning', '2026-01-27 23:29:48'),
(18, 'login_fallido', 'Intento de login fallido para: erick@apotemaone.com', NULL, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'warning', '2026-01-27 23:29:58'),
(19, 'login_fallido', 'Intento de login fallido para: erick@apotemaone.com', NULL, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'warning', '2026-01-27 23:33:44'),
(20, 'login_fallido', 'Intento de login fallido para: erick@apotenaone.com', NULL, '104.28.50.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '/auth/procesar', 'POST', 'warning', '2026-01-27 23:36:42'),
(21, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-01-27 23:38:15'),
(22, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/auth/procesar', 'POST', 'info', '2026-01-28 00:09:21'),
(23, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.164.6', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1 Edg/144.0.0.0', '/auth/procesar', 'POST', 'info', '2026-01-28 01:43:04'),
(24, 'logout', 'Usuario erick cerró sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/logout', 'GET', 'info', '2026-01-28 01:43:47'),
(25, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/auth/procesar', 'POST', 'info', '2026-01-28 01:46:17'),
(26, 'logout', 'Usuario erick cerró sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/logout', 'GET', 'info', '2026-01-28 01:46:50'),
(27, 'login_exitoso', 'Usuario erick inició sesión', 3, '187.225.135.108', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-01-28 03:33:30'),
(28, 'login_exitoso', 'Usuario erick inició sesión', 3, '187.225.135.108', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-01-28 04:02:15'),
(29, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/auth/procesar', 'POST', 'info', '2026-01-28 19:05:36'),
(30, 'logout', 'Usuario erick cerró sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/logout', 'GET', 'info', '2026-01-28 19:28:12'),
(31, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/auth/procesar', 'POST', 'info', '2026-01-28 20:14:27'),
(32, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/auth/procesar', 'POST', 'info', '2026-01-28 22:00:35'),
(33, 'login_fallido', 'Intento de login fallido para: ', NULL, '52.167.144.225', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', '/auth/procesar', 'GET', 'warning', '2026-01-28 22:13:33'),
(34, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/auth/procesar', 'POST', 'info', '2026-01-28 22:23:35'),
(35, 'usuario_eliminado', 'Usuario ID: 2 eliminado', 2, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/usuarios/eliminar/2', 'POST', 'warning', '2026-01-28 22:38:21'),
(36, 'usuario_creado', 'Usuario amargarita creado', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/usuarios/guardar', 'POST', 'info', '2026-01-28 22:39:30'),
(37, 'usuario_creado', 'Usuario utorres creado', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/usuarios/guardar', 'POST', 'info', '2026-01-28 22:45:09'),
(38, 'email_error', 'Error al enviar email a: ulises.torres@porta.com.mx - Error: SMTP Error: Could not connect to SMTP host. Failed to connect to server SMTP server error: Failed to connect to server SMTP code: 101 Additional SMTP info: Network is unreachable', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/usuarios/guardar', 'POST', 'error', '2026-01-28 22:47:20'),
(39, 'usuario_eliminado', 'Usuario ID: 5 eliminado', 5, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/usuarios/eliminar/5', 'POST', 'warning', '2026-01-28 22:51:10'),
(40, 'usuario_eliminado', 'Usuario ID: 4 eliminado', 4, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/usuarios/eliminar/4', 'POST', 'warning', '2026-01-28 22:51:14'),
(41, 'usuario_creado', 'Usuario utorres creado', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/usuarios/guardar', 'POST', 'info', '2026-01-28 23:04:17'),
(42, 'usuario_creado', 'Usuario asanchez creado', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/usuarios/guardar', 'POST', 'info', '2026-01-28 23:04:50'),
(43, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.164.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '/auth/procesar', 'POST', 'info', '2026-01-28 23:46:05'),
(44, 'login_exitoso', 'Usuario utorres inició sesión', 8, '189.147.19.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-01-29 17:14:06'),
(45, 'password_cambiado', 'Contraseña cambiada para usuario ID: 8', 8, '189.147.19.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '/cambiar-password', 'POST', 'info', '2026-01-29 17:15:16'),
(46, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '187.190.193.173', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-01-30 19:41:15'),
(47, 'login_fallido', 'Intento de login fallido para: ', NULL, '187.251.99.38', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '/auth/procesar', 'GET', 'warning', '2026-01-30 19:41:17'),
(48, 'login_fallido', 'Intento de login fallido para: ', NULL, '52.167.144.150', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', '/auth/procesar', 'GET', 'warning', '2026-03-07 15:36:50'),
(49, 'login_exitoso', 'Usuario utorres inició sesión', 8, '187.168.65.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-24 17:21:34'),
(50, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-24 22:31:08'),
(51, 'login_exitoso', 'Usuario utorres inició sesión', 8, '187.168.65.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-24 22:36:39'),
(52, 'password_cambiado', 'Contraseña cambiada para usuario ID: 9', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/cambiar-password', 'POST', 'info', '2026-03-24 22:41:35'),
(53, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-25 14:59:12'),
(54, 'login_exitoso', 'Usuario utorres inició sesión', 8, '187.168.65.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-25 15:17:33'),
(55, 'producto_eliminado', 'Producto ID: 10', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/eliminar/10', 'POST', 'info', '2026-03-25 15:30:33'),
(56, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-25 17:41:46'),
(57, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-26 21:28:32'),
(58, 'producto_eliminado', 'Producto ID: 8', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/eliminar/8', 'POST', 'info', '2026-03-26 21:28:58'),
(59, 'producto_eliminado', 'Producto ID: 12', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/eliminar/12', 'POST', 'info', '2026-03-26 21:53:26'),
(60, 'producto_eliminado', 'Producto ID: 11', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/eliminar/11', 'POST', 'info', '2026-03-26 21:53:32'),
(61, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 21:57:07'),
(62, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 21:57:08'),
(63, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 21:58:01'),
(64, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 21:58:39'),
(65, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 21:59:06'),
(66, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 21:59:29'),
(67, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 22:00:30'),
(68, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.168.252', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-26 22:06:10'),
(69, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 3, '189.236.168.252', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 22:08:02'),
(70, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 22:14:48'),
(71, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 22:23:01'),
(72, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 22:23:28'),
(73, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 22:23:53'),
(74, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 22:24:15'),
(75, 'producto_eliminado', 'Producto ID: 26', 3, '189.236.168.252', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/eliminar/26', 'POST', 'info', '2026-03-26 22:55:05'),
(76, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-26 23:06:52'),
(77, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '187.189.93.227', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-27 14:55:31'),
(78, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '187.189.93.227', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-27 15:53:44'),
(79, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '187.189.93.227', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-27 16:53:33'),
(80, 'login_exitoso', 'Usuario utorres inició sesión', 8, '189.147.250.255', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-30 16:45:25'),
(81, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '189.147.250.255', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-30 16:46:29'),
(82, 'login_exitoso', 'Usuario asanchez inició sesión', 9, '189.147.250.255', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-31 16:03:54'),
(83, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.168.252', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-31 16:05:01'),
(84, 'login_exitoso', 'Usuario utorres inició sesión', 8, '189.147.250.255', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-31 16:05:51'),
(85, 'usuario_eliminado', 'Usuario ID: 9 eliminado', 9, '189.236.168.252', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/usuarios/eliminar/9', 'POST', 'warning', '2026-03-31 16:10:28'),
(86, 'logout', 'Usuario asanchez cerró sesión', 9, '189.147.250.255', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/logout', 'GET', 'info', '2026-03-31 16:13:05'),
(87, 'login_fallido', 'Intento de login fallido para: erick@cerelit.com', NULL, '189.147.250.255', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'warning', '2026-03-31 16:13:34'),
(88, 'logout', 'Usuario erick cerró sesión', 3, '189.236.168.252', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/logout', 'GET', 'info', '2026-03-31 16:13:43'),
(89, 'login_fallido', 'Intento de login fallido para: erick@apotemaone.com', NULL, '189.147.250.255', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'warning', '2026-03-31 16:14:18'),
(90, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.147.250.255', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-31 16:15:29'),
(91, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.168.252', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/auth/procesar', 'POST', 'info', '2026-03-31 16:37:39'),
(92, 'login_exitoso', 'Usuario erick inició sesión', 3, '189.236.168.252', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '/auth/procesar', 'POST', 'info', '2026-03-31 23:37:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`id`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrador', 'Perfil con acceso total al sistema', 1, '2026-01-27 16:45:45', '2026-01-27 16:45:45', NULL),
(2, 'Editor', 'Perfil con permisos de edición de contenido', 1, '2026-01-27 16:45:45', '2026-01-27 16:45:45', NULL),
(3, 'Viewer', 'Perfil solo lectura', 1, '2026-01-27 16:45:45', '2026-01-27 16:45:45', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_permisos`
--

CREATE TABLE `perfil_permisos` (
  `id` int(10) UNSIGNED NOT NULL,
  `perfil_id` int(10) UNSIGNED NOT NULL,
  `permiso_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `perfil_permisos`
--

INSERT INTO `perfil_permisos` (`id`, `perfil_id`, `permiso_id`, `created_at`) VALUES
(1, 1, 13, '2026-01-27 16:46:53'),
(2, 1, 10, '2026-01-27 16:46:53'),
(3, 1, 11, '2026-01-27 16:46:53'),
(4, 1, 12, '2026-01-27 16:46:53'),
(5, 1, 9, '2026-01-27 16:46:53'),
(6, 1, 6, '2026-01-27 16:46:53'),
(7, 1, 7, '2026-01-27 16:46:53'),
(8, 1, 8, '2026-01-27 16:46:53'),
(9, 1, 5, '2026-01-27 16:46:53'),
(10, 1, 2, '2026-01-27 16:46:53'),
(11, 1, 3, '2026-01-27 16:46:53'),
(12, 1, 4, '2026-01-27 16:46:53'),
(13, 1, 1, '2026-01-27 16:46:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(10) UNSIGNED NOT NULL,
  `clave` varchar(100) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `clave`, `nombre`, `modulo`, `descripcion`, `created_at`) VALUES
(1, 'usuarios.ver', 'Ver usuarios', 'usuarios', 'Ver listado de usuarios', '2026-01-27 16:46:53'),
(2, 'usuarios.crear', 'Crear usuarios', 'usuarios', 'Crear nuevos usuarios', '2026-01-27 16:46:53'),
(3, 'usuarios.editar', 'Editar usuarios', 'usuarios', 'Editar usuarios existentes', '2026-01-27 16:46:53'),
(4, 'usuarios.eliminar', 'Eliminar usuarios', 'usuarios', 'Eliminar usuarios', '2026-01-27 16:46:53'),
(5, 'productos.ver', 'Ver productos', 'productos', 'Ver catálogo de productos', '2026-01-27 16:46:53'),
(6, 'productos.crear', 'Crear productos', 'productos', 'Crear nuevos productos', '2026-01-27 16:46:53'),
(7, 'productos.editar', 'Editar productos', 'productos', 'Editar productos', '2026-01-27 16:46:53'),
(8, 'productos.eliminar', 'Eliminar productos', 'productos', 'Eliminar productos', '2026-01-27 16:46:53'),
(9, 'categorias.ver', 'Ver categorías', 'categorias', 'Ver categorías de productos', '2026-01-27 16:46:53'),
(10, 'categorias.crear', 'Crear categorías', 'categorias', 'Crear nuevas categorías', '2026-01-27 16:46:53'),
(11, 'categorias.editar', 'Editar categorías', 'categorias', 'Editar categorías', '2026-01-27 16:46:53'),
(12, 'categorias.eliminar', 'Eliminar categorías', 'categorias', 'Eliminar categorías', '2026-01-27 16:46:53'),
(13, 'admin.acceso', 'Acceso admin', 'admin', 'Acceso al panel administrativo', '2026-01-27 16:46:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_catalogo`
--

CREATE TABLE `productos_catalogo` (
  `id` int(10) UNSIGNED NOT NULL,
  `categoria_id` int(10) UNSIGNED NOT NULL,
  `familia_id` int(10) UNSIGNED DEFAULT NULL,
  `nombre` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `descripcion_corta` varchar(500) DEFAULT NULL,
  `imagen_principal` varchar(255) DEFAULT NULL,
  `precio_referencia` decimal(10,2) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `caracteristicas` text DEFAULT NULL,
  `instrucciones_cuidado` text DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `nuevo` tinyint(1) NOT NULL DEFAULT 0,
  `vistas` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `rating` decimal(3,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos_catalogo`
--

INSERT INTO `productos_catalogo` (`id`, `categoria_id`, `familia_id`, `nombre`, `slug`, `descripcion`, `descripcion_corta`, `imagen_principal`, `precio_referencia`, `sku`, `marca`, `caracteristicas`, `instrucciones_cuidado`, `orden`, `activo`, `destacado`, `nuevo`, `vistas`, `rating`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'DrawerFit Expandable Spice Organizer', 'drawerfit-spice-organizer-deleted-1', NULL, 'Organizador expandible para especias', NULL, 39.99, 'DRW-001', NULL, NULL, NULL, 0, 1, 1, 0, 0, 4.70, '2026-01-27 16:40:10', '2026-03-26 22:52:21', '2026-01-27 16:58:04'),
(2, 1, 1, 'Kitchen Drawer Divider Set', 'kitchen-drawer-divider-deleted-2', NULL, 'Set de divisores ajustables', NULL, 29.99, 'DRW-002', NULL, NULL, NULL, 0, 1, 1, 0, 0, 4.50, '2026-01-27 16:40:10', '2026-03-26 22:52:21', '2026-01-27 16:58:07'),
(3, 2, 2, 'Water Bottle Storage Rack', 'water-bottle-rack-deleted-3', NULL, 'Organizador de botellas', 'img/productos/placeholder.png', 24.99, 'HYD-001', NULL, NULL, NULL, 0, 1, 1, 0, 0, 4.80, '2026-01-27 16:40:10', '2026-03-26 22:52:21', '2026-01-27 17:13:06'),
(4, 3, NULL, 'Under Sink Organizer', 'under-sink-organizer-deleted-4', NULL, 'Organizador bajo fregadero', 'img/productos/placeholder.png', 34.99, 'CLN-001', NULL, NULL, NULL, 0, 1, 1, 0, 0, 4.50, '2026-01-27 16:40:10', '2026-03-26 22:52:21', '2026-01-27 17:13:10'),
(5, 4, 3, 'Food Container Organizer', 'food-container-organizer-deleted-5', NULL, 'Organizador de contenedores', 'img/productos/placeholder.png', 27.99, 'FS-001', NULL, NULL, NULL, 0, 1, 1, 0, 0, 4.90, '2026-01-27 16:40:10', '2026-03-26 22:52:21', '2026-01-27 17:13:13'),
(6, 7, 4, 'Botella Infantil Muuk 500ml', 'botella-infantil-muuk-500ml', 'KJDNCAIDSNCJDSANCOIDSANCADSNVOIDSAVNADS', 'Botella térmica infantil 500ml. Diseño divertido y seguro.', 'uploads_privados/productos/productos_69790a42ac886.png', NULL, '', '', NULL, NULL, 0, 1, 1, 1, 145, 0.00, '2026-01-27 17:21:41', '2026-04-01 00:47:12', NULL),
(7, 7, 6, 'Botella Nuuk 2 L', 'botella-nuuk-2-l', 'descripcion botella nuuk', 'Botella Nuuk 2 L', 'uploads_privados/productos/productos_69c6b7769b05d.png', NULL, '', 'ekuora', NULL, NULL, 0, 1, 1, 1, 39, 0.00, '2026-01-28 22:37:13', '2026-03-31 23:37:16', NULL),
(8, 8, 10, 'prueba calidad 400 px', 'prueba-calidad-400-px-deleted-8', 'Contenedor cuadrado de plástico (PP) con capacidad de 500 ml, en color traslúcido natural. Incluye tapa con broches de plástico (PP) en color azul sólido y un o-ring de plástico flexible en color naranja que garantiza la hermeticidad.', '', 'uploads_privados/productos/productos_69c314b00f3dc.jpg', NULL, '', '', NULL, NULL, 0, 1, 0, 0, 10, 0.00, '2026-03-24 22:45:18', '2026-03-26 22:52:21', '2026-03-26 21:28:58'),
(9, 8, 10, 'Contenedor Cube Chico 350 ml', 'contenedor-cube-chico-350-ml', 'Contenedor cuadrado de plástico con capacidad de 350 ml\r\n-Tapa con broches de plástico para un cierre seguro\r\n-Sello antiderrames que garantiza la hermeticidad\r\n-Diseño ultra apilable para optimizar el espacio\r\n-Desarmable para facilitar su limpieza\r\n-Libre de BPA\r\n-Apto para lavavajillas\r\n-Seguro para uso en horno de microondas', 'Contenedor Cube Chico 630 ml', 'uploads_privados/productos/productos_69c6b64c08770.jpg', NULL, '', 'ekuora', NULL, NULL, 0, 1, 0, 1, 38, 0.00, '2026-03-25 15:01:29', '2026-04-07 10:47:20', NULL),
(10, 8, 10, 'Contenedor Cube Chico 500 ml_800', 'contenedor-cube-chico-500-ml-800-deleted-10', '', '', 'uploads_privados/productos/productos_69c3fef0e2b7c.jpg', NULL, '', '', NULL, NULL, 0, 1, 0, 0, 3, 0.00, '2026-03-25 15:27:44', '2026-03-26 22:52:21', '2026-03-25 15:30:33'),
(11, 8, 10, 'Contenedor Cube Chico 650 ml', 'contenedor-cube-chico-650-ml-deleted-11', '', 'Contenedor cuadrado de plástico (PP) con capacidad de 650 ml, en color traslúcido natural. Incluye tapa con broches de plástico (PP) en color azul sólido y un o-ring de plástico flexible en color naranja que garantiza la hermeticidad.', 'uploads_privados/productos/productos_69c5a7d1e7022.jpg', NULL, '', '', NULL, NULL, 0, 1, 0, 0, 1, 0.00, '2026-03-26 21:33:39', '2026-03-26 22:52:21', '2026-03-26 21:53:32'),
(12, 8, 10, 'Contenedor Cube Chico 500 ml', 'contenedor-cube-chico-500-ml-deleted-12', '', 'Contenedor cuadrado de plástico (PP) con capacidad de 500 ml, en color traslúcido natural. Incluye tapa con broches de plástico (PP) en color azul sólido y un o-ring de plástico flexible en color naranja que garantiza la hermeticidad.', 'uploads_privados/productos/productos_69c5aa76cae5a.jpg', NULL, '', '', NULL, NULL, 0, 1, 0, 0, 0, 0.00, '2026-03-26 21:51:50', '2026-03-26 22:52:21', '2026-03-26 21:53:26'),
(26, 8, 10, 'Cube Chico 500 ml', 'cube-chico-500-ml-1-deleted-26', '', 'Contenedor cuadrado de plástico (PP) con capacidad de 500 ml, en color traslúcido natural. Incluye tapa con broches de plástico (PP) en color azul sólido y un o-ring de plástico flexible en color naranja que garantiza la hermeticidad.', 'uploads_privados/productos/productos_69c5b2f691ea1.jpg', NULL, '', '', NULL, NULL, 0, 1, 0, 0, 2, 0.00, '2026-03-26 22:24:37', '2026-03-26 22:55:05', '2026-03-26 22:55:05'),
(27, 8, 10, 'Contenedor Cube Chico 500  ml', 'contenedor-cube-chico-500-ml', '', 'Contenedor Cube Chico 500 ml', 'uploads_privados/productos/productos_69c69b7ed88cd.jpg', NULL, '', 'ekuora', NULL, NULL, 0, 1, 0, 1, 8, 0.00, '2026-03-26 22:55:24', '2026-04-07 15:16:57', NULL),
(28, 8, 10, 'Contenedor Cube Chico 650 ml', 'contenedor-cube-chico-650-ml', '', 'Contenedor Cube Chico 650 ml', 'uploads_privados/productos/productos_69c69c0ebc976.jpg', NULL, '', 'ekuora', NULL, NULL, 0, 1, 0, 1, 8, 0.00, '2026-03-27 15:02:38', '2026-04-07 10:00:29', NULL),
(29, 8, 10, 'Contenedor Cube Mediano 650 ml', 'contenedor-cube-mediano-650-ml', '', 'Contenedor Cube Mediano 650 ml', 'uploads_privados/productos/productos_69c69ca91fa45.jpg', NULL, '', 'ekuora', NULL, NULL, 0, 1, 0, 1, 7, 0.00, '2026-03-27 15:03:46', '2026-04-07 15:22:06', NULL),
(30, 8, 10, 'Contenedor Cube Mediano 850 ml', 'contenedor-cube-mediano-850-ml', '', 'Contenedor Cube Mediano 850 ml', 'uploads_privados/productos/productos_69c69e1b515e4.jpg', NULL, '', 'ekuora', NULL, NULL, 0, 1, 0, 1, 5, 0.00, '2026-03-27 15:10:05', '2026-04-07 08:36:15', NULL),
(31, 8, 10, 'Contenedor Cube Mediano 1100 ml', 'contenedor-cube-mediano-1100-ml', '', 'Contenedor Cube Mediano 1100 ml', 'uploads_privados/productos/productos_69c69e7dc51b0.jpg', NULL, '', 'ekuora', NULL, NULL, 0, 1, 0, 1, 8, 0.00, '2026-03-27 15:13:01', '2026-04-07 01:40:46', NULL),
(32, 8, 10, 'Set Contenedores Cube Chicos 350 ml, 500 ml y 650 ml', 'set-contenedores-cube-chicos-350-ml-500-ml-y-650-ml', '', 'Set de 3 Contenedores herméticos (350 ml, 500 ml y 650 ml)', 'uploads_privados/productos/productos_69c6a3e4831f4.jpg', NULL, '', 'ekuora', NULL, NULL, 0, 1, 1, 1, 21, 0.00, '2026-03-27 15:31:45', '2026-04-06 18:01:33', NULL),
(33, 8, 10, 'Set Contenedores Cube Medianos 650 ml, 850 ml y 1100 ml', 'set-contenedores-cube-medianos-650-ml-850-ml-y-1100-ml', '', 'Set de 3 Contenedores herméticos (650 ml, 850 ml y 1100 ml)', 'uploads_privados/productos/productos_69c6a5f08ae30.jpg', NULL, '', 'ekuora', NULL, NULL, 0, 1, 1, 1, 14, 0.00, '2026-03-27 15:38:17', '2026-04-07 14:19:45', NULL),
(34, 8, 10, 'Contenedores Cube medianos', 'contenedores-cube-medianos', '', '', 'uploads_privados/productos/productos_69cbf22330955.jpg', NULL, '', 'ekuora', NULL, NULL, 0, 1, 0, 0, 5, 0.00, '2026-03-31 16:11:15', '2026-04-07 07:11:02', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rate_limits`
--

CREATE TABLE `rate_limits` (
  `id` int(10) UNSIGNED NOT NULL,
  `identificador` varchar(100) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rate_limits`
--

INSERT INTO `rate_limits` (`id`, `identificador`, `accion`, `ip`, `created_at`) VALUES
(9, 'erick@apotenaone.com', 'login', '104.28.50.22', '2026-01-27 23:36:42'),
(25, '', 'login', '52.167.144.150', '2026-03-07 15:36:50'),
(43, 'erick@cerelit.com', 'login', '189.147.250.255', '2026-03-31 16:13:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `perfil_id` int(10) UNSIGNED DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` varchar(50) NOT NULL DEFAULT 'usuario',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `debe_cambiar_password` tinyint(1) NOT NULL DEFAULT 1,
  `intentos_fallidos` int(11) NOT NULL DEFAULT 0,
  `bloqueado_hasta` timestamp NULL DEFAULT NULL,
  `token_recuperacion` varchar(100) DEFAULT NULL,
  `token_expira` timestamp NULL DEFAULT NULL,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `perfil_id`, `username`, `email`, `password_hash`, `nombre`, `apellido`, `telefono`, `rol`, `activo`, `debe_cambiar_password`, `intentos_fallidos`, `bloqueado_hasta`, `token_recuperacion`, `token_expira`, `ultimo_acceso`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'admin', 'admin@ekuora.com', '$2y$10$nNYi.cI9fISerM0i.cAqZeISVb5wDJiVZKsj98eN2Lxwy2Z9wlquO', 'Administrador', NULL, NULL, 'admin', 1, 0, 0, NULL, NULL, NULL, '2026-01-27 20:55:31', '2026-01-27 16:43:47', '2026-01-27 20:55:31', NULL),
(2, 1, '', 'erick@apotema.com', '$2y$10$Njwh85Xksh4uObmLnPl5pOYl0yYPW3rqOJcSVYW/22BdUxNbg84Hi', 'Erick Cruz', NULL, NULL, 'usuario', 1, 0, 1, NULL, NULL, NULL, '2026-01-27 20:56:03', '2026-01-27 16:45:45', '2026-01-28 22:38:21', '2026-01-28 22:38:21'),
(3, 1, 'erick', 'erick@apotemaone.com', '$2y$12$yDtOKYv3Fnp/o/AheQzxCOnKUs.ZyvdoHuZ52OdV61cL67B17IA9G', 'Erick', NULL, NULL, 'admin', 1, 0, 0, NULL, NULL, NULL, '2026-03-31 23:37:36', '2026-01-27 23:33:33', '2026-03-31 23:37:36', NULL),
(8, 1, 'utorres', 'ulises.torres@porta.com.mx', '$2y$12$8AxXjlzSHWTWgCAFdMK6NunPMyKEOEa0nelT0JxdzGeR1DgEDEwzK', 'Ulises Torres', NULL, NULL, 'usuario', 1, 0, 0, NULL, NULL, NULL, '2026-03-31 16:05:51', '2026-01-28 23:04:17', '2026-03-31 16:05:51', NULL),
(9, 1, 'asanchez', 'adriana.sanchez@porta.com.mx', '$2y$12$mATFsSCT0GubTJ6buOFEw.DD9X1imoFXIkNITls6V8CX5lGKTQAj2', 'Adriana Margarita Sanchez', NULL, NULL, 'usuario', 1, 0, 0, NULL, NULL, NULL, '2026-03-31 16:03:54', '2026-01-28 23:04:50', '2026-03-31 16:10:28', '2026-03-31 16:10:28');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_clave` (`clave`);

--
-- Indices de la tabla `banners_home`
--
ALTER TABLE `banners_home`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias_productos`
--
ALTER TABLE `categorias_productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_slug` (`slug`);

--
-- Indices de la tabla `colecciones`
--
ALTER TABLE `colecciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_slug` (`slug`);

--
-- Indices de la tabla `familias_productos`
--
ALTER TABLE `familias_productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_slug` (`slug`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `logs_seguridad`
--
ALTER TABLE `logs_seguridad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_evento` (`evento`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `perfil_permisos`
--
ALTER TABLE `perfil_permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_perfil_permiso` (`perfil_id`,`permiso_id`),
  ADD KEY `permiso_id` (`permiso_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_clave` (`clave`);

--
-- Indices de la tabla `productos_catalogo`
--
ALTER TABLE `productos_catalogo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_slug` (`slug`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `familia_id` (`familia_id`);

--
-- Indices de la tabla `rate_limits`
--
ALTER TABLE `rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_identificador_accion` (`identificador`,`accion`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_username` (`username`),
  ADD UNIQUE KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT de la tabla `banners_home`
--
ALTER TABLE `banners_home`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `categorias_productos`
--
ALTER TABLE `categorias_productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `colecciones`
--
ALTER TABLE `colecciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `familias_productos`
--
ALTER TABLE `familias_productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `logs_seguridad`
--
ALTER TABLE `logs_seguridad`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `perfil_permisos`
--
ALTER TABLE `perfil_permisos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `productos_catalogo`
--
ALTER TABLE `productos_catalogo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `rate_limits`
--
ALTER TABLE `rate_limits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `familias_productos`
--
ALTER TABLE `familias_productos`
  ADD CONSTRAINT `familias_productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_productos` (`id`);

--
-- Filtros para la tabla `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  ADD CONSTRAINT `imagenes_productos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos_catalogo` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `perfil_permisos`
--
ALTER TABLE `perfil_permisos`
  ADD CONSTRAINT `perfil_permisos_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `perfil_permisos_ibfk_2` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos_catalogo`
--
ALTER TABLE `productos_catalogo`
  ADD CONSTRAINT `productos_catalogo_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_productos` (`id`),
  ADD CONSTRAINT `productos_catalogo_ibfk_2` FOREIGN KEY (`familia_id`) REFERENCES `familias_productos` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
