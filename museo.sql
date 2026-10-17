-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 17-10-2026 a las 19:21:25
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `museo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atributos`
--

CREATE TABLE `atributos` (
  `id` int(11) NOT NULL,
  `denominacion` varchar(100) NOT NULL,
  `tipo_dato` varchar(20) NOT NULL,
  `unidad` varchar(20) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_baja` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `atributos`
--

INSERT INTO `atributos` (`id`, `denominacion`, `tipo_dato`, `unidad`, `fecha_alta`, `fecha_baja`, `fecha_edicion`) VALUES
(1, 'altura', 'texto', NULL, '2026-08-18 12:02:47', '2026-09-17 13:35:15', '2026-09-17 13:35:15'),
(2, 'dia de gracias', 'fecha', NULL, '2026-08-18 12:04:09', '2026-09-14 21:51:06', '2026-09-14 21:51:06'),
(3, '', '', NULL, '2026-08-18 12:12:23', NULL, '2026-09-15 23:22:38'),
(4, 'peso', 'numerico', NULL, '2026-08-21 21:37:16', '2026-09-14 21:52:13', '2026-09-14 21:52:13'),
(5, 'dia de mañana', 'fecha', NULL, '2026-08-24 22:54:20', NULL, '2026-09-14 23:33:47'),
(6, 'peso plumita', 'numerico', NULL, '2026-08-24 23:26:20', NULL, '2026-09-17 11:39:00'),
(7, 'texto', 'texto', NULL, '2026-09-04 14:17:59', NULL, '2026-09-15 23:19:54'),
(8, 'luis', 'texto', NULL, '2026-09-04 14:18:18', NULL, '2026-09-15 23:18:37'),
(9, 'juanp', 'texto', NULL, '2026-09-04 14:24:50', NULL, '2026-09-15 23:18:28'),
(10, 'pergolini', 'texto', NULL, '2026-09-04 14:34:24', NULL, '2026-09-15 23:29:16'),
(11, 'pitagorico', 'numerico', NULL, '2026-09-04 21:21:51', NULL, '2026-09-11 13:58:24'),
(12, 'leon', 'texto', NULL, '2026-09-04 21:25:48', '2026-09-07 23:52:07', '2026-09-07 23:52:07'),
(13, 'diametro', 'numerico', NULL, '2026-09-04 21:55:49', '2026-09-07 23:53:01', '2026-09-07 23:53:01'),
(14, 'ingredientes', 'texto', NULL, '2026-09-04 21:57:44', '2026-09-07 23:56:23', '2026-09-07 23:56:23'),
(15, 'padu', 'texto', NULL, '2026-09-07 11:53:43', '2026-09-07 23:56:42', '2026-09-07 23:56:42'),
(16, 'laravel', 'texto', NULL, '2026-09-07 12:08:18', '2026-09-08 02:33:05', '2026-09-08 02:33:05'),
(17, 'hy6y', 'texto', NULL, '2026-09-07 21:54:36', '2026-09-08 02:55:41', '2026-09-08 02:55:41'),
(18, 'maida', 'texto', NULL, '2026-09-08 12:52:31', '2026-09-10 12:45:28', '2026-09-10 12:45:28'),
(19, 'ancho', 'fecha', NULL, '2026-09-08 14:23:12', NULL, '2026-09-11 12:29:02'),
(20, 'perro', 'numerico', NULL, '2026-09-09 13:48:34', '2026-09-11 11:33:38', '2026-09-11 11:33:38'),
(21, 'juan', 'texto', NULL, '2026-09-09 13:55:08', '2026-09-11 11:35:19', '2026-09-11 11:35:19'),
(22, 'etor', 'texto', NULL, '2026-09-09 13:56:54', '2026-09-11 11:35:26', '2026-09-11 11:35:26'),
(23, 'dicemetro', 'numerico', NULL, '2026-09-09 13:59:53', '2026-09-11 11:35:35', '2026-09-11 11:35:35'),
(24, 'lushi', 'numerico', NULL, '2026-09-09 14:01:15', '2026-09-11 11:35:43', '2026-09-11 11:35:43'),
(25, 'g', 'numerico', NULL, '2026-09-09 14:01:30', '2026-09-11 12:27:54', '2026-09-11 12:27:54'),
(26, 'pesos', 'numerico', NULL, '2026-09-09 14:02:36', '2026-09-11 12:23:49', '2026-09-11 12:23:49'),
(27, 'etores', 'numerico', NULL, '2026-09-09 14:03:47', '2026-09-14 21:59:17', '2026-09-14 21:59:17'),
(28, 'gari', 'archivo', NULL, '2026-09-09 14:04:41', NULL, '2026-09-09 14:04:41'),
(29, 'dimencion x', 'numerico', NULL, '2026-09-09 14:13:12', NULL, '2026-09-09 14:13:12'),
(30, 'dimencion y', 'numerico', NULL, '2026-09-09 14:14:14', NULL, '2026-09-09 14:14:14'),
(31, 'dimencion z', 'numerico', NULL, '2026-09-09 14:14:41', '2026-09-11 12:27:46', '2026-09-11 12:27:46'),
(32, 'pesetas', 'numerico', NULL, '2026-09-09 14:18:23', '2026-09-28 12:56:31', '2026-09-28 12:56:31'),
(33, 'pesitos', 'numerico', NULL, '2026-09-09 14:19:00', '2026-09-28 12:56:27', '2026-09-28 12:56:27'),
(34, 'juanpes', 'texto', NULL, '2026-09-09 14:23:28', '2026-09-25 12:00:10', '2026-09-25 12:00:10'),
(35, 'perreques', 'texto', NULL, '2026-09-09 14:23:50', '2026-09-25 12:00:02', '2026-09-25 12:00:02'),
(36, 'larivel', 'texto', NULL, '2026-09-10 11:02:53', '2026-09-25 11:59:43', '2026-09-25 11:59:43'),
(37, 'gnora', 'texto', NULL, '2026-09-10 11:07:14', NULL, '2026-09-28 13:18:02'),
(38, 'retar', 'numerico', NULL, '2026-09-10 11:17:54', NULL, '2026-09-28 13:16:48'),
(39, 'kasdlkajsd', 'texto', NULL, '2026-09-10 11:18:53', '2026-09-22 11:47:09', '2026-09-22 11:47:09'),
(40, 'holis', 'texto', NULL, '2026-09-10 11:19:29', NULL, '2026-09-28 13:16:46'),
(41, 'pesetotes', 'numerico', NULL, '2026-09-10 11:20:07', '2026-09-22 11:46:07', '2026-09-22 11:46:07'),
(42, 'lksajldkjaslkdjlkasjdlkas', 'numerico', NULL, '2026-09-10 11:20:31', NULL, '2026-09-28 13:16:45'),
(43, 'sdfghjklasdfghjklwertyuiocvbnm', 'numerico', NULL, '2026-09-10 11:20:56', '2026-09-22 11:25:54', '2026-09-22 11:25:54'),
(44, 'Color', 'texto', NULL, '2026-09-11 09:35:03', '2026-09-22 11:25:44', '2026-09-22 11:25:44'),
(45, 'Color', 'texto', NULL, '2026-09-11 09:35:03', NULL, '2026-09-28 13:16:40'),
(46, 'equipuo', 'numerico', NULL, '2026-09-11 13:23:52', NULL, '2026-09-28 13:16:38'),
(47, 'kevin', 'archivo', NULL, '2026-09-14 21:54:54', NULL, '2026-09-28 13:16:14'),
(48, 'joia', 'texto', NULL, '2026-09-19 15:25:05', NULL, '2026-09-28 13:15:30'),
(49, 'hola', 'texto', NULL, '2026-09-19 16:02:34', NULL, '2026-09-28 13:15:08'),
(50, 'gilora', 'numerico', 'mm', '2026-09-28 00:58:27', NULL, '2026-09-28 13:12:26'),
(51, 'niniyo', 'numerico', 'mm', '2026-09-28 00:59:29', NULL, '2026-09-28 13:11:06'),
(52, 'bayito', 'numerico', 'mm', '2026-09-28 01:00:00', NULL, '2026-09-28 13:10:25'),
(53, 'kevinkectuch', 'numerico', 'mm', '2026-09-28 23:30:52', NULL, '2026-09-28 23:30:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas`
--

CREATE TABLE `etiquetas` (
  `id` int(11) NOT NULL,
  `denominacion` varchar(100) NOT NULL,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_baja` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `etiquetas`
--

INSERT INTO `etiquetas` (`id`, `denominacion`, `fecha_alta`, `fecha_baja`, `fecha_edicion`) VALUES
(1, 'diario', '2026-08-09 00:06:46', NULL, '2026-09-15 23:22:12'),
(3, 'arte romano', '2026-08-11 00:59:30', NULL, '2026-09-15 23:22:38'),
(4, 'colección Vivanco ', '2026-08-11 00:59:56', '2026-09-28 22:35:52', '2026-09-28 22:35:52'),
(5, 'paleontologia', '2026-08-11 14:28:53', '2026-09-28 22:35:48', '2026-09-28 22:35:48'),
(6, 'arte abstracto', '2026-08-11 14:29:30', '2026-09-28 22:35:43', '2026-09-28 22:35:43'),
(7, 'arte romani', '2026-08-11 14:29:57', NULL, '2026-09-28 22:53:10'),
(8, 'arte abstracto', '2026-08-18 12:07:11', NULL, '2026-09-28 22:53:10'),
(9, 'suiza', '2026-09-15 21:51:54', NULL, '2026-09-28 22:53:09'),
(10, 'suizo', '2026-09-15 21:52:07', NULL, '2026-09-28 22:53:08'),
(11, 'altura', '2026-09-17 12:15:19', NULL, '2026-09-28 22:53:07'),
(12, 'luis', '2026-09-17 12:22:52', NULL, '2026-09-28 22:53:07'),
(13, 'cnachunflos', '2026-09-17 12:23:02', NULL, '2026-09-28 22:53:07'),
(14, 'kevin', '2026-09-17 13:43:03', NULL, '2026-09-28 22:53:06'),
(15, 'firulais', '2026-09-17 21:34:37', NULL, '2026-09-28 22:53:05'),
(16, 'ferolais', '2026-09-17 21:35:22', NULL, '2026-09-28 22:53:05'),
(17, 'facebook', '2026-09-17 21:35:57', '2026-09-28 22:53:23', '2026-09-28 22:53:23'),
(18, 'ochotecho', '2026-09-19 16:07:43', '2026-09-28 22:53:19', '2026-09-28 22:53:19'),
(19, 'fefi', '2026-09-19 16:08:13', '2026-09-28 22:53:16', '2026-09-28 22:53:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `museos`
--

CREATE TABLE `museos` (
  `id` int(11) NOT NULL,
  `denominacion` varchar(100) NOT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `facebook` varchar(150) DEFAULT NULL,
  `instagram` varchar(150) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_baja` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `museos`
--

INSERT INTO `museos` (`id`, `denominacion`, `direccion`, `telefono`, `email`, `whatsapp`, `facebook`, `instagram`, `fecha_alta`, `fecha_baja`, `fecha_edicion`) VALUES
(1, 'Museo Histórico Municipal', 'Av. San Martín 123, Victoria, Entre Ríos', '03436-420000', 'contacto@museovictoria.gob.ar', '+5493436123456', 'museohistoricovictoria', '@museovictoria', '2026-06-26 23:22:36', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objetos`
--

CREATE TABLE `objetos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `denominacion` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_baja` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objetos`
--

INSERT INTO `objetos` (`id`, `codigo`, `denominacion`, `descripcion`, `id_usuario`, `fecha_alta`, `fecha_baja`, `fecha_edicion`) VALUES
(33, 'AQ87', 'sebas', 'sebas era un gran pintor', 3, '2026-09-28 23:44:52', NULL, '2026-10-12 02:35:03'),
(34, 'AQ87', 'sebas', 'sebas era un gran pintor', 3, '2026-09-28 23:44:52', NULL, '2026-10-12 02:35:01'),
(35, 'ABJ', 'nino', 'Nino era un gato muy guapo y de carton', 3, '2026-09-30 23:53:38', NULL, '2026-10-12 02:34:59'),
(36, '12312312', 'kevin', '', 3, '2026-09-30 23:54:34', NULL, '2026-10-12 02:34:58'),
(37, 'k34', 'mkultra', 'Proyecto mk ultra', 23, '2026-10-01 00:10:51', NULL, '2026-10-12 02:27:26'),
(38, 'HK34', 'felfor', '', 23, '2026-10-01 01:45:52', NULL, '2026-10-12 02:27:25'),
(39, 'ABG12', 'alfa romero', 'romero', 23, '2026-10-01 02:28:57', NULL, '2026-10-12 02:27:25'),
(40, 'kas', 'alfa ro', 'dsasd', 23, '2026-10-01 02:42:28', NULL, '2026-10-12 02:44:30'),
(41, 'A4325', 'guarda pelo', 'guarda pelo de rowina reibenclau', 3, '2026-10-02 13:07:44', NULL, '2026-10-12 02:46:10'),
(42, 'A4325', 'ak-47', 'AK-47 perteneciente a tp2 pack', 3, '2026-10-02 13:11:03', NULL, '2026-10-12 02:46:09'),
(43, 'kas', 'juanpasurita', 'Examen extra opcional', 13, '2026-10-12 02:23:23', NULL, '2026-10-13 13:13:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objeto_atributos`
--

CREATE TABLE `objeto_atributos` (
  `id` int(11) NOT NULL,
  `objeto_id` int(11) DEFAULT NULL,
  `atributo_id` int(11) DEFAULT NULL,
  `valor` varchar(255) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objeto_etiquetas`
--

CREATE TABLE `objeto_etiquetas` (
  `id` int(11) NOT NULL,
  `objeto_id` int(11) DEFAULT NULL,
  `etiqueta_id` int(11) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objeto_etiquetas`
--

INSERT INTO `objeto_etiquetas` (`id`, `objeto_id`, `etiqueta_id`, `fecha_alta`, `fecha_baja`, `fecha_edicion`) VALUES
(33, 34, 1, '2026-09-28 23:44:52', '2026-10-01 01:19:58', '2026-10-01 01:19:58'),
(34, 34, 13, '2026-09-28 23:44:52', '2026-10-01 01:19:58', '2026-10-01 01:19:58'),
(35, 34, 3, '2026-09-28 23:44:52', '2026-10-01 01:19:58', '2026-10-01 01:19:58'),
(36, 34, 7, '2026-09-28 23:44:52', '2026-10-01 01:19:58', '2026-10-01 01:19:58'),
(37, 34, 8, '2026-09-28 23:44:52', '2026-10-01 01:19:58', '2026-10-01 01:19:58'),
(38, 40, 13, '2026-10-01 02:42:28', '2026-10-01 02:52:46', '2026-10-01 02:52:46'),
(39, 40, 1, '2026-10-01 02:42:28', '2026-10-01 02:52:46', '2026-10-01 02:52:46'),
(40, 40, 16, '2026-10-01 02:42:28', '2026-10-01 02:52:46', '2026-10-01 02:52:46'),
(41, 40, 15, '2026-10-01 02:42:28', '2026-10-01 02:52:46', '2026-10-01 02:52:46'),
(42, 40, 13, '2026-10-01 02:52:46', '2026-10-01 22:15:11', '2026-10-01 22:15:11'),
(43, 40, 1, '2026-10-01 02:52:46', '2026-10-01 22:15:11', '2026-10-01 22:15:11'),
(44, 40, 16, '2026-10-01 02:52:46', '2026-10-01 22:15:11', '2026-10-01 22:15:11'),
(45, 40, 15, '2026-10-01 02:52:46', '2026-10-01 22:15:11', '2026-10-01 22:15:11'),
(46, 38, 13, '2026-10-01 02:53:02', NULL, '2026-10-01 02:53:02'),
(47, 38, 1, '2026-10-01 02:53:02', NULL, '2026-10-01 02:53:02'),
(48, 38, 16, '2026-10-01 02:53:02', NULL, '2026-10-01 02:53:02'),
(49, 38, 15, '2026-10-01 02:53:02', NULL, '2026-10-01 02:53:02'),
(50, 38, 14, '2026-10-01 02:53:02', NULL, '2026-10-01 02:53:02'),
(51, 38, 12, '2026-10-01 02:53:02', NULL, '2026-10-01 02:53:02'),
(52, 38, 9, '2026-10-01 02:53:02', NULL, '2026-10-01 02:53:02'),
(53, 39, 16, '2026-10-01 02:53:18', NULL, '2026-10-01 02:53:18'),
(54, 39, 15, '2026-10-01 02:53:18', NULL, '2026-10-01 02:53:18'),
(55, 39, 14, '2026-10-01 02:53:18', NULL, '2026-10-01 02:53:18'),
(56, 39, 12, '2026-10-01 02:53:18', NULL, '2026-10-01 02:53:18'),
(57, 39, 9, '2026-10-01 02:53:18', NULL, '2026-10-01 02:53:18'),
(58, 39, 10, '2026-10-01 02:53:18', NULL, '2026-10-01 02:53:18'),
(59, 36, 16, '2026-10-01 02:54:06', NULL, '2026-10-01 02:54:06'),
(60, 36, 15, '2026-10-01 02:54:06', NULL, '2026-10-01 02:54:06'),
(61, 36, 14, '2026-10-01 02:54:06', NULL, '2026-10-01 02:54:06'),
(62, 36, 12, '2026-10-01 02:54:06', NULL, '2026-10-01 02:54:06'),
(63, 36, 9, '2026-10-01 02:54:06', NULL, '2026-10-01 02:54:06'),
(64, 36, 10, '2026-10-01 02:54:06', NULL, '2026-10-01 02:54:06'),
(65, 40, 13, '2026-10-01 22:15:11', NULL, '2026-10-01 22:15:11'),
(66, 40, 1, '2026-10-01 22:15:11', NULL, '2026-10-01 22:15:11'),
(67, 40, 16, '2026-10-01 22:15:11', NULL, '2026-10-01 22:15:11'),
(68, 40, 15, '2026-10-01 22:15:11', NULL, '2026-10-01 22:15:11'),
(69, 42, 13, '2026-10-02 13:11:03', NULL, '2026-10-02 13:11:03'),
(70, 42, 1, '2026-10-02 13:11:03', NULL, '2026-10-02 13:11:03'),
(71, 42, 16, '2026-10-02 13:11:03', NULL, '2026-10-02 13:11:03'),
(72, 42, 15, '2026-10-02 13:11:03', NULL, '2026-10-02 13:11:03'),
(73, 42, 14, '2026-10-02 13:11:03', NULL, '2026-10-02 13:11:03'),
(74, 42, 12, '2026-10-02 13:11:03', NULL, '2026-10-02 13:11:03'),
(75, 42, 9, '2026-10-02 13:11:03', NULL, '2026-10-02 13:11:03'),
(76, 43, 7, '2026-10-12 02:23:23', '2026-10-13 13:04:20', '2026-10-13 13:04:20'),
(77, 43, 3, '2026-10-12 02:23:23', '2026-10-13 13:04:20', '2026-10-13 13:04:20'),
(78, 43, 13, '2026-10-12 02:23:23', '2026-10-13 13:04:20', '2026-10-13 13:04:20'),
(79, 43, 1, '2026-10-12 02:23:23', '2026-10-13 13:04:20', '2026-10-13 13:04:20'),
(80, 43, 16, '2026-10-12 02:23:23', '2026-10-13 13:04:20', '2026-10-13 13:04:20'),
(81, 43, 15, '2026-10-12 02:23:23', '2026-10-13 13:04:20', '2026-10-13 13:04:20'),
(82, 43, 14, '2026-10-12 02:23:23', '2026-10-13 13:04:20', '2026-10-13 13:04:20'),
(83, 43, 12, '2026-10-12 02:23:23', '2026-10-13 13:04:20', '2026-10-13 13:04:20'),
(84, 43, 9, '2026-10-12 02:23:23', '2026-10-13 13:04:20', '2026-10-13 13:04:20'),
(85, 43, 10, '2026-10-12 02:23:23', '2026-10-13 13:04:20', '2026-10-13 13:04:20'),
(86, 43, 7, '2026-10-13 13:04:20', '2026-10-13 13:04:40', '2026-10-13 13:04:40'),
(87, 43, 3, '2026-10-13 13:04:20', '2026-10-13 13:04:40', '2026-10-13 13:04:40'),
(88, 43, 13, '2026-10-13 13:04:20', '2026-10-13 13:04:40', '2026-10-13 13:04:40'),
(89, 43, 1, '2026-10-13 13:04:20', '2026-10-13 13:04:40', '2026-10-13 13:04:40'),
(90, 43, 16, '2026-10-13 13:04:20', '2026-10-13 13:04:40', '2026-10-13 13:04:40'),
(91, 43, 15, '2026-10-13 13:04:20', '2026-10-13 13:04:40', '2026-10-13 13:04:40'),
(92, 43, 14, '2026-10-13 13:04:20', '2026-10-13 13:04:40', '2026-10-13 13:04:40'),
(93, 43, 12, '2026-10-13 13:04:20', '2026-10-13 13:04:40', '2026-10-13 13:04:40'),
(94, 43, 9, '2026-10-13 13:04:20', '2026-10-13 13:04:40', '2026-10-13 13:04:40'),
(95, 43, 10, '2026-10-13 13:04:20', '2026-10-13 13:04:40', '2026-10-13 13:04:40'),
(96, 43, 7, '2026-10-13 13:04:40', '2026-10-13 13:09:19', '2026-10-13 13:09:19'),
(97, 43, 3, '2026-10-13 13:04:40', '2026-10-13 13:09:19', '2026-10-13 13:09:19'),
(98, 43, 13, '2026-10-13 13:04:40', '2026-10-13 13:09:19', '2026-10-13 13:09:19'),
(99, 43, 1, '2026-10-13 13:04:40', '2026-10-13 13:09:19', '2026-10-13 13:09:19'),
(100, 43, 16, '2026-10-13 13:04:40', '2026-10-13 13:09:19', '2026-10-13 13:09:19'),
(101, 43, 15, '2026-10-13 13:04:40', '2026-10-13 13:09:19', '2026-10-13 13:09:19'),
(102, 43, 14, '2026-10-13 13:04:40', '2026-10-13 13:09:19', '2026-10-13 13:09:19'),
(103, 43, 12, '2026-10-13 13:04:40', '2026-10-13 13:09:19', '2026-10-13 13:09:19'),
(104, 43, 9, '2026-10-13 13:04:40', '2026-10-13 13:09:19', '2026-10-13 13:09:19'),
(105, 43, 10, '2026-10-13 13:04:40', '2026-10-13 13:09:19', '2026-10-13 13:09:19'),
(106, 43, 1, '2026-10-13 13:09:19', '2026-10-13 13:13:22', '2026-10-13 13:13:22'),
(107, 43, 3, '2026-10-13 13:09:19', '2026-10-13 13:13:22', '2026-10-13 13:13:22'),
(108, 43, 7, '2026-10-13 13:09:19', '2026-10-13 13:13:22', '2026-10-13 13:13:22'),
(109, 43, 9, '2026-10-13 13:09:19', '2026-10-13 13:13:22', '2026-10-13 13:13:22'),
(110, 43, 10, '2026-10-13 13:09:19', '2026-10-13 13:13:22', '2026-10-13 13:13:22'),
(111, 43, 12, '2026-10-13 13:09:19', '2026-10-13 13:13:22', '2026-10-13 13:13:22'),
(112, 43, 13, '2026-10-13 13:09:19', '2026-10-13 13:13:22', '2026-10-13 13:13:22'),
(113, 43, 14, '2026-10-13 13:09:19', '2026-10-13 13:13:22', '2026-10-13 13:13:22'),
(114, 43, 15, '2026-10-13 13:09:19', '2026-10-13 13:13:22', '2026-10-13 13:13:22'),
(115, 43, 16, '2026-10-13 13:09:19', '2026-10-13 13:13:22', '2026-10-13 13:13:22'),
(116, 43, 1, '2026-10-13 13:13:22', NULL, '2026-10-13 13:13:22'),
(117, 43, 3, '2026-10-13 13:13:22', NULL, '2026-10-13 13:13:22'),
(118, 43, 7, '2026-10-13 13:13:22', NULL, '2026-10-13 13:13:22'),
(119, 43, 9, '2026-10-13 13:13:22', NULL, '2026-10-13 13:13:22'),
(120, 43, 10, '2026-10-13 13:13:22', NULL, '2026-10-13 13:13:22'),
(121, 43, 12, '2026-10-13 13:13:22', NULL, '2026-10-13 13:13:22'),
(122, 43, 13, '2026-10-13 13:13:22', NULL, '2026-10-13 13:13:22'),
(123, 43, 14, '2026-10-13 13:13:22', NULL, '2026-10-13 13:13:22'),
(124, 43, 15, '2026-10-13 13:13:22', NULL, '2026-10-13 13:13:22'),
(125, 43, 16, '2026-10-13 13:13:22', NULL, '2026-10-13 13:13:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `denominacion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `denominacion`) VALUES
(1, 'administrador'),
(4, 'gerente'),
(3, 'invitado'),
(2, 'operador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_rol` int(10) UNSIGNED NOT NULL,
  `objeto` set('a','b','m','l') NOT NULL,
  `etiqueta` set('a','b','m','l') NOT NULL,
  `atributo` set('a','b','m','l') NOT NULL,
  `usuario` set('a','b','m','l') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol_permiso`
--

INSERT INTO `rol_permiso` (`id`, `id_rol`, `objeto`, `etiqueta`, `atributo`, `usuario`) VALUES
(1, 1, 'a,b,m,l', 'a,b,m,l', 'a,b,m,l', 'a,b,m,l'),
(2, 2, 'a,m,l', 'l', 'l', ''),
(3, 3, 'l', '', '', ''),
(4, 4, 'a,b,m,l', 'a,b,m,l', 'a,b,m,l', 'l');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `denominacion` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_baja` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL,
  `id_rol` int(10) UNSIGNED NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `denominacion`, `email`, `password`, `telefono`, `fecha_alta`, `fecha_baja`, `fecha_edicion`, `id_rol`) VALUES
(1, 'altura', 'luis@gmail.com', '', '5374621872', '2026-08-18 13:27:05', NULL, '2026-10-12 02:05:28', 3),
(2, 'luis2', 'luis@gmail.com', '', '12343523', '2026-08-18 13:28:12', NULL, '2026-08-18 13:28:12', 3),
(3, 'juanp', 'juanp@gmail.com', '$2y$10$F5w36qdEt2i45hfq64vx8uhOI8uvfZBtAx0plsDB4nfzxvCChnkEK', '625735218', '2026-08-18 13:56:57', NULL, '2026-08-18 13:56:57', 1),
(4, 'gj', 'gj@gmail.com', '$2y$10$g8O6E3WJvySECLlFvjzzwuHY9ZWkjZDGBLqcwrisIFpJdk.8Q4Geu', '26384281', '2026-08-21 21:42:00', NULL, '2026-08-21 21:42:00', 3),
(5, 'juanv', 'pedro@gmail.com', '$2y$10$7P/Ap3TDvNx4snu0yD4JA.MhplUVH0p1WYm/Uz15vWsD6R34FKjcS', '123443123412', '2026-08-21 21:45:22', NULL, '2026-08-22 00:07:51', 3),
(6, 'sadsad', 'jsjdjsjd@gmail.com', '$2y$10$ZcdmzYcOUtIi3qvbBPQ19OLB.VXlLjeb3j.RC1Y7tB6gIVqRHbUBG', '123721837621', '2026-08-21 21:49:32', NULL, '2026-08-21 23:47:20', 3),
(7, 'julio', 'julia@gmail.com', '$2y$10$ExIqOqZHmbSl/EVpa19K9.wzZx3X9PiyWaZaucdzvCxWBMCHNPd32', '', '2026-08-21 21:58:26', NULL, '2026-08-21 21:58:26', 3),
(8, 'julianos', 'julianos@gmail.com', '$2y$10$SR.21WNDlE.0HL58weveIeLjFwl2Ogmw6Y4.EnUUD8wts8nHQr/pu', '12343242', '2026-08-21 23:56:55', NULL, '2026-08-21 23:56:55', 3),
(9, 'juli', 'juli@gmail.com', '$2y$10$E5seaW4uvKJO.eT4lSAFNuQ01dtATs69YPMyrD453Uidy6DEpYpDW', '1231231', '2026-08-21 23:58:21', NULL, '2026-10-12 02:05:27', 3),
(10, 'godines', 'godines@gmail.com', '$2y$10$hxxePdz3y4IAjO39QPmw/.jv3V2soLIX8tzJ6pyI5uqXsmFZUX0be', '387671278321', '2026-08-24 13:58:00', NULL, '2026-10-12 02:05:26', 3),
(11, 'godines2', 'godines2@gmail.com', '$2y$10$DM35kNDBGjKnYRr/Qi4ycO/9Zay7GrSghEYYJhmTriVs/ZFdh6ENy', '86876872123', '2026-08-24 13:58:33', NULL, '2026-10-12 02:05:25', 3),
(12, 'pericles', 'pericles@gmail.com', '$2y$10$oHoJecP3DrF.ptpBe5tLSOz7t8sGArswjcsC4jfE.UYJ//2EEAYde', '1237126312', '2026-08-24 14:01:59', NULL, '2026-09-28 21:44:12', 3),
(13, 'pepe', 'pepealiaspollo@gmail.com', '$2y$10$WmxGCxiFQPLtM43jGWrBaO7GRbbgpZl6yP6bfO3g6fn0OqnZEUJ/i', '889261938192836', '2026-08-24 14:24:54', NULL, '2026-10-12 02:15:16', 2),
(14, 'lushi', 'lushi@gmail.com', '$2y$10$bj4mf5Rjd2BthSzk.Tndv.etjoIM/o3pYGlR5KeF3Q6fw/SKR9gO6', '98912837123', '2026-08-24 14:25:54', NULL, '2026-09-28 11:22:42', 3),
(15, 'jk', 'jk@gmail.com', '$2y$10$V/sPCpEiGbBypCtNPsAWTuhdi6JwEGvS5Li4Q78hjDq8KJfEurT6m', '21312321', '2026-08-24 14:27:08', NULL, '2026-09-28 21:44:09', 3),
(16, 'kevin', 'luis@gmail.com', '$2y$10$ZDdW0QTFYZGCYcN9dmi8G.xT5lPOia3SdVVP71SKZzTQB.6W2TX1a', '213123123', '2026-08-24 23:03:28', NULL, '2026-09-28 21:44:08', 3),
(17, 'joaquin', 'jkking@gmail.com', '$2y$10$UNZkSuJvat6wlyZ44iWWOeY/zD5iRE8YrcRBLH.cttP3yFoHmP762', '210930129039129', '2026-09-21 17:02:24', NULL, '2026-09-28 21:44:08', 3),
(18, 'joaquina', 'jkking@gmail.com', '$2y$10$7v71sPAq2qRNabClWcAOyOiJwWIhACfhzNipFI.GTLH6q3HsyKL3G', '210930129039129', '2026-09-21 22:38:32', NULL, '2026-09-28 21:44:07', 3),
(19, 'nati', 'juli@gmail.com', '$2y$10$snf6Qi9xqBv4zgmXh3nAmenWSP5IajEPu9mjyT5FSlQAf6DJby6iu', '1231231', '2026-09-21 22:51:55', NULL, '2026-09-28 21:44:05', 3),
(20, 'nicolas', 'pericles@gmail.com', '$2y$10$3T95VNPENKyx55Bb2lK8kOhcpaGKBMvG7s3pMGnKeJOadPudRBJBS', '1237126312', '2026-09-21 22:53:55', NULL, '2026-09-28 21:44:04', 3),
(21, 'jaimes', 'jaimes@gmail.com', '$2y$10$.ftF6leCDMGwGDooeBhOBu9ZuAwoycnzaZY3ohPF7ZcNB2Yt9wTIa', '987219839183', '2026-09-25 11:55:51', NULL, '2026-09-28 21:44:03', 3),
(22, 'lolipop', 'lolipop@gmail.com', '$2y$10$QAlIUX.xQ0Znlnwb1DzrMeVgAlzAqc/xuiHWiNifBBXl/myL2uR.6', '899273981723', '2026-09-28 12:30:08', NULL, '2026-10-12 01:41:29', 4),
(23, 'lola', 'lolabony@gmail.com', '$2y$10$UBA9DkxJGv0kXBpYM4kW4uWnLhZj.Uy6PQPGKqlWmDir75Sx5j0IC', '432423134', '2026-10-01 00:09:54', NULL, '2026-10-13 13:17:43', 4),
(24, 'edgardolui', 'lui@gmail.com', '$2y$10$nZpKeZR0szbz31BLfsG18.wNX1AyIqTW1z3jemP3hBsQtqQZIAEMC', '123213123', '2026-10-07 02:18:36', NULL, '2026-10-12 01:44:59', 3),
(25, 'eleuteria', 'eleu@gmail.com', '$2y$10$7nroZwXRUlpFq7GvG960H.TcAuJdP5VGMZiLCyikHnav5l5wpfpTG', '123123123', '2026-10-13 11:44:57', NULL, '2026-10-17 17:16:50', 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `atributos`
--
ALTER TABLE `atributos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `museos`
--
ALTER TABLE `museos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `objetos`
--
ALTER TABLE `objetos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `objeto_atributos`
--
ALTER TABLE `objeto_atributos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_objatr_objeto` (`objeto_id`),
  ADD KEY `fk_objatr_atributo` (`atributo_id`);

--
-- Indices de la tabla `objeto_etiquetas`
--
ALTER TABLE `objeto_etiquetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_objetq_objeto` (`objeto_id`),
  ADD KEY `fk_objetq_etiqueta` (`etiqueta_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_roles_denominacion` (`denominacion`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuarios_id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `atributos`
--
ALTER TABLE `atributos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `museos`
--
ALTER TABLE `museos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `objetos`
--
ALTER TABLE `objetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `objeto_atributos`
--
ALTER TABLE `objeto_atributos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `objeto_etiquetas`
--
ALTER TABLE `objeto_etiquetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `objetos`
--
ALTER TABLE `objetos`
  ADD CONSTRAINT `fk_objetos_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `objeto_atributos`
--
ALTER TABLE `objeto_atributos`
  ADD CONSTRAINT `fk_objatr_atributo` FOREIGN KEY (`atributo_id`) REFERENCES `atributos` (`id`),
  ADD CONSTRAINT `fk_objatr_objeto` FOREIGN KEY (`objeto_id`) REFERENCES `objetos` (`id`);

--
-- Filtros para la tabla `objeto_etiquetas`
--
ALTER TABLE `objeto_etiquetas`
  ADD CONSTRAINT `fk_objetq_etiqueta` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`),
  ADD CONSTRAINT `fk_objetq_objeto` FOREIGN KEY (`objeto_id`) REFERENCES `objetos` (`id`);

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `rol_permiso_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
