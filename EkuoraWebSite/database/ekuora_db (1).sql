-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2026 at 03:25 PM
-- Server version: 10.6.24-MariaDB-cll-lve
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ekuora_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ajustes`
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
-- Dumping data for table `ajustes`
--

INSERT INTO `ajustes` (`id`, `clave`, `valor`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'site_title', 'Ekuora - Catálogo', 'Título del sitio', '2026-01-27 17:12:07', '2026-01-27 17:12:07'),
(2, 'contact_email', 'contacto@ekuora.com', 'Email de contacto', '2026-01-27 17:12:07', '2026-01-27 17:12:07'),
(3, 'promo_titulo', 'PROMO TEST', NULL, '2026-01-27 19:53:37', '2026-01-28 04:09:38'),
(4, 'promo_subtitulo', 'test 2', NULL, '2026-01-27 19:53:37', '2026-01-27 19:53:37'),
(5, 'promo_texto_boton', 'test boton', NULL, '2026-01-27 19:53:37', '2026-01-27 20:17:30'),
(6, 'promo_enlace', '', NULL, '2026-01-27 19:53:37', '2026-01-27 19:53:37'),
(7, 'promo_imagen', 'uploads_privados/ajustes/1769644340_promo_imagen test.jpg', NULL, '2026-01-27 19:53:37', '2026-01-28 23:52:20'),
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
(131, 'about_imagen', 'uploads_privados/ajustes/1769644340_about_imagen test.jpg', NULL, '2026-01-28 22:24:27', '2026-01-28 23:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `banners_home`
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
-- Dumping data for table `banners_home`
--

INSERT INTO `banners_home` (`id`, `titulo`, `subtitulo`, `texto_boton`, `enlace`, `seccion`, `imagen`, `orden`, `activo`, `created_at`, `updated_at`) VALUES
(4, 'Ekuora test 1', '', 'EXPLORAR COLECCION', '', 'hero', 'uploads_privados/banners/banner_697aa0c3c59f4.jpg', 0, 1, '2026-01-27 23:42:01', '2026-01-28 23:50:27'),
(5, 'Recomendado test', 'test', 'EXPLORAR COLECCION', '', 'recommend', 'uploads_privados/banners/banner_697aa0d5233e4.jpg', 1, 1, '2026-01-28 04:03:46', '2026-01-28 23:50:45');

-- --------------------------------------------------------

--
-- Table structure for table `categorias_productos`
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
-- Dumping data for table `categorias_productos`
--

INSERT INTO `categorias_productos` (`id`, `nombre`, `slug`, `descripcion`, `imagen`, `icono`, `orden`, `activo`, `destacado`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cook & Bake test', 'cook-bake-test', 'Organizadores para cocinar', NULL, 'bi-fire', 1, 0, 0, '2026-01-27 16:32:22', '2026-01-27 17:16:02', '2026-01-27 17:16:02'),
(2, 'RENAMED_ID_2', 'test-1', 'Organización para botellas', NULL, 'bi-droplet', 3, 1, 1, '2026-01-27 16:32:22', '2026-01-27 22:41:39', '2026-01-27 22:41:39'),
(3, 'Cleanup', 'cleanup', 'Organizadores para limpieza', NULL, 'bi-stars', 3, 0, 0, '2026-01-27 16:32:22', '2026-01-27 17:29:19', '2026-01-27 17:29:19'),
(4, 'Food Storage', 'food-storage', 'Almacenamiento de alimentos', NULL, 'bi-box2', 4, 0, 0, '2026-01-27 16:32:22', '2026-01-27 17:28:18', '2026-01-27 17:28:18'),
(5, 'Stash', 'stash', 'Almacenamiento general', NULL, 'bi-folder', 5, 0, 0, '2026-01-27 16:32:22', '2026-01-27 17:20:23', '2026-01-27 17:20:23'),
(6, 'Fresh for Bath', 'fresh-for-bath', 'Organizadores para el baño', NULL, 'bi-moisture', 6, 0, 0, '2026-01-27 16:32:22', '2026-01-27 17:15:57', '2026-01-27 17:15:57'),
(7, 'Hidratación', 'hidrataci-n', 'descripcion didratacion test', 'uploads_privados/categorias/categorias_697aa01f65f26.jpg', 'bi-box-seam', 1, 1, 1, '2026-01-27 16:53:42', '2026-01-28 23:47:43', NULL),
(8, 'Contenedores', 'contenedores', '', 'uploads_privados/categorias/categorias_69c31bddc5b3a.png', 'bi-box-seam', 4, 1, 1, '2026-01-27 17:28:42', '2026-03-24 23:18:53', NULL),
(9, 'test4', 'test4', '', NULL, 'bi-box-seam', 4, 1, 1, '2026-01-27 19:50:54', '2026-01-27 20:56:26', '2026-01-27 20:56:26'),
(10, 'Organización', 'organizaci-n', 'test', 'uploads_privados/categorias/categorias_697aa02eb61b2.jpg', 'bi-box-seam', 3, 1, 1, '2026-01-27 22:42:16', '2026-01-28 23:47:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `colecciones`
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

-- --------------------------------------------------------

--
-- Table structure for table `familias_productos`
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
-- Dumping data for table `familias_productos`
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
(10, 8, 'Cube', 'cube', 'test Cube', 'uploads_privados/familias/familias_69c31c49b7d0e.png', 1, 0, '2026-01-28 22:33:22', '2026-03-24 23:20:41', NULL),
(11, 8, 'Balance', 'balance', 'test Balance', 'uploads_privados/familias/familias_697aa05ddb85c.jpg', 1, 0, '2026-01-28 22:33:48', '2026-01-28 23:48:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `imagenes_productos`
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
-- Dumping data for table `imagenes_productos`
--

INSERT INTO `imagenes_productos` (`id`, `producto_id`, `ruta`, `alt_text`, `orden`, `created_at`) VALUES
(1, 3, 'img/productos/placeholder.png', 'Water Bottle Storage Rack', 1, '2026-01-27 17:06:24'),
(2, 4, 'img/productos/placeholder.png', 'Under Sink Organizer', 1, '2026-01-27 17:06:24'),
(3, 5, 'img/productos/placeholder.png', 'Food Container Organizer', 1, '2026-01-27 17:06:24'),
(4, 6, 'uploads_privados/productos/productos_69790a42ae771.png', 'Botella Infantil Muuk 500ml', 0, '2026-01-27 18:56:02'),
(5, 6, 'uploads_privados/productos/productos_69790a597055f.png', 'Botella Infantil Muuk 500ml', 0, '2026-01-27 18:56:25'),
(6, 8, 'uploads_privados/productos/productos_69c314b010fea.jpg', 'Contenedor Cube Chico 500 ml', 0, '2026-03-24 22:48:16'),
(7, 8, 'uploads_privados/productos/productos_69c314b011dc8.jpg', 'Contenedor Cube Chico 500 ml', 1, '2026-03-24 22:48:16'),
(8, 8, 'uploads_privados/productos/productos_69c314b012a0a.jpg', 'Contenedor Cube Chico 500 ml', 2, '2026-03-24 22:48:16'),
(9, 9, 'uploads_privados/productos/productos_69c3f8c95078a.jpg', 'Contenedor Cube Chico 500 ml_1500', 0, '2026-03-25 15:01:29'),
(10, 9, 'uploads_privados/productos/productos_69c3f8c951e58.jpg', 'Contenedor Cube Chico 500 ml_1500', 1, '2026-03-25 15:01:29'),
(11, 9, 'uploads_privados/productos/productos_69c3f8c955662.jpg', 'Contenedor Cube Chico 500 ml_1500', 2, '2026-03-25 15:01:29'),
(12, 11, 'uploads_privados/productos/productos_69c5a81a7eb1d.jpg', 'Contenedor Cube Chico 650 ml', 0, '2026-03-26 21:41:46'),
(13, 11, 'uploads_privados/productos/productos_69c5a81a8037b.jpg', 'Contenedor Cube Chico 650 ml', 1, '2026-03-26 21:41:46'),
(14, 11, 'uploads_privados/productos/productos_69c5a81a819ba.jpg', 'Contenedor Cube Chico 650 ml', 2, '2026-03-26 21:41:46');

-- --------------------------------------------------------

--
-- Table structure for table `logs_seguridad`
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
-- Dumping data for table `logs_seguridad`
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
(74, 'error_producto', 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'contenedor-cube-chico-500-ml\' for key \'idx_slug\'', 9, '187.168.65.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/productos/guardar', 'POST', 'info', '2026-03-26 22:24:15');

-- --------------------------------------------------------

--
-- Table structure for table `perfiles`
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
-- Dumping data for table `perfiles`
--

INSERT INTO `perfiles` (`id`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrador', 'Perfil con acceso total al sistema', 1, '2026-01-27 16:45:45', '2026-01-27 16:45:45', NULL),
(2, 'Editor', 'Perfil con permisos de edición de contenido', 1, '2026-01-27 16:45:45', '2026-01-27 16:45:45', NULL),
(3, 'Viewer', 'Perfil solo lectura', 1, '2026-01-27 16:45:45', '2026-01-27 16:45:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `perfil_permisos`
--

CREATE TABLE `perfil_permisos` (
  `id` int(10) UNSIGNED NOT NULL,
  `perfil_id` int(10) UNSIGNED NOT NULL,
  `permiso_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `perfil_permisos`
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
-- Table structure for table `permisos`
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
-- Dumping data for table `permisos`
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
-- Table structure for table `productos_catalogo`
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
-- Dumping data for table `productos_catalogo`
--

INSERT INTO `productos_catalogo` (`id`, `categoria_id`, `familia_id`, `nombre`, `slug`, `descripcion`, `descripcion_corta`, `imagen_principal`, `precio_referencia`, `sku`, `marca`, `caracteristicas`, `instrucciones_cuidado`, `orden`, `activo`, `destacado`, `nuevo`, `vistas`, `rating`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'DrawerFit Expandable Spice Organizer', 'drawerfit-spice-organizer', NULL, 'Organizador expandible para especias', NULL, 39.99, 'DRW-001', NULL, NULL, NULL, 0, 1, 1, 0, 0, 4.70, '2026-01-27 16:40:10', '2026-01-27 16:58:04', '2026-01-27 16:58:04'),
(2, 1, 1, 'Kitchen Drawer Divider Set', 'kitchen-drawer-divider', NULL, 'Set de divisores ajustables', NULL, 29.99, 'DRW-002', NULL, NULL, NULL, 0, 1, 1, 0, 0, 4.50, '2026-01-27 16:40:10', '2026-01-27 16:58:07', '2026-01-27 16:58:07'),
(3, 2, 2, 'Water Bottle Storage Rack', 'water-bottle-rack', NULL, 'Organizador de botellas', 'img/productos/placeholder.png', 24.99, 'HYD-001', NULL, NULL, NULL, 0, 1, 1, 0, 0, 4.80, '2026-01-27 16:40:10', '2026-01-27 17:13:06', '2026-01-27 17:13:06'),
(4, 3, NULL, 'Under Sink Organizer', 'under-sink-organizer', NULL, 'Organizador bajo fregadero', 'img/productos/placeholder.png', 34.99, 'CLN-001', NULL, NULL, NULL, 0, 1, 1, 0, 0, 4.50, '2026-01-27 16:40:10', '2026-01-27 17:13:10', '2026-01-27 17:13:10'),
(5, 4, 3, 'Food Container Organizer', 'food-container-organizer', NULL, 'Organizador de contenedores', 'img/productos/placeholder.png', 27.99, 'FS-001', NULL, NULL, NULL, 0, 1, 1, 0, 0, 4.90, '2026-01-27 16:40:10', '2026-01-27 17:13:13', '2026-01-27 17:13:13'),
(6, 7, 4, 'Botella Infantil Muuk 500ml', 'botella-infantil-muuk-500ml', 'KJDNCAIDSNCJDSANCOIDSANCADSNVOIDSAVNADS', 'Botella térmica infantil 500ml. Diseño divertido y seguro.', 'uploads_privados/productos/productos_69790a42ac886.png', NULL, '', '', NULL, NULL, 0, 1, 1, 1, 128, 0.00, '2026-01-27 17:21:41', '2026-03-24 16:35:50', NULL),
(7, 7, 6, 'Botella Nuuk', 'botella-nuuk', 'descripcion botella nuuk', 'botella nuuk', 'uploads_privados/productos/productos_697aa047913fc.jpg', NULL, '', '', NULL, NULL, 0, 1, 1, 1, 26, 0.00, '2026-01-28 22:37:13', '2026-03-23 06:29:28', NULL),
(8, 8, 10, 'prueba calidad 400 px', 'prueba-calidad-400-px', 'Contenedor cuadrado de plástico (PP) con capacidad de 500 ml, en color traslúcido natural. Incluye tapa con broches de plástico (PP) en color azul sólido y un o-ring de plástico flexible en color naranja que garantiza la hermeticidad.', '', 'uploads_privados/productos/productos_69c314b00f3dc.jpg', NULL, '', '', NULL, NULL, 0, 1, 0, 0, 10, 0.00, '2026-03-24 22:45:18', '2026-03-26 21:28:58', '2026-03-26 21:28:58'),
(9, 8, 10, 'Contenedor Cube Chico 350 ml', 'contenedor-cube-chico-350-ml', '', 'Contenedor cuadrado de plástico (PP) con capacidad de 500 ml, en color traslúcido natural. Incluye tapa con broches de plástico (PP) en color azul sólido y un o-ring de plástico flexible en color naranja que garantiza la hermeticidad.', 'uploads_privados/productos/productos_69c3f8c94e770.jpg', NULL, '', 'ekuora', NULL, NULL, 0, 1, 0, 0, 10, 0.00, '2026-03-25 15:01:29', '2026-03-26 21:44:14', NULL),
(10, 8, 10, 'Contenedor Cube Chico 500 ml_800', 'contenedor-cube-chico-500-ml-800', '', '', 'uploads_privados/productos/productos_69c3fef0e2b7c.jpg', NULL, '', '', NULL, NULL, 0, 1, 0, 0, 3, 0.00, '2026-03-25 15:27:44', '2026-03-25 15:30:33', '2026-03-25 15:30:33'),
(11, 8, 10, 'Contenedor Cube Chico 650 ml', 'contenedor-cube-chico-650-ml', '', 'Contenedor cuadrado de plástico (PP) con capacidad de 650 ml, en color traslúcido natural. Incluye tapa con broches de plástico (PP) en color azul sólido y un o-ring de plástico flexible en color naranja que garantiza la hermeticidad.', 'uploads_privados/productos/productos_69c5a7d1e7022.jpg', NULL, '', '', NULL, NULL, 0, 1, 0, 0, 1, 0.00, '2026-03-26 21:33:39', '2026-03-26 21:53:32', '2026-03-26 21:53:32'),
(12, 8, 10, 'Contenedor Cube Chico 500 ml', 'contenedor-cube-chico-500-ml', '', 'Contenedor cuadrado de plástico (PP) con capacidad de 500 ml, en color traslúcido natural. Incluye tapa con broches de plástico (PP) en color azul sólido y un o-ring de plástico flexible en color naranja que garantiza la hermeticidad.', 'uploads_privados/productos/productos_69c5aa76cae5a.jpg', NULL, '', '', NULL, NULL, 0, 1, 0, 0, 0, 0.00, '2026-03-26 21:51:50', '2026-03-26 21:53:26', '2026-03-26 21:53:26'),
(26, 8, 10, 'Cube Chico 500 ml', 'cube-chico-500-ml', '', '', NULL, NULL, '', '', NULL, NULL, 0, 1, 0, 0, 0, 0.00, '2026-03-26 22:24:37', '2026-03-26 22:24:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rate_limits`
--

CREATE TABLE `rate_limits` (
  `id` int(10) UNSIGNED NOT NULL,
  `identificador` varchar(100) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rate_limits`
--

INSERT INTO `rate_limits` (`id`, `identificador`, `accion`, `ip`, `created_at`) VALUES
(9, 'erick@apotenaone.com', 'login', '104.28.50.22', '2026-01-27 23:36:42'),
(25, '', 'login', '52.167.144.150', '2026-03-07 15:36:50');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
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
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `perfil_id`, `username`, `email`, `password_hash`, `nombre`, `apellido`, `telefono`, `rol`, `activo`, `debe_cambiar_password`, `intentos_fallidos`, `bloqueado_hasta`, `token_recuperacion`, `token_expira`, `ultimo_acceso`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'admin', 'admin@ekuora.com', '$2y$10$nNYi.cI9fISerM0i.cAqZeISVb5wDJiVZKsj98eN2Lxwy2Z9wlquO', 'Administrador', NULL, NULL, 'admin', 1, 0, 0, NULL, NULL, NULL, '2026-01-27 20:55:31', '2026-01-27 16:43:47', '2026-01-27 20:55:31', NULL),
(2, 1, '', 'erick@apotema.com', '$2y$10$Njwh85Xksh4uObmLnPl5pOYl0yYPW3rqOJcSVYW/22BdUxNbg84Hi', 'Erick Cruz', NULL, NULL, 'usuario', 1, 0, 1, NULL, NULL, NULL, '2026-01-27 20:56:03', '2026-01-27 16:45:45', '2026-01-28 22:38:21', '2026-01-28 22:38:21'),
(3, 1, 'erick', 'erick@apotemaone.com', '$2y$12$yDtOKYv3Fnp/o/AheQzxCOnKUs.ZyvdoHuZ52OdV61cL67B17IA9G', 'Erick', NULL, NULL, 'admin', 1, 0, 0, NULL, NULL, NULL, '2026-03-26 22:06:10', '2026-01-27 23:33:33', '2026-03-26 22:06:10', NULL),
(8, 1, 'utorres', 'ulises.torres@porta.com.mx', '$2y$12$8AxXjlzSHWTWgCAFdMK6NunPMyKEOEa0nelT0JxdzGeR1DgEDEwzK', 'Ulises Torres', NULL, NULL, 'usuario', 1, 0, 0, NULL, NULL, NULL, '2026-03-25 15:17:33', '2026-01-28 23:04:17', '2026-03-25 15:17:33', NULL),
(9, 1, 'asanchez', 'adriana.sanchez@porta.com.mx', '$2y$12$mATFsSCT0GubTJ6buOFEw.DD9X1imoFXIkNITls6V8CX5lGKTQAj2', 'Adriana Margarita Sanchez', NULL, NULL, 'usuario', 1, 0, 0, NULL, NULL, NULL, '2026-03-26 21:28:31', '2026-01-28 23:04:50', '2026-03-26 21:28:31', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ajustes`
--
ALTER TABLE `ajustes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_clave` (`clave`);

--
-- Indexes for table `banners_home`
--
ALTER TABLE `banners_home`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorias_productos`
--
ALTER TABLE `categorias_productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_slug` (`slug`);

--
-- Indexes for table `colecciones`
--
ALTER TABLE `colecciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_slug` (`slug`);

--
-- Indexes for table `familias_productos`
--
ALTER TABLE `familias_productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_slug` (`slug`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indexes for table `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indexes for table `logs_seguridad`
--
ALTER TABLE `logs_seguridad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_evento` (`evento`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perfil_permisos`
--
ALTER TABLE `perfil_permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_perfil_permiso` (`perfil_id`,`permiso_id`),
  ADD KEY `permiso_id` (`permiso_id`);

--
-- Indexes for table `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_clave` (`clave`);

--
-- Indexes for table `productos_catalogo`
--
ALTER TABLE `productos_catalogo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_slug` (`slug`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `familia_id` (`familia_id`);

--
-- Indexes for table `rate_limits`
--
ALTER TABLE `rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_identificador_accion` (`identificador`,`accion`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_username` (`username`),
  ADD UNIQUE KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ajustes`
--
ALTER TABLE `ajustes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `banners_home`
--
ALTER TABLE `banners_home`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categorias_productos`
--
ALTER TABLE `categorias_productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `colecciones`
--
ALTER TABLE `colecciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `familias_productos`
--
ALTER TABLE `familias_productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `logs_seguridad`
--
ALTER TABLE `logs_seguridad`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `perfil_permisos`
--
ALTER TABLE `perfil_permisos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `productos_catalogo`
--
ALTER TABLE `productos_catalogo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `rate_limits`
--
ALTER TABLE `rate_limits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `familias_productos`
--
ALTER TABLE `familias_productos`
  ADD CONSTRAINT `familias_productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_productos` (`id`);

--
-- Constraints for table `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  ADD CONSTRAINT `imagenes_productos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos_catalogo` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `perfil_permisos`
--
ALTER TABLE `perfil_permisos`
  ADD CONSTRAINT `perfil_permisos_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `perfil_permisos_ibfk_2` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `productos_catalogo`
--
ALTER TABLE `productos_catalogo`
  ADD CONSTRAINT `productos_catalogo_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_productos` (`id`),
  ADD CONSTRAINT `productos_catalogo_ibfk_2` FOREIGN KEY (`familia_id`) REFERENCES `familias_productos` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
