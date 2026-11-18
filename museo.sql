-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-11-2026 a las 02:13:35
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
(1, 'Material', 'texto', NULL, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(2, 'Época', 'texto', NULL, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(3, 'Alto', 'numerico', 'cm', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(4, 'Ancho', 'numerico', 'cm', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(5, 'Profundidad', 'numerico', 'cm', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(6, 'Peso', 'numerico', 'g', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(7, 'Estado de conservación', 'texto', NULL, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(8, 'Procedencia', 'texto', NULL, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(9, 'Donante', 'texto', NULL, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(10, 'Fecha de ingreso', 'fecha', NULL, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(11, 'Cantidad de páginas', 'numerico', NULL, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(12, 'Autor', 'texto', NULL, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `tabla` enum('objetos','objeto_etiquetas','objeto_atributos','etiquetas','atributos','piezas_imagenes') NOT NULL,
  `accion` enum('a','b','m','r') NOT NULL,
  `id_dato` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `tabla`, `accion`, `id_dato`, `id_usuario`, `fecha`) VALUES
(1, 'objetos', 'a', 1, 3, '2026-11-17 16:23:03'),
(2, 'objetos', 'a', 2, 3, '2026-11-17 16:23:03'),
(3, 'objetos', 'a', 3, 22, '2026-11-17 16:23:03'),
(4, 'objetos', 'a', 4, 22, '2026-11-17 16:23:03'),
(5, 'objetos', 'a', 5, 22, '2026-11-17 16:23:03'),
(6, 'objetos', 'a', 6, 23, '2026-11-17 16:23:03'),
(7, 'objetos', 'a', 7, 23, '2026-11-17 16:23:03'),
(8, 'objetos', 'a', 8, 3, '2026-11-17 16:23:03'),
(9, 'objetos', 'a', 9, 28, '2026-11-17 16:23:03'),
(10, 'objetos', 'a', 10, 23, '2026-11-17 16:23:03'),
(11, 'objetos', 'a', 11, 28, '2026-11-17 16:23:03'),
(12, 'objetos', 'a', 12, 22, '2026-11-17 16:23:03'),
(13, 'objetos', 'a', 13, 3, '2026-11-17 16:23:03'),
(14, 'objetos', 'a', 14, 3, '2026-11-17 16:23:03'),
(15, 'objetos', 'a', 15, 23, '2026-11-17 16:23:03'),
(16, 'objetos', 'a', 16, 22, '2026-11-17 16:23:03'),
(17, 'objetos', 'a', 17, 28, '2026-11-17 16:23:03'),
(18, 'objetos', 'a', 18, 22, '2026-11-17 16:23:03'),
(19, 'objetos', 'b', 18, 3, '2026-11-17 16:24:49'),
(20, 'etiquetas', 'b', 17, 3, '2026-11-17 16:25:17'),
(21, 'objeto_etiquetas', 'a', 19, 3, '2026-11-17 20:55:33'),
(22, 'objeto_atributos', 'a', 19, 3, '2026-11-17 20:55:33'),
(23, 'objetos', 'a', 19, 3, '2026-11-17 20:55:33'),
(24, 'objetos', 'm', 19, 3, '2026-11-17 21:53:34'),
(25, 'objeto_etiquetas', 'm', 19, 3, '2026-11-17 21:53:34'),
(26, 'objeto_atributos', 'm', 19, 3, '2026-11-17 21:53:34');

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
(1, 'pueblos originarios', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(2, 'arqueología', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(3, 'inmigración', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(4, 'vida cotidiana', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(5, 'fotografía', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(6, 'documentos históricos', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(7, 'libros', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(8, 'río y navegación', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(9, 'caleras', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(10, 'siglo XIX', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(11, 'siglo XX', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(12, 'oficios y herramientas', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(13, 'libros prohibidos durante la dictadura', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(14, 'donación vecinal', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(15, 'tecnología y comunicaciones', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(16, 'textiles', '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(17, 'numismática', '2026-11-17 16:23:02', '2026-11-17 19:25:17', '2026-11-17 19:25:17');

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
  `fecha_edicion` datetime DEFAULT NULL,
  `id_ubicacion` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objetos`
--

INSERT INTO `objetos` (`id`, `codigo`, `denominacion`, `descripcion`, `id_usuario`, `fecha_alta`, `fecha_baja`, `fecha_edicion`, `id_ubicacion`) VALUES
(1, 'MHV-0001', 'Punta de flecha de piedra', 'Punta de proyectil tallada en sílex, hallada en la costa del río.', 3, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 8),
(2, 'MHV-0002', 'Boleadoras de piedra', 'Juego de tres bolas de piedra con surco para la cuerda.', 3, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 8),
(3, 'MHV-0003', 'Fragmento de cerámica indígena', 'Fragmento de vasija con decoración incisa.', 22, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 9),
(4, 'MHV-0004', 'Baúl de inmigrante', 'Baúl de madera con herrajes de hierro, traído por una familia europea a fines del siglo XIX.', 22, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 10),
(5, 'MHV-0005', 'Máquina de coser a pedal', 'Máquina de coser con mueble de hierro fundido y pedal, de uso familiar.', 22, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 4),
(6, 'MHV-0006', 'Plancha de hierro a carbón', 'Plancha de hierro con tapa y mango de madera.', 23, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 4),
(7, 'MHV-0007', 'Teléfono de pared a magneto', 'Teléfono de madera con manivela y auricular de baquelita.', 23, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 4),
(8, 'MHV-0008', 'Fotografía del puerto de Victoria', 'Fotografía en blanco y negro de embarcaciones amarradas en el puerto, circa 1920.', 3, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 5),
(9, 'MHV-0009', 'Libro de actas municipal', 'Libro encuadernado con actas de sesiones del Concejo Deliberante.', 28, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 11),
(10, 'MHV-0010', 'Farol de kerosene de embarcación', 'Farol de bronce y vidrio utilizado en navegación fluvial.', 23, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 5),
(11, 'MHV-0011', 'Plano catastral de la ciudad', 'Plano manuscrito en tinta sobre papel entelado.', 28, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 11),
(12, 'MHV-0012', 'Casco de bombero voluntario', 'Casco de cuero con cimera de metal y escudo de la institución.', 22, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 12),
(13, 'MHV-0013', 'Pala de calero', 'Herramienta de hierro y madera usada en el trabajo de las caleras.', 3, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 3),
(14, 'MHV-0014', 'Rebelión en la granja', 'Ejemplar del libro Rebelión en la granja.', 3, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 11),
(15, 'MHV-0015', 'Cámara fotográfica de fuelle', 'Cámara de placas con fuelle de cuero y trípode de madera.', 23, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 4),
(16, 'MHV-0016', 'Radio a válvulas', 'Radio de mueble de madera con dial iluminado.', 22, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 4),
(17, 'MHV-0017', 'Colección de monedas argentinas', 'Conjunto de monedas de distintas épocas en estuche de madera.', 28, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02', 10),
(18, 'MHV-0018', 'Estandarte de club social', 'Estandarte de tela bordada con flecos dorados.', 22, '2026-11-17 16:23:02', '2026-11-17 19:24:49', '2026-11-17 19:24:49', 12),
(19, '123MJ', 'atencio90', 'kevin atencio', 3, '2026-11-17 23:55:33', NULL, '2026-11-18 00:53:34', NULL);

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

--
-- Volcado de datos para la tabla `objeto_atributos`
--

INSERT INTO `objeto_atributos` (`id`, `objeto_id`, `atributo_id`, `valor`, `fecha_alta`, `fecha_baja`, `fecha_edicion`) VALUES
(1, 1, 1, 'Sílex', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(2, 1, 2, 'Prehispánica', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(3, 1, 3, '4.5', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(4, 1, 7, 'Bueno', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(5, 1, 8, 'Costa del río Paraná', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(6, 2, 1, 'Piedra', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(7, 2, 2, 'Prehispánica', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(8, 2, 6, '350', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(9, 2, 7, 'Regular', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(10, 3, 1, 'Arcilla cocida', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(11, 3, 2, 'Prehispánica', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(12, 3, 4, '9', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(13, 3, 7, 'Fragmentado', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(14, 4, 1, 'Madera y hierro', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(15, 4, 2, 'Fines del siglo XIX', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(16, 4, 3, '55', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(17, 4, 4, '90', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(18, 4, 5, '50', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(19, 4, 9, 'Familia local', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(20, 4, 10, '2019-05-12', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(21, 5, 1, 'Hierro fundido y madera', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(22, 5, 2, 'Circa 1910', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(23, 5, 3, '90', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(24, 5, 7, 'Bueno', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(25, 5, 9, 'Familia local', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(26, 6, 1, 'Hierro', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(27, 6, 2, 'Principios del siglo XX', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(28, 6, 6, '3200', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(29, 6, 7, 'Regular', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(30, 7, 1, 'Madera y baquelita', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(31, 7, 2, 'Circa 1930', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(32, 7, 3, '35', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(33, 7, 7, 'Bueno', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(34, 8, 1, 'Papel fotográfico', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(35, 8, 2, 'Circa 1920', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(36, 8, 3, '18', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(37, 8, 4, '24', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(38, 8, 7, 'Bueno', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(39, 8, 12, 'Fotógrafo no identificado', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(40, 9, 1, 'Papel y cuero', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(41, 9, 2, '1925', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(42, 9, 11, '240', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(43, 9, 7, 'Regular', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(44, 9, 8, 'Archivo municipal', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(45, 10, 1, 'Bronce y vidrio', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(46, 10, 2, 'Fines del siglo XIX', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(47, 10, 3, '32', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(48, 10, 7, 'Bueno', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(49, 11, 1, 'Tinta sobre papel entelado', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(50, 11, 2, 'Siglo XIX', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(51, 11, 3, '60', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(52, 11, 4, '85', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(53, 11, 7, 'Frágil', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(54, 12, 1, 'Cuero y metal', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(55, 12, 2, 'Circa 1940', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(56, 12, 7, 'Regular', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(57, 12, 9, 'Cuerpo de Bomberos Voluntarios', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(58, 13, 1, 'Hierro y madera', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(59, 13, 2, 'Siglo XIX', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(60, 13, 3, '130', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(61, 13, 6, '2800', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(62, 13, 8, 'Caleras de la zona', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(63, 14, 1, 'Papel', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(64, 14, 12, 'George Orwell', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(65, 14, 11, '128', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(66, 14, 7, 'Bueno', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(67, 15, 1, 'Madera, cuero y latón', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(68, 15, 2, 'Circa 1900', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(69, 15, 3, '25', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(70, 15, 7, 'Regular', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(71, 16, 1, 'Madera y metal', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(72, 16, 2, 'Circa 1950', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(73, 16, 3, '40', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(74, 16, 4, '55', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(75, 16, 7, 'No funciona', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(76, 17, 1, 'Metal', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(77, 17, 2, 'Siglos XIX y XX', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(78, 17, 7, 'Bueno', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(79, 17, 9, 'Coleccionista local', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(80, 18, 1, 'Tela bordada', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(81, 18, 2, 'Circa 1935', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(82, 18, 3, '120', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(83, 18, 7, 'Frágil', '2026-11-17 16:23:03', NULL, '2026-11-17 16:23:03'),
(84, 19, 11, '123', '2026-11-17 23:55:33', '2026-11-18 00:53:34', '2026-11-18 00:53:34'),
(85, 19, 11, '123', '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34');

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
(1, 1, 1, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(2, 1, 2, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(3, 2, 1, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(4, 2, 2, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(5, 3, 1, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(6, 3, 2, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(7, 4, 3, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(8, 4, 10, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(9, 4, 4, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(10, 4, 14, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(11, 5, 4, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(12, 5, 11, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(13, 5, 16, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(14, 6, 4, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(15, 6, 11, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(16, 7, 4, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(17, 7, 15, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(18, 7, 11, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(19, 8, 5, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(20, 8, 8, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(21, 8, 11, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(22, 8, 6, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(23, 9, 6, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(24, 9, 11, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(25, 10, 8, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(26, 10, 10, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(27, 11, 6, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(28, 11, 10, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(29, 12, 12, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(30, 12, 14, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(31, 12, 11, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(32, 13, 9, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(33, 13, 12, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(34, 13, 10, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(35, 14, 7, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(36, 14, 13, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(37, 15, 5, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(38, 15, 15, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(39, 15, 11, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(40, 16, 15, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(41, 16, 11, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(42, 16, 4, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(43, 16, 14, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(44, 17, 17, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(45, 17, 11, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(46, 18, 14, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(47, 18, 11, '2026-11-17 16:23:02', NULL, '2026-11-17 16:23:02'),
(48, 19, 2, '2026-11-17 23:55:33', '2026-11-18 00:53:34', '2026-11-18 00:53:34'),
(49, 19, 9, '2026-11-17 23:55:33', '2026-11-18 00:53:34', '2026-11-18 00:53:34'),
(50, 19, 6, '2026-11-17 23:55:33', '2026-11-18 00:53:34', '2026-11-18 00:53:34'),
(51, 19, 14, '2026-11-17 23:55:33', '2026-11-18 00:53:34', '2026-11-18 00:53:34'),
(52, 19, 5, '2026-11-17 23:55:33', '2026-11-18 00:53:34', '2026-11-18 00:53:34'),
(53, 19, 2, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(54, 19, 9, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(55, 19, 6, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(56, 19, 14, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(57, 19, 5, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `piezas_imagenes`
--

CREATE TABLE `piezas_imagenes` (
  `id` int(11) NOT NULL,
  `objeto_id` int(11) NOT NULL,
  `nombrearchivo` varchar(255) NOT NULL,
  `portada` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_baja` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `piezas_imagenes`
--

INSERT INTO `piezas_imagenes` (`id`, `objeto_id`, `nombrearchivo`, `portada`, `fecha_alta`, `fecha_baja`, `fecha_edicion`) VALUES
(1, 19, '19_20261117235533_Captura_de_pantalla__178_.png', 1, '2026-11-17 23:55:33', NULL, '2026-11-18 00:53:34'),
(2, 19, '19_20261118005334_Captura_de_pantalla__1_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(3, 19, '19_20261118005334_Captura_de_pantalla__2_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(4, 19, '19_20261118005334_Captura_de_pantalla__3_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(5, 19, '19_20261118005334_Captura_de_pantalla__4_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(6, 19, '19_20261118005334_Captura_de_pantalla__5_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(7, 19, '19_20261118005334_Captura_de_pantalla__6_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(8, 19, '19_20261118005334_Captura_de_pantalla__7_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(9, 19, '19_20261118005334_Captura_de_pantalla__8_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(10, 19, '19_20261118005334_Captura_de_pantalla__9_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(11, 19, '19_20261118005334_Captura_de_pantalla__10_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(12, 19, '19_20261118005334_Captura_de_pantalla__11_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(13, 19, '19_20261118005334_Captura_de_pantalla__12_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(14, 19, '19_20261118005334_Captura_de_pantalla__13_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34'),
(15, 19, '19_20261118005334_Captura_de_pantalla__14_.png', 0, '2026-11-18 00:53:34', NULL, '2026-11-18 00:53:34');

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
  `usuario` set('a','b','m','l') NOT NULL,
  `sala` set('a','b','m','l') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol_permiso`
--

INSERT INTO `rol_permiso` (`id`, `id_rol`, `objeto`, `etiqueta`, `atributo`, `usuario`, `sala`) VALUES
(1, 1, 'a,b,m,l', 'a,b,m,l', 'a,b,m,l', 'a,b,m,l', 'a,b,m,l'),
(2, 2, 'a,m,l', 'l', 'l', '', 'a,m,l'),
(3, 3, 'l', '', '', '', 'l'),
(4, 4, 'a,b,m,l', 'a,b,m,l', 'a,b,m,l', 'l', 'a,b,m,l');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id` int(10) UNSIGNED NOT NULL,
  `denominacion` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `id_pertenece` int(10) UNSIGNED DEFAULT NULL,
  `fecha_alta` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_baja` datetime DEFAULT NULL,
  `fecha_edicion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`id`, `denominacion`, `descripcion`, `id_pertenece`, `fecha_alta`, `fecha_baja`, `fecha_edicion`) VALUES
(1, 'Museo', 'Edificio principal del Museo Histórico Municipal', NULL, '2026-11-17 16:23:02', NULL, NULL),
(2, 'Sala 1 - Pueblos originarios', 'Arqueología y primeros habitantes de la región', 1, '2026-11-17 16:23:02', NULL, NULL),
(3, 'Sala 2 - Fundación e inmigración', 'Origen de la ciudad, llegada de inmigrantes y oficio de la cal', 1, '2026-11-17 16:23:02', NULL, NULL),
(4, 'Sala 3 - Vida cotidiana', 'Objetos del hogar y la vida familiar de los siglos XIX y XX', 1, '2026-11-17 16:23:02', NULL, NULL),
(5, 'Sala 4 - Río y puerto', 'Navegación, puerto y actividad comercial del río Paraná', 1, '2026-11-17 16:23:02', NULL, NULL),
(6, 'Biblioteca y archivo', 'Libros, planos y documentación histórica', 1, '2026-11-17 16:23:02', NULL, NULL),
(7, 'Depósito', 'Depósito ubicado al final del museo', 1, '2026-11-17 16:23:02', NULL, NULL),
(8, 'Vitrina 1.A', 'Piezas líticas', 2, '2026-11-17 16:23:02', NULL, NULL),
(9, 'Vitrina 1.B', 'Cerámica', 2, '2026-11-17 16:23:02', NULL, NULL),
(10, 'Vitrina 2.A', 'Objetos de inmigrantes y numismática', 3, '2026-11-17 16:23:02', NULL, NULL),
(11, 'Mueble de archivo', 'Mueble con libros, actas y planos', 6, '2026-11-17 16:23:02', NULL, NULL),
(12, 'Estante D.1', 'Estante de piezas en reserva', 7, '2026-11-17 16:23:02', NULL, NULL);

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
(24, 'edgardolui', 'lui@gmail.com', '$2y$10$nZpKeZR0szbz31BLfsG18.wNX1AyIqTW1z3jemP3hBsQtqQZIAEMC', '123213123', '2026-10-07 02:18:36', NULL, '2026-10-17 17:48:51', 3),
(25, 'eleuteria', 'eleu@gmail.com', '$2y$10$7nroZwXRUlpFq7GvG960H.TcAuJdP5VGMZiLCyikHnav5l5wpfpTG', '123123123', '2026-10-13 11:44:57', NULL, '2026-11-13 12:08:31', 3),
(26, 'loresmas', 'loresmas@gmail.com', '$2y$10$VsNyhNJfEGoqUXhslAPa4.64kVjm/LlFEDdsODci.UhhumDyfBUjK', '2392109830192', '2026-10-17 17:51:19', '2026-10-26 22:18:46', '2026-10-26 22:18:46', 1),
(27, 'cumbia420', 'cumbia420@gmail.com', '$2y$10$DhWFL8ub2v7dzIHeDmFw.uLwHU1HJ44UmWzSg4X0c5LeL0Ml4GWR6', '9821793812793', '2026-10-19 22:29:24', '2026-10-19 22:31:04', '2026-10-19 22:31:04', 1),
(28, 'josesito', 'jose@gmail.com', '$2y$10$pKjioh5ITTKbqrL15uP.f.xmkpp1E5CqfHMqW.w1G1pBmXRMFhSZ2', '123123123123', '2026-11-13 11:59:52', NULL, '2026-11-13 11:59:52', 1),
(29, 'etor maina', 'etor@gmail.com', '$2y$10$FMAxp7T0WKn6TiTxzLCOeui0/UMhcSk3G7R7cZHG22C/GaETfz7ay', '21321312', '2026-11-16 11:51:32', NULL, '2026-11-16 11:51:32', 3),
(30, 'maxi', 'maximiliano@gmail.com', '$2y$10$5a9fyZlr72caiqAzQBCDquvjvkMEMBglILyAsF.C0Au2tuJhzMqai', '2312132121', '2026-11-16 21:35:31', NULL, '2026-11-16 21:35:31', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `atributos`
--
ALTER TABLE `atributos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tabla` (`tabla`,`id_dato`),
  ADD KEY `id_usuario` (`id_usuario`);

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
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_objetos_ubicacion` (`id_ubicacion`);

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
lALTER TABLE `objeto_etiquetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_objetq_objeto` (`objeto_id`),
  ADD KEY `fk_objetq_etiqueta` (`etiqueta_id`);

--
-- Indices de la tabla `piezas_imagenes`
--
ALTER TABLE `piezas_imagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pimg_objeto` (`objeto_id`);

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
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_salas_pertenece` (`id_pertenece`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `museos`
--
ALTER TABLE `museos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `objetos`
--
ALTER TABLE `objetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `objeto_atributos`
--
ALTER TABLE `objeto_atributos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `objeto_etiquetas`
--
ALTER TABLE `objeto_etiquetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `piezas_imagenes`
--
ALTER TABLE `piezas_imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `objetos`
--
ALTER TABLE `objetos`
  ADD CONSTRAINT `fk_objetos_ubicacion` FOREIGN KEY (`id_ubicacion`) REFERENCES `salas` (`id`),
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
-- Filtros para la tabla `piezas_imagenes`
--
ALTER TABLE `piezas_imagenes`
  ADD CONSTRAINT `fk_pimg_objeto` FOREIGN KEY (`objeto_id`) REFERENCES `objetos` (`id`);

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `rol_permiso_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `fk_salas_pertenece` FOREIGN KEY (`id_pertenece`) REFERENCES `salas` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
