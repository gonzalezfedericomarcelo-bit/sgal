-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-11-2025 a las 18:17:24
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
-- Base de datos: `sgal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--

CREATE TABLE `accesos` (
  `id` int(11) NOT NULL,
  `tipo` enum('VISITA','VEHICULO') NOT NULL,
  `nombre_placa` varchar(150) NOT NULL COMMENT 'Nombre de visita o Placa de vehículo',
  `documento` varchar(50) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha_entrada` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_salida` datetime DEFAULT NULL,
  `usuario_registro_id` int(11) NOT NULL,
  `camara_vinculada_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `accesos`
--

INSERT INTO `accesos` (`id`, `tipo`, `nombre_placa`, `documento`, `motivo`, `fecha_entrada`, `fecha_salida`, `usuario_registro_id`, `camara_vinculada_id`) VALUES
(1, 'VISITA', 'Juan Perez', '25123456', 'Entrevista RRHH', '2025-10-27 00:38:26', '2025-10-27 02:38:26', 16, 1),
(2, 'VEHICULO', 'AA123BB', '', 'Carga de Mercadería', '2025-10-29 00:38:26', '2025-10-29 01:23:26', 16, 2),
(3, 'VISITA', 'Maria Gomez', '30987654', 'Reunión con Gerencia', '2025-10-31 00:38:26', '2025-10-31 01:38:26', 16, 1),
(4, 'VISITA', 'Carlos Lopez', '33123456', 'Servicio Técnico', '2025-10-31 21:38:26', NULL, 16, 1),
(5, 'VEHICULO', 'AD789FG', '', 'Descarga Proveedor', '2025-10-31 23:38:26', NULL, 16, 2),
(6, 'VEHICULO', 'AA123BB', '23176558', 'Visita de demostración', '2025-06-25 17:14:54', NULL, 16, 1),
(7, 'VISITA', 'AA123BB', '23277984', 'Visita de demostración', '2025-10-11 11:37:54', NULL, 16, 3),
(8, 'VISITA', 'AC456DE', '37793159', 'Visita de demostración', '2025-07-26 08:08:54', '2025-10-24 06:27:54', 16, 2),
(9, 'VISITA', 'AA123BB', '48556973', 'Visita de demostración', '2025-10-08 19:56:54', '2025-07-23 19:46:54', 16, 3),
(10, 'VEHICULO', 'AA123BB', '39339154', 'Visita de demostración', '2025-07-13 10:59:54', NULL, 16, 3),
(11, 'VEHICULO', 'AA123BB', '22170947', 'Visita de demostración', '2025-06-16 15:45:54', '2025-06-27 06:41:54', 16, 2),
(12, 'VEHICULO', 'Visita 011', '38646947', 'Visita de demostración', '2025-08-15 13:10:54', NULL, 16, 1),
(13, 'VISITA', 'Visita 012', '47507413', 'Visita de demostración', '2025-06-18 09:36:54', NULL, 16, 2),
(14, 'VEHICULO', 'Visita 013', '30862990', 'Visita de demostración', '2025-10-30 14:25:54', '2025-06-20 08:14:54', 16, 1),
(15, 'VEHICULO', 'Visita 014', '35850824', 'Visita de demostración', '2025-07-02 01:32:54', '2025-05-08 09:57:54', 16, 2),
(16, 'VEHICULO', 'Visita 015', '39632583', 'Visita de demostración', '2025-10-10 20:00:54', '2025-06-14 17:53:54', 16, 2),
(17, 'VEHICULO', 'AA123BB', '20046778', 'Visita de demostración', '2025-09-21 12:25:54', NULL, 16, 2),
(18, 'VEHICULO', 'AA123BB', '26233640', 'Visita de demostración', '2025-07-22 19:34:54', NULL, 16, 1),
(19, 'VISITA', 'AD789FG', '25938662', 'Visita de demostración', '2025-07-04 17:35:54', '2025-07-14 01:21:54', 16, 2),
(20, 'VISITA', 'AA123BB', '26812251', 'Visita de demostración', '2025-05-12 14:12:54', NULL, 16, 2),
(21, 'VEHICULO', 'Visita 020', '21909577', 'Visita de demostración', '2025-06-28 11:02:54', NULL, 16, 2),
(22, 'VISITA', 'Visita 021', '35465885', 'Visita de demostración', '2025-06-06 04:36:54', '2025-09-01 13:02:54', 16, 3),
(23, 'VEHICULO', 'AC456DE', '31923939', 'Visita de demostración', '2025-10-10 09:41:54', '2025-07-01 10:34:54', 16, 3),
(24, 'VISITA', 'Visita 023', '46210267', 'Visita de demostración', '2025-09-15 15:47:54', '2025-07-22 20:33:54', 16, 1),
(25, 'VISITA', 'Visita 024', '49303753', 'Visita de demostración', '2025-06-13 10:11:54', '2025-07-28 03:07:54', 16, 1),
(26, 'VISITA', 'AA123BB', '46832233', 'Visita de demostración', '2025-08-21 23:07:54', NULL, 16, 1),
(27, 'VEHICULO', 'AC456DE', '35500335', 'Visita de demostración', '2025-05-31 08:39:54', '2025-10-26 08:28:54', 16, 3),
(28, 'VISITA', 'AC456DE', '28236324', 'Visita de demostración', '2025-05-22 23:19:54', '2025-06-30 01:34:54', 16, 3),
(29, 'VISITA', 'Visita 028', '48380580', 'Visita de demostración', '2025-10-31 22:48:54', NULL, 16, 3),
(30, 'VEHICULO', 'AD789FG', '28709586', 'Visita de demostración', '2025-09-08 22:59:54', '2025-10-08 09:32:54', 16, 2),
(31, 'VISITA', 'AD789FG', '47021879', 'Visita de demostración', '2025-06-29 11:19:54', '2025-06-25 18:11:54', 16, 1),
(32, 'VISITA', 'AA123BB', '43849005', 'Visita de demostración', '2025-10-21 23:23:54', '2025-09-07 07:22:54', 16, 1),
(33, 'VEHICULO', 'AA123BB', '29439244', 'Visita de demostración', '2025-05-07 19:10:54', '2025-05-15 13:24:54', 16, 1),
(34, 'VEHICULO', 'AA123BB', '22935446', 'Visita de demostración', '2025-09-30 02:19:54', '2025-08-14 17:36:54', 16, 2),
(35, 'VISITA', 'AA123BB', '24712724', 'Visita de demostración', '2025-09-26 00:05:54', '2025-10-23 15:48:54', 16, 1),
(36, 'VISITA', 'AA123BB', '28446452', 'Visita de demostración', '2025-06-10 19:27:54', NULL, 16, 2),
(37, 'VISITA', 'Visita 036', '23199603', 'Visita de demostración', '2025-07-08 03:06:54', '2025-07-22 04:52:54', 16, 3),
(38, 'VEHICULO', 'Visita 037', '36514026', 'Visita de demostración', '2025-05-16 15:21:54', NULL, 16, 1),
(39, 'VEHICULO', 'AA123BB', '36493486', 'Visita de demostración', '2025-07-03 18:05:54', '2025-08-11 09:31:54', 16, 3),
(40, 'VISITA', 'Visita 039', '47724128', 'Visita de demostración', '2025-07-07 06:45:54', '2025-08-19 19:08:54', 16, 3),
(41, 'VEHICULO', 'Visita 040', '40318276', 'Visita de demostración', '2025-06-14 17:35:54', '2025-05-29 05:30:54', 16, 1),
(42, 'VISITA', 'AD789FG', '26138576', 'Visita de demostración', '2025-09-26 21:46:54', '2025-09-21 19:42:54', 16, 3),
(43, 'VEHICULO', 'AA123BB', '40849614', 'Visita de demostración', '2025-09-25 12:38:54', '2025-10-25 11:06:54', 16, 2),
(44, 'VISITA', 'Visita 043', '24215689', 'Visita de demostración', '2025-10-10 08:06:54', NULL, 16, 2),
(45, 'VISITA', 'Visita 044', '29316428', 'Visita de demostración', '2025-07-25 15:50:54', '2025-08-27 10:01:54', 16, 1),
(46, 'VEHICULO', 'Visita 045', '28001645', 'Visita de demostración', '2025-06-16 11:53:54', NULL, 16, 3),
(47, 'VISITA', 'AC456DE', '29648228', 'Visita de demostración', '2025-10-26 07:31:54', NULL, 16, 3),
(48, 'VEHICULO', 'AD789FG', '31892658', 'Visita de demostración', '2025-07-13 15:35:54', '2025-07-26 00:27:54', 16, 1),
(49, 'VISITA', 'Visita 048', '40900880', 'Visita de demostración', '2025-05-09 01:14:54', '2025-10-21 16:15:54', 16, 3),
(50, 'VISITA', 'Visita 049', '30546768', 'Visita de demostración', '2025-06-21 22:19:54', '2025-05-28 02:15:54', 16, 2),
(51, 'VISITA', 'Visita 050', '32865747', 'Visita de demostración', '2025-08-10 06:27:54', NULL, 16, 2),
(52, 'VEHICULO', 'Visita 051', '44027904', 'Visita de demostración', '2025-09-06 15:42:54', NULL, 16, 2),
(53, 'VISITA', 'Visita 052', '24222507', 'Visita de demostración', '2025-10-05 10:04:54', '2025-10-06 17:03:54', 16, 1),
(54, 'VEHICULO', 'Visita 053', '41345893', 'Visita de demostración', '2025-07-22 05:40:54', '2025-06-15 18:33:54', 16, 2),
(55, 'VEHICULO', 'Visita 054', '21358309', 'Visita de demostración', '2025-05-09 07:01:54', '2025-06-10 03:22:54', 16, 1),
(56, 'VISITA', 'AD789FG', '48057172', 'Visita de demostración', '2025-07-16 17:01:54', NULL, 16, 1),
(57, 'VISITA', 'AA123BB', '37444110', 'Visita de demostración', '2025-09-14 12:25:54', '2025-10-16 19:22:54', 16, 2),
(58, 'VISITA', 'AA123BB', '41155352', 'Visita de demostración', '2025-10-23 08:03:54', NULL, 16, 2),
(59, 'VISITA', 'AC456DE', '31040057', 'Visita de demostración', '2025-10-30 08:35:54', '2025-06-30 22:02:54', 16, 3),
(60, 'VISITA', 'AA123BB', '39663832', 'Visita de demostración', '2025-10-28 13:20:54', NULL, 16, 3),
(61, 'VISITA', 'AC456DE', '29383703', 'Visita de demostración', '2025-08-22 03:42:54', NULL, 16, 2),
(62, 'VEHICULO', 'AA123BB', '25186373', 'Visita de demostración', '2025-06-13 00:42:54', '2025-07-08 19:11:54', 16, 1),
(63, 'VEHICULO', 'Visita 062', '36724468', 'Visita de demostración', '2025-07-26 14:13:54', NULL, 16, 3),
(64, 'VISITA', 'Visita 063', '29938122', 'Visita de demostración', '2025-10-27 13:07:54', NULL, 16, 2),
(65, 'VEHICULO', 'AC456DE', '34734477', 'Visita de demostración', '2025-09-06 20:37:54', NULL, 16, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacenes`
--

CREATE TABLE `almacenes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `almacenes`
--

INSERT INTO `almacenes` (`id`, `nombre`, `ubicacion`) VALUES
(1, 'Bodega Central (A)', 'Sector A-1, Edificio Principal'),
(2, 'Almacén Secundario (B)', 'Sector B-3, Edificio Anexo'),
(3, 'Pañol de Taller', 'Taller de Mantenimiento'),
(4, 'Depósito AA', 'Zona Norte - Fila 1'),
(5, 'Depósito AB', 'Zona Norte - Fila 2'),
(6, 'Depósito AC', 'Zona Norte - Fila 3'),
(7, 'Depósito AD', 'Zona Norte - Fila 4'),
(8, 'Depósito BA', 'Zona Sur - Fila 1'),
(9, 'Depósito BB', 'Zona Sur - Fila 2'),
(10, 'Depósito BC', 'Zona Sur - Fila 3'),
(11, 'Depósito BD', 'Zona Sur - Fila 4'),
(12, 'Depósito CA', 'Zona Este - Fila 1'),
(13, 'Depósito CB', 'Zona Este - Fila 2'),
(14, 'Depósito CC', 'Zona Este - Fila 3'),
(15, 'Depósito CD', 'Zona Este - Fila 4'),
(16, 'Depósito DA', 'Zona Oeste - Fila 1'),
(17, 'Depósito DB', 'Zona Oeste - Fila 2'),
(18, 'Depósito DC', 'Zona Oeste - Fila 3'),
(19, 'Depósito DD', 'Zona Oeste - Fila 4'),
(20, 'Recepción General', 'Entrada Principal'),
(21, 'Rechazos y Devoluciones', 'Sector R-1'),
(22, 'Stock de Seguridad', 'Sector S-1'),
(23, 'Materiales de Oficina', 'Piso 2, Oficina 205');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camaras`
--

CREATE TABLE `camaras` (
  `id` int(11) NOT NULL,
  `nombre_camara` varchar(100) NOT NULL,
  `ubicacion` varchar(150) DEFAULT NULL,
  `url_stream_ip` varchar(255) DEFAULT NULL COMMENT 'URL del stream (MJPEG, RTSP, HLS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `camaras`
--

INSERT INTO `camaras` (`id`, `nombre_camara`, `ubicacion`, `url_stream_ip`) VALUES
(1, 'CAM-01 Garita Principal', 'Acceso Peatonal', 'http://pendelcam.kip.uni-heidelberg.de/mjpg/video.mjpg'),
(2, 'CAM-02 Garita Vehicular', 'Acceso Vehicular', 'http://88.53.197.250/axis-cgi/mjpg/video.cgi'),
(3, 'CAM-03 Depósito A', 'Interior Bodega Central', 'http://webcam.st-malo.com/axis-cgi/mjpg/video.cgi'),
(4, 'CAM-04', 'Lugano, Suiza', 'http://webcam01.lugano.ch/axis-cgi/mjpg/video.cgi'),
(5, 'CAM-05', 'Venecia, Italia', 'http://85.46.64.146/axis-cgi/mjpg/video.cgi'),
(6, 'CAM-06', 'Prueba', 'http://212.67.231.233/mjpg/video.mjpg'),
(7, 'CAM-07', 'Prueba2', 'http://77.222.181.11:8080/mjpg/video.mjpg'),
(8, 'CAM-08', 'Prueba3', 'http://213.128.169.233:1112/mjpg/video.mjpg'),
(9, 'CAM-09', 'Prueba4', 'http://62.214.4.38/mjpg/video.mjpg'),
(10, 'CAM-10', 'Prueba5', 'http://193.214.75.118/mjpg/video.mjpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre_cliente` varchar(150) NOT NULL,
  `direccion_principal` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre_cliente`, `direccion_principal`, `telefono`) VALUES
(1, 'Cliente A (GlobalTech)', 'Av. Corrientes 1234, CABA', '11-4567-8901'),
(2, 'Cliente B (LogiCorp)', 'San Martín 500, Rosario', '341-456-7890'),
(3, 'Cliente C (Industrias Sur)', 'Calle Falsa 123, Avellaneda', '11-3210-4567');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_global`
--

CREATE TABLE `configuracion_global` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL COMMENT 'Ej: DOBLE_APROBACION_LOGISTICA',
  `setting_value` varchar(255) NOT NULL COMMENT 'Ej: 1 (true) o 0 (false)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion_global`
--

INSERT INTO `configuracion_global` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'DOBLE_APROBACION_LOGISTICA', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costos_predictivos`
--

CREATE TABLE `costos_predictivos` (
  `id` int(11) NOT NULL,
  `ruta_id` int(11) NOT NULL,
  `costo_estimado_combustible` decimal(10,2) DEFAULT NULL,
  `costo_estimado_peajes` decimal(10,2) DEFAULT NULL,
  `costo_estimado_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `costos_predictivos`
--

INSERT INTO `costos_predictivos` (`id`, `ruta_id`, `costo_estimado_combustible`, `costo_estimado_peajes`, `costo_estimado_total`) VALUES
(1, 1, 150.50, 45.00, 195.50),
(2, 2, 120.00, 30.00, 150.00),
(3, 3, 80.00, 60.00, 140.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `nombre_doc` varchar(150) NOT NULL,
  `entidad_tipo` enum('VEHICULO','CONDUCTOR','CLIENTE') NOT NULL COMMENT 'Tipo de entidad (tabla) a la que pertenece',
  `entidad_id` int(11) NOT NULL COMMENT 'ID de la entidad (ej. vehiculos.id o usuarios.id)',
  `fecha_emision` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `archivo_path` varchar(255) NOT NULL COMMENT 'Ruta donde se guarda el archivo físico',
  `usuario_carga_id` int(11) NOT NULL,
  `fecha_carga` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturacion`
--

CREATE TABLE `facturacion` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `numero_factura` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `estado` enum('PENDIENTE','PAGADA','ANULADA') NOT NULL DEFAULT 'PENDIENTE',
  `fecha_pago` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `facturacion`
--

INSERT INTO `facturacion` (`id`, `cliente_id`, `numero_factura`, `fecha`, `monto`, `estado`, `fecha_pago`) VALUES
(1, 1, 'F-0001', '2025-09-01', 150000.00, 'PAGADA', '2025-10-01'),
(2, 2, 'F-0002', '2025-10-01', 300000.00, 'PAGADA', '2025-10-17'),
(3, 3, 'F-0003', '2025-10-22', 80000.00, 'PENDIENTE', NULL),
(4, 1, 'F-0004', '2025-10-27', 220000.00, 'PENDIENTE', NULL),
(5, 2, 'F-0005', '2025-10-31', 50000.00, 'ANULADA', NULL),
(6, 1, 'F-DEMO-0005', '2025-10-29', 26723.00, 'PAGADA', '2025-11-04'),
(7, 1, 'F-DEMO-0006', '2025-07-08', 58924.00, 'PENDIENTE', NULL),
(8, 1, 'F-DEMO-0007', '2025-07-10', 17200.00, 'PENDIENTE', NULL),
(9, 2, 'F-DEMO-0008', '2025-07-04', 10423.00, 'PAGADA', '2025-07-15'),
(10, 1, 'F-DEMO-0009', '2025-06-07', 37127.00, 'PAGADA', '2025-06-15'),
(11, 3, 'F-DEMO-0010', '2025-08-20', 54116.00, 'PAGADA', '2025-08-26'),
(12, 1, 'F-DEMO-0011', '2025-06-05', 44706.00, 'PENDIENTE', NULL),
(13, 1, 'F-DEMO-0012', '2025-06-20', 18960.00, 'PAGADA', '2025-06-26'),
(14, 1, 'F-DEMO-0013', '2025-10-26', 31896.00, 'PAGADA', '2025-11-05'),
(15, 1, 'F-DEMO-0014', '2025-08-10', 32787.00, 'PENDIENTE', NULL),
(16, 3, 'F-DEMO-0015', '2025-05-21', 47867.00, 'PAGADA', '2025-05-22'),
(17, 2, 'F-DEMO-0016', '2025-08-12', 29018.00, 'PAGADA', '2025-08-19'),
(18, 2, 'F-DEMO-0017', '2025-08-23', 46155.00, 'PAGADA', '2025-09-21'),
(19, 1, 'F-DEMO-0018', '2025-06-10', 36615.00, 'PAGADA', '2025-06-15'),
(20, 3, 'F-DEMO-0019', '2025-05-28', 54952.00, 'PENDIENTE', NULL),
(21, 2, 'F-DEMO-0020', '2025-08-29', 54270.00, 'PAGADA', '2025-09-27'),
(22, 1, 'F-DEMO-0021', '2025-09-28', 42126.00, 'PAGADA', '2025-10-05'),
(23, 1, 'F-DEMO-0022', '2025-07-08', 12148.00, 'PAGADA', '2025-07-18'),
(24, 1, 'F-DEMO-0023', '2025-05-30', 25256.00, 'PENDIENTE', NULL),
(25, 1, 'F-DEMO-0024', '2025-10-24', 30548.00, 'PENDIENTE', NULL),
(26, 3, 'F-DEMO-0025', '2025-09-27', 30646.00, 'PAGADA', '2025-10-26'),
(27, 1, 'F-DEMO-0026', '2025-07-07', 10071.00, 'PAGADA', '2025-07-29'),
(28, 2, 'F-DEMO-0027', '2025-06-02', 15648.00, 'PAGADA', '2025-06-23'),
(29, 3, 'F-DEMO-0028', '2025-10-01', 50618.00, 'PAGADA', '2025-10-13'),
(30, 1, 'F-DEMO-0029', '2025-08-09', 59191.00, 'PAGADA', '2025-09-01'),
(31, 2, 'F-DEMO-0030', '2025-09-20', 20056.00, 'PAGADA', '2025-10-12'),
(32, 3, 'F-DEMO-0031', '2025-07-28', 20326.00, 'PAGADA', '2025-08-05'),
(33, 3, 'F-DEMO-0032', '2025-10-27', 58410.00, 'PENDIENTE', NULL),
(34, 2, 'F-DEMO-0033', '2025-06-20', 58029.00, 'PAGADA', '2025-06-25'),
(35, 2, 'F-DEMO-0034', '2025-10-17', 28280.00, 'PAGADA', '2025-11-14'),
(36, 2, 'F-DEMO-0035', '2025-05-20', 51981.00, 'PAGADA', '2025-05-28'),
(37, 3, 'F-DEMO-0036', '2025-08-29', 47961.00, 'PENDIENTE', NULL),
(38, 3, 'F-DEMO-0037', '2025-05-09', 53519.00, 'PAGADA', '2025-05-22'),
(39, 1, 'F-DEMO-0038', '2025-06-07', 32921.00, 'PENDIENTE', NULL),
(40, 1, 'F-DEMO-0039', '2025-06-30', 16765.00, 'PAGADA', '2025-07-12'),
(41, 2, 'F-DEMO-0040', '2025-08-16', 21386.00, 'PENDIENTE', NULL),
(42, 1, 'F-DEMO-0041', '2025-07-19', 53406.00, 'PAGADA', '2025-08-07'),
(43, 1, 'F-DEMO-0042', '2025-05-26', 57561.00, 'PAGADA', '2025-05-27'),
(44, 2, 'F-DEMO-0043', '2025-10-18', 43426.00, 'PAGADA', '2025-10-24'),
(45, 2, 'F-DEMO-0044', '2025-08-16', 12964.00, 'PAGADA', '2025-09-10'),
(46, 3, 'F-DEMO-0045', '2025-05-24', 54243.00, 'PENDIENTE', NULL),
(47, 2, 'F-DEMO-0046', '2025-10-03', 50556.00, 'PAGADA', '2025-10-23'),
(48, 2, 'F-DEMO-0047', '2025-06-27', 38507.00, 'ANULADA', NULL),
(49, 2, 'F-DEMO-0048', '2025-05-22', 55129.00, 'PENDIENTE', NULL),
(50, 3, 'F-DEMO-0049', '2025-06-10', 33035.00, 'PENDIENTE', NULL),
(51, 2, 'F-DEMO-0050', '2025-05-06', 56631.00, 'PAGADA', '2025-05-28'),
(52, 1, 'F-DEMO-0051', '2025-09-26', 42418.00, 'PAGADA', '2025-10-14'),
(53, 1, 'F-DEMO-0052', '2025-06-18', 41053.00, 'PENDIENTE', NULL),
(54, 3, 'F-DEMO-0053', '2025-07-11', 26616.00, 'PENDIENTE', NULL),
(55, 3, 'F-DEMO-0054', '2025-10-24', 16284.00, 'PAGADA', '2025-11-18'),
(56, 1, 'F-DEMO-0055', '2025-07-18', 19434.00, 'PAGADA', '2025-07-28'),
(57, 1, 'F-DEMO-0056', '2025-09-17', 51902.00, 'PAGADA', '2025-09-22'),
(58, 1, 'F-DEMO-0057', '2025-08-02', 44278.00, 'PENDIENTE', NULL),
(59, 2, 'F-DEMO-0058', '2025-06-27', 52789.00, 'PAGADA', '2025-07-22'),
(60, 1, 'F-DEMO-0059', '2025-10-28', 41231.00, 'PAGADA', '2025-11-16'),
(61, 1, 'F-DEMO-0060', '2025-10-03', 11340.00, 'PAGADA', '2025-10-23'),
(62, 3, 'F-DEMO-0061', '2025-08-28', 48120.00, 'PENDIENTE', NULL),
(63, 3, 'F-DEMO-0062', '2025-07-05', 50902.00, 'PAGADA', '2025-07-17'),
(64, 1, 'F-DEMO-0063', '2025-06-18', 28220.00, 'PAGADA', '2025-06-19'),
(65, 3, 'F-DEMO-0064', '2025-05-10', 17066.00, 'PENDIENTE', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id` int(11) NOT NULL,
  `tipo_gasto_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` text DEFAULT NULL,
  `vehiculo_id` int(11) DEFAULT NULL COMMENT 'Gasto asociado a un vehículo',
  `ruta_id` int(11) DEFAULT NULL COMMENT 'Gasto asociado a una ruta',
  `usuario_registro_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `almacen_id` int(11) NOT NULL,
  `cantidad_actual` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `producto_id`, `almacen_id`, `cantidad_actual`) VALUES
(1, 1, 1, 4000),
(2, 2, 1, 850),
(3, 3, 3, 50),
(4, 4, 3, 200),
(5, 1, 3, 1000),
(6, 2, 3, 50),
(7, 95, 12, 518),
(8, 13, 17, 335),
(9, 56, 16, 116),
(10, 69, 7, 418),
(11, 56, 4, 242),
(12, 43, 18, 452),
(13, 77, 16, 183),
(14, 88, 5, 219),
(15, 91, 13, 485),
(16, 10, 7, 289),
(17, 63, 19, 482),
(18, 13, 12, 220),
(19, 21, 15, 500),
(20, 100, 18, 244),
(21, 20, 5, 297),
(22, 42, 5, 105),
(23, 38, 12, 111),
(24, 73, 9, 276),
(25, 102, 10, 234),
(26, 43, 19, 374),
(27, 36, 7, 545),
(28, 39, 6, 220),
(29, 59, 17, 196),
(30, 6, 7, 57),
(31, 87, 9, 107),
(32, 39, 18, 262),
(33, 34, 9, 507),
(34, 40, 15, 291),
(35, 72, 17, 82),
(36, 85, 10, 217),
(37, 22, 18, 480),
(38, 67, 13, 300),
(39, 53, 17, 319),
(40, 86, 5, 416),
(41, 38, 10, 541),
(42, 76, 10, 226),
(43, 68, 6, 75),
(44, 67, 17, 65),
(45, 11, 17, 325),
(46, 93, 16, 50),
(47, 79, 13, 536),
(48, 38, 4, 303),
(49, 47, 13, 341),
(50, 37, 15, 233),
(51, 74, 15, 237),
(52, 6, 8, 99),
(53, 53, 12, 395),
(54, 6, 6, 60),
(55, 76, 5, 471),
(56, 93, 10, 525),
(57, 13, 15, 449),
(58, 76, 12, 488),
(59, 95, 14, 198),
(60, 54, 18, 306),
(61, 37, 4, 346),
(62, 82, 11, 405),
(63, 60, 11, 175),
(64, 69, 11, 158),
(65, 65, 18, 58),
(66, 7, 7, 324),
(67, 94, 19, 472),
(68, 89, 4, 67),
(69, 34, 13, 340),
(70, 66, 17, 113),
(71, 26, 10, 102),
(72, 7, 10, 533),
(73, 15, 8, 85),
(74, 89, 18, 50),
(75, 58, 9, 538),
(76, 36, 6, 63),
(77, 41, 7, 334),
(78, 51, 14, 221),
(79, 97, 15, 450),
(80, 58, 15, 97),
(81, 12, 11, 340),
(82, 55, 18, 175),
(83, 7, 15, 517),
(84, 61, 9, 507),
(85, 46, 15, 410),
(86, 35, 10, 249),
(87, 103, 13, 361),
(88, 32, 17, 518),
(89, 94, 16, 448),
(90, 27, 10, 80),
(91, 89, 6, 294),
(92, 49, 5, 508),
(93, 70, 12, 548),
(94, 102, 16, 198),
(95, 70, 5, 293),
(96, 98, 6, 539),
(97, 23, 11, 442),
(98, 101, 12, 381),
(99, 43, 13, 306),
(100, 13, 11, 167),
(101, 94, 4, 312),
(102, 36, 18, 475),
(103, 9, 17, 274),
(104, 76, 8, 75),
(105, 48, 18, 162),
(106, 38, 15, 458),
(107, 67, 5, 198),
(108, 93, 15, 71),
(109, 64, 11, 223),
(110, 77, 4, 216),
(111, 23, 16, 201),
(112, 78, 5, 443),
(113, 45, 18, 166),
(114, 64, 15, 454),
(115, 29, 15, 65),
(116, 89, 5, 176),
(117, 86, 13, 195),
(118, 9, 18, 59),
(119, 90, 6, 428),
(120, 65, 5, 124),
(121, 47, 12, 474),
(122, 39, 19, 98),
(123, 56, 11, 541),
(124, 51, 11, 497),
(125, 101, 6, 410),
(126, 28, 9, 533),
(127, 76, 19, 380),
(128, 70, 15, 414),
(129, 61, 12, 519),
(130, 94, 13, 190),
(131, 20, 10, 97),
(132, 76, 9, 176),
(133, 36, 19, 477),
(134, 44, 13, 271),
(135, 44, 19, 445),
(136, 9, 12, 338),
(137, 74, 14, 177),
(138, 63, 5, 468),
(139, 74, 12, 147),
(140, 33, 6, 253),
(141, 67, 18, 214),
(142, 11, 7, 263),
(143, 54, 4, 191),
(144, 32, 7, 93),
(145, 82, 7, 366),
(146, 8, 10, 367),
(147, 37, 9, 292),
(148, 87, 12, 110),
(149, 72, 19, 509),
(150, 63, 8, 531),
(151, 42, 8, 206),
(152, 101, 4, 247),
(153, 65, 16, 341),
(154, 6, 18, 478),
(155, 100, 15, 152),
(156, 98, 15, 492),
(157, 104, 18, 437),
(158, 81, 8, 403),
(159, 44, 6, 415),
(160, 72, 5, 331),
(161, 5, 17, 535),
(162, 18, 9, 79),
(163, 53, 9, 356),
(164, 100, 12, 538),
(165, 73, 11, 178),
(166, 40, 7, 533),
(167, 8, 5, 73),
(168, 54, 6, 445),
(169, 27, 13, 536),
(170, 15, 15, 217),
(171, 18, 4, 180),
(172, 27, 4, 473),
(173, 64, 8, 539),
(174, 45, 8, 262),
(175, 84, 11, 352),
(176, 10, 13, 195),
(177, 84, 9, 420),
(178, 83, 18, 407),
(179, 41, 6, 488),
(180, 19, 5, 201),
(181, 80, 4, 159),
(182, 71, 6, 407),
(183, 52, 6, 115),
(184, 8, 16, 111),
(185, 104, 15, 50),
(186, 31, 16, 126),
(187, 18, 17, 469),
(188, 95, 4, 85),
(189, 37, 17, 336),
(190, 103, 7, 380),
(191, 83, 15, 171),
(192, 77, 7, 531),
(193, 71, 14, 82),
(194, 49, 14, 453),
(195, 55, 19, 488),
(196, 103, 16, 334),
(197, 104, 7, 66),
(198, 47, 19, 116),
(199, 94, 8, 387),
(200, 8, 13, 330),
(201, 67, 7, 462),
(202, 98, 16, 546),
(203, 76, 6, 111),
(204, 98, 9, 179),
(205, 10, 14, 440),
(206, 9, 15, 411),
(207, 59, 14, 304),
(208, 23, 10, 295),
(209, 60, 18, 349),
(210, 50, 16, 259),
(211, 15, 19, 546),
(212, 26, 4, 163),
(213, 96, 10, 132),
(214, 84, 5, 414),
(215, 43, 7, 449),
(216, 26, 8, 273),
(217, 59, 10, 530),
(218, 14, 15, 480),
(219, 29, 5, 344),
(220, 24, 4, 492),
(221, 97, 19, 462),
(222, 85, 16, 215),
(223, 104, 13, 112),
(224, 90, 17, 55),
(225, 37, 10, 191),
(226, 50, 12, 354),
(227, 59, 15, 159),
(228, 69, 6, 95),
(229, 13, 19, 116),
(230, 72, 13, 379),
(231, 54, 5, 506),
(232, 89, 11, 116),
(233, 36, 4, 292),
(234, 77, 9, 493),
(235, 96, 13, 523),
(236, 11, 14, 344),
(237, 47, 10, 167),
(238, 101, 13, 442),
(239, 100, 11, 307),
(240, 89, 8, 428),
(241, 18, 14, 523),
(242, 19, 18, 296),
(243, 30, 13, 519),
(244, 102, 7, 493),
(245, 21, 5, 250),
(246, 27, 11, 358),
(247, 43, 15, 240),
(248, 94, 14, 532),
(249, 99, 18, 373),
(250, 60, 12, 77),
(251, 25, 14, 426),
(252, 49, 18, 532),
(253, 93, 13, 123),
(254, 89, 14, 341),
(255, 29, 9, 290),
(256, 11, 4, 371),
(257, 21, 4, 116),
(258, 32, 8, 342),
(259, 14, 10, 492),
(260, 31, 9, 333),
(261, 19, 6, 524),
(262, 95, 16, 374),
(263, 93, 4, 167),
(264, 62, 12, 353),
(265, 16, 13, 472),
(266, 87, 16, 369),
(267, 45, 11, 531),
(268, 12, 7, 245),
(269, 39, 5, 504),
(270, 74, 7, 284),
(271, 47, 4, 272),
(272, 84, 15, 497),
(273, 84, 7, 533),
(274, 102, 15, 208),
(275, 39, 15, 195),
(276, 83, 4, 542),
(277, 104, 14, 316),
(278, 99, 13, 154),
(279, 97, 6, 143),
(280, 21, 8, 330),
(281, 70, 10, 250),
(282, 95, 7, 131),
(283, 55, 6, 127),
(284, 68, 7, 431),
(285, 6, 14, 484),
(286, 15, 14, 416),
(287, 34, 4, 398),
(288, 37, 12, 488),
(289, 86, 8, 351),
(290, 57, 14, 394),
(291, 40, 19, 234),
(292, 18, 13, 523),
(293, 64, 18, 158),
(294, 33, 8, 469),
(295, 98, 14, 318),
(296, 50, 7, 357),
(297, 10, 6, 309),
(298, 12, 15, 254),
(299, 89, 17, 413),
(300, 84, 17, 547),
(301, 28, 14, 533),
(302, 87, 18, 390),
(303, 90, 5, 318),
(304, 89, 12, 364),
(305, 44, 18, 320),
(306, 42, 13, 474),
(307, 50, 15, 425),
(308, 96, 8, 169),
(309, 51, 5, 384),
(310, 14, 4, 371),
(311, 44, 4, 96),
(312, 97, 14, 116),
(313, 78, 11, 347),
(314, 52, 9, 459),
(315, 57, 17, 56),
(316, 35, 19, 429),
(317, 85, 4, 206),
(318, 58, 8, 418),
(319, 7, 5, 514),
(320, 68, 19, 549),
(321, 21, 13, 367),
(322, 54, 10, 296),
(323, 30, 14, 193),
(324, 8, 12, 229),
(325, 62, 10, 180),
(326, 63, 10, 461),
(327, 85, 17, 108),
(328, 73, 10, 427),
(329, 70, 9, 135),
(330, 76, 7, 422),
(331, 9, 16, 522),
(332, 67, 15, 543),
(333, 37, 7, 201),
(334, 9, 5, 464),
(335, 9, 8, 115),
(336, 54, 8, 365),
(337, 73, 8, 319),
(338, 81, 16, 547),
(339, 81, 9, 58),
(340, 62, 14, 486),
(341, 38, 7, 108),
(342, 35, 4, 185),
(343, 19, 7, 202),
(344, 55, 7, 391),
(345, 45, 10, 536),
(346, 8, 8, 362),
(347, 17, 6, 454),
(348, 28, 15, 177),
(349, 84, 6, 537),
(350, 71, 7, 60),
(351, 20, 11, 493),
(352, 41, 5, 331),
(353, 83, 19, 69),
(354, 34, 18, 417),
(355, 90, 11, 119),
(356, 36, 14, 545),
(357, 84, 19, 343),
(358, 93, 14, 428),
(359, 92, 17, 472),
(360, 36, 15, 164),
(361, 100, 7, 164),
(362, 77, 11, 300),
(363, 70, 19, 516),
(364, 51, 12, 313),
(365, 86, 7, 441),
(366, 86, 14, 220),
(367, 94, 6, 72),
(368, 22, 19, 370),
(369, 15, 9, 54),
(370, 19, 16, 257),
(371, 45, 15, 151),
(372, 23, 13, 399),
(373, 29, 4, 239),
(374, 45, 7, 235),
(375, 56, 8, 403),
(376, 53, 4, 191),
(377, 90, 19, 156),
(378, 73, 7, 148),
(379, 31, 14, 291),
(380, 9, 14, 539),
(381, 78, 18, 405),
(382, 5, 5, 373),
(383, 8, 17, 504),
(384, 30, 19, 83),
(385, 14, 7, 205),
(386, 58, 4, 173),
(387, 21, 9, 353),
(388, 95, 11, 227),
(389, 27, 16, 481),
(390, 104, 17, 338),
(391, 20, 7, 126),
(392, 21, 10, 291),
(393, 102, 12, 296),
(394, 41, 4, 152),
(395, 5, 4, 154),
(396, 68, 12, 106),
(397, 40, 4, 64),
(398, 18, 15, 289),
(399, 67, 16, 231),
(400, 103, 6, 541),
(401, 37, 6, 352),
(402, 68, 8, 71),
(403, 72, 10, 104),
(404, 46, 7, 317),
(405, 54, 12, 415),
(406, 79, 19, 441),
(407, 72, 4, 540),
(408, 40, 18, 149),
(409, 95, 8, 479),
(410, 57, 19, 56),
(411, 71, 13, 143),
(412, 45, 16, 423),
(413, 88, 4, 332),
(414, 101, 8, 222),
(415, 82, 14, 506),
(416, 65, 12, 281),
(417, 25, 15, 111),
(418, 35, 8, 377),
(419, 23, 9, 246),
(420, 72, 18, 142),
(421, 96, 14, 319),
(422, 80, 18, 65),
(423, 42, 9, 274),
(424, 91, 10, 212),
(425, 39, 14, 151),
(426, 10, 19, 250),
(427, 87, 14, 374),
(428, 46, 18, 163),
(429, 70, 6, 520),
(430, 86, 18, 107),
(431, 46, 9, 78),
(432, 91, 15, 168),
(433, 39, 11, 330),
(434, 73, 18, 418),
(435, 86, 16, 497),
(436, 45, 12, 314),
(437, 34, 7, 132),
(438, 41, 11, 530),
(439, 62, 9, 358),
(440, 40, 13, 402),
(441, 71, 18, 324),
(442, 24, 19, 273),
(443, 26, 18, 282),
(444, 48, 4, 70),
(445, 75, 5, 332),
(446, 62, 6, 211),
(447, 80, 14, 285),
(448, 16, 12, 54),
(449, 79, 6, 308),
(450, 16, 5, 92),
(451, 81, 17, 290),
(452, 81, 12, 430),
(453, 64, 4, 488),
(454, 58, 17, 82),
(455, 89, 7, 54),
(456, 22, 8, 393),
(457, 68, 14, 507),
(458, 76, 13, 476),
(459, 13, 6, 419),
(460, 65, 7, 535),
(461, 91, 6, 482),
(462, 67, 6, 352),
(463, 56, 19, 172),
(464, 46, 19, 457),
(465, 84, 8, 351),
(466, 28, 12, 213),
(467, 74, 19, 236),
(468, 17, 4, 330),
(469, 66, 6, 212),
(470, 66, 11, 238),
(471, 65, 11, 369),
(472, 14, 17, 139),
(473, 73, 4, 440),
(474, 5, 12, 534),
(475, 47, 16, 219),
(476, 91, 4, 500),
(477, 66, 9, 285),
(478, 20, 4, 144),
(479, 52, 7, 127),
(480, 36, 9, 407),
(481, 77, 15, 343),
(482, 15, 10, 314),
(483, 46, 12, 413),
(484, 25, 7, 89),
(485, 77, 17, 435),
(486, 92, 7, 500),
(487, 23, 17, 389),
(488, 72, 8, 493),
(489, 89, 9, 208),
(490, 31, 10, 497),
(491, 98, 12, 230),
(492, 99, 10, 187),
(493, 74, 4, 405),
(494, 6, 17, 156),
(495, 93, 9, 249),
(496, 87, 13, 174),
(497, 93, 12, 163),
(498, 35, 18, 268),
(499, 57, 4, 493),
(500, 80, 19, 314),
(501, 44, 11, 531),
(502, 97, 13, 368),
(503, 103, 12, 152),
(504, 54, 16, 227),
(505, 60, 15, 430),
(506, 42, 11, 308),
(519, 2, 2, 0),
(520, 2, 4, 100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_sistema`
--

CREATE TABLE `log_sistema` (
  `id` bigint(20) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL COMMENT 'NULO si es acción del sistema',
  `accion_realizada` varchar(100) NOT NULL COMMENT 'Ej: LOGIN_EXITOSO, UPDATE_GASTO',
  `detalles` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_stock`
--

CREATE TABLE `movimientos_stock` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `almacen_origen_id` int(11) NOT NULL,
  `almacen_destino_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` enum('PENDIENTE','APROBADO','RECHAZADO') NOT NULL DEFAULT 'PENDIENTE',
  `usuario_solicitud_id` int(11) NOT NULL,
  `usuario_aprobacion_id` int(11) DEFAULT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_aprobacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `movimientos_stock`
--

INSERT INTO `movimientos_stock` (`id`, `producto_id`, `almacen_origen_id`, `almacen_destino_id`, `cantidad`, `estado`, `usuario_solicitud_id`, `usuario_aprobacion_id`, `fecha_solicitud`, `fecha_aprobacion`) VALUES
(1, 1, 1, 3, 1000, 'APROBADO', 17, 18, '2025-10-17 00:38:26', '2025-10-18 00:38:26'),
(2, 2, 1, 3, 50, 'APROBADO', 17, 18, '2025-10-22 00:38:26', '2025-10-23 00:38:26'),
(3, 4, 3, 2, 20, 'PENDIENTE', 17, NULL, '2025-10-29 00:38:27', NULL),
(4, 3, 3, 1, 10, 'PENDIENTE', 17, NULL, '2025-10-31 23:38:27', NULL),
(5, 2, 1, 2, 100, 'APROBADO', 22, 22, '2025-11-02 12:54:10', '2025-11-02 12:54:10'),
(6, 2, 2, 4, 100, 'APROBADO', 22, 22, '2025-11-02 12:54:49', '2025-11-02 12:59:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paradas_ruta`
--

CREATE TABLE `paradas_ruta` (
  `id` int(11) NOT NULL,
  `ruta_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `direccion` varchar(255) NOT NULL COMMENT 'Dirección de la parada (puede diferir de la principal)',
  `orden_visita` int(11) NOT NULL,
  `estado_parada` enum('PENDIENTE','VISITADA','CANCELADA') NOT NULL DEFAULT 'PENDIENTE',
  `fecha_llegada_estimada` datetime DEFAULT NULL,
  `fecha_llegada_real` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `paradas_ruta`
--

INSERT INTO `paradas_ruta` (`id`, `ruta_id`, `cliente_id`, `direccion`, `orden_visita`, `estado_parada`, `fecha_llegada_estimada`, `fecha_llegada_real`) VALUES
(1, 1, 1, 'Av. Corrientes 1234, CABA', 1, 'VISITADA', NULL, NULL),
(2, 1, 2, 'San Martín 500, Rosario', 2, 'VISITADA', NULL, NULL),
(3, 2, 3, 'Calle Falsa 123, Avellaneda', 1, 'PENDIENTE', NULL, NULL),
(4, 3, 1, 'Av. Corrientes 1234, CABA', 1, 'PENDIENTE', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `clave_permiso` varchar(100) NOT NULL COMMENT 'Ej: acceso_registrar_entrada, logistica_aprobar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `clave_permiso`) VALUES
(4, 'acceso_registrar_entrada'),
(5, 'acceso_registrar_salida'),
(6, 'acceso_ver_historial'),
(3, 'admin_gestionar_roles'),
(2, 'admin_gestionar_usuarios'),
(1, 'admin_panel'),
(24, 'bi_ver_dashboard'),
(15, 'camaras_gestionar'),
(14, 'camaras_ver_dashboard'),
(35, 'config_gestionar_clientes'),
(21, 'config_gestionar_sistema'),
(36, 'config_gestionar_tipos_gasto'),
(34, 'config_gestionar_vehiculos'),
(13, 'documentos_gestionar'),
(19, 'documentos_ver_alertas'),
(17, 'financiero_gestionar_facturacion'),
(11, 'financiero_gestionar_gastos'),
(22, 'financiero_gestionar_presupuestos'),
(23, 'financiero_ver_costeos'),
(8, 'logistica_aprobar_movimiento'),
(16, 'logistica_gestionar_almacenes'),
(10, 'logistica_gestionar_productos'),
(7, 'logistica_solicitar_movimiento'),
(9, 'logistica_ver_inventario'),
(20, 'reportes_ver_calidad_datos'),
(12, 'rutas_planificar'),
(18, 'rutas_ver_activas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos`
--

CREATE TABLE `presupuestos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `mes` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `monto_presupuestado` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `presupuestos`
--

INSERT INTO `presupuestos` (`id`, `nombre`, `mes`, `anio`, `monto_presupuestado`) VALUES
(1, 'Gastos Combustible', 5, 2024, 150000.00),
(2, 'Gastos Generales', 5, 2024, 50000.00),
(3, 'Gastos Combustible', 6, 2024, 155000.00),
(4, 'Gastos Generales', 6, 2024, 50000.00),
(5, 'Gastos Combustible', 7, 2024, 160000.00),
(6, 'Gastos Generales', 7, 2024, 55000.00),
(7, 'Gastos Combustible', 8, 2024, 165000.00),
(8, 'Gastos Generales', 8, 2024, 50000.00),
(9, 'Gastos Combustible', 9, 2024, 170000.00),
(10, 'Gastos Generales', 9, 2024, 60000.00),
(11, 'Gastos Combustible', 10, 2024, 175000.00),
(12, 'Gastos Generales', 10, 2024, 50000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `sku`, `nombre`, `descripcion`) VALUES
(1, 'TOR-001', 'Tornillos M8', 'Caja de 1000u'),
(2, 'ACE-002', 'Aceite Motor 10W40', 'Bidón de 5L'),
(3, 'LLA-003', 'Llantas 205/55 R16', 'Unidad'),
(4, 'FIL-004', 'Filtro de Aire', 'Unidad - Modelo HILUX'),
(5, 'SKU-DEMO-100', 'Producto Demo 100', 'Descripción genérica'),
(6, 'SKU-DEMO-101', 'Producto Demo 101', 'Descripción genérica'),
(7, 'SKU-DEMO-102', 'Producto Demo 102', 'Descripción genérica'),
(8, 'SKU-DEMO-103', 'Producto Demo 103', 'Descripción genérica'),
(9, 'SKU-DEMO-104', 'Producto Demo 104', 'Descripción genérica'),
(10, 'SKU-DEMO-105', 'Producto Demo 105', 'Descripción genérica'),
(11, 'SKU-DEMO-106', 'Producto Demo 106', 'Descripción genérica'),
(12, 'SKU-DEMO-107', 'Producto Demo 107', 'Descripción genérica'),
(13, 'SKU-DEMO-108', 'Producto Demo 108', 'Descripción genérica'),
(14, 'SKU-DEMO-109', 'Producto Demo 109', 'Descripción genérica'),
(15, 'SKU-DEMO-110', 'Producto Demo 110', 'Descripción genérica'),
(16, 'SKU-DEMO-111', 'Producto Demo 111', 'Descripción genérica'),
(17, 'SKU-DEMO-112', 'Producto Demo 112', 'Descripción genérica'),
(18, 'SKU-DEMO-113', 'Producto Demo 113', 'Descripción genérica'),
(19, 'SKU-DEMO-114', 'Producto Demo 114', 'Descripción genérica'),
(20, 'SKU-DEMO-115', 'Producto Demo 115', 'Descripción genérica'),
(21, 'SKU-DEMO-116', 'Producto Demo 116', 'Descripción genérica'),
(22, 'SKU-DEMO-117', 'Producto Demo 117', 'Descripción genérica'),
(23, 'SKU-DEMO-118', 'Producto Demo 118', 'Descripción genérica'),
(24, 'SKU-DEMO-119', 'Producto Demo 119', 'Descripción genérica'),
(25, 'SKU-DEMO-120', 'Producto Demo 120', 'Descripción genérica'),
(26, 'SKU-DEMO-121', 'Producto Demo 121', 'Descripción genérica'),
(27, 'SKU-DEMO-122', 'Producto Demo 122', 'Descripción genérica'),
(28, 'SKU-DEMO-123', 'Producto Demo 123', 'Descripción genérica'),
(29, 'SKU-DEMO-124', 'Producto Demo 124', 'Descripción genérica'),
(30, 'SKU-DEMO-125', 'Producto Demo 125', 'Descripción genérica'),
(31, 'SKU-DEMO-126', 'Producto Demo 126', 'Descripción genérica'),
(32, 'SKU-DEMO-127', 'Producto Demo 127', 'Descripción genérica'),
(33, 'SKU-DEMO-128', 'Producto Demo 128', 'Descripción genérica'),
(34, 'SKU-DEMO-129', 'Producto Demo 129', 'Descripción genérica'),
(35, 'SKU-DEMO-130', 'Producto Demo 130', 'Descripción genérica'),
(36, 'SKU-DEMO-131', 'Producto Demo 131', 'Descripción genérica'),
(37, 'SKU-DEMO-132', 'Producto Demo 132', 'Descripción genérica'),
(38, 'SKU-DEMO-133', 'Producto Demo 133', 'Descripción genérica'),
(39, 'SKU-DEMO-134', 'Producto Demo 134', 'Descripción genérica'),
(40, 'SKU-DEMO-135', 'Producto Demo 135', 'Descripción genérica'),
(41, 'SKU-DEMO-136', 'Producto Demo 136', 'Descripción genérica'),
(42, 'SKU-DEMO-137', 'Producto Demo 137', 'Descripción genérica'),
(43, 'SKU-DEMO-138', 'Producto Demo 138', 'Descripción genérica'),
(44, 'SKU-DEMO-139', 'Producto Demo 139', 'Descripción genérica'),
(45, 'SKU-DEMO-140', 'Producto Demo 140', 'Descripción genérica'),
(46, 'SKU-DEMO-141', 'Producto Demo 141', 'Descripción genérica'),
(47, 'SKU-DEMO-142', 'Producto Demo 142', 'Descripción genérica'),
(48, 'SKU-DEMO-143', 'Producto Demo 143', 'Descripción genérica'),
(49, 'SKU-DEMO-144', 'Producto Demo 144', 'Descripción genérica'),
(50, 'SKU-DEMO-145', 'Producto Demo 145', 'Descripción genérica'),
(51, 'SKU-DEMO-146', 'Producto Demo 146', 'Descripción genérica'),
(52, 'SKU-DEMO-147', 'Producto Demo 147', 'Descripción genérica'),
(53, 'SKU-DEMO-148', 'Producto Demo 148', 'Descripción genérica'),
(54, 'SKU-DEMO-149', 'Producto Demo 149', 'Descripción genérica'),
(55, 'SKU-DEMO-150', 'Producto Demo 150', 'Descripción genérica'),
(56, 'SKU-DEMO-151', 'Producto Demo 151', 'Descripción genérica'),
(57, 'SKU-DEMO-152', 'Producto Demo 152', 'Descripción genérica'),
(58, 'SKU-DEMO-153', 'Producto Demo 153', 'Descripción genérica'),
(59, 'SKU-DEMO-154', 'Producto Demo 154', 'Descripción genérica'),
(60, 'SKU-DEMO-155', 'Producto Demo 155', 'Descripción genérica'),
(61, 'SKU-DEMO-156', 'Producto Demo 156', 'Descripción genérica'),
(62, 'SKU-DEMO-157', 'Producto Demo 157', 'Descripción genérica'),
(63, 'SKU-DEMO-158', 'Producto Demo 158', 'Descripción genérica'),
(64, 'SKU-DEMO-159', 'Producto Demo 159', 'Descripción genérica'),
(65, 'SKU-DEMO-160', 'Producto Demo 160', 'Descripción genérica'),
(66, 'SKU-DEMO-161', 'Producto Demo 161', 'Descripción genérica'),
(67, 'SKU-DEMO-162', 'Producto Demo 162', 'Descripción genérica'),
(68, 'SKU-DEMO-163', 'Producto Demo 163', 'Descripción genérica'),
(69, 'SKU-DEMO-164', 'Producto Demo 164', 'Descripción genérica'),
(70, 'SKU-DEMO-165', 'Producto Demo 165', 'Descripción genérica'),
(71, 'SKU-DEMO-166', 'Producto Demo 166', 'Descripción genérica'),
(72, 'SKU-DEMO-167', 'Producto Demo 167', 'Descripción genérica'),
(73, 'SKU-DEMO-168', 'Producto Demo 168', 'Descripción genérica'),
(74, 'SKU-DEMO-169', 'Producto Demo 169', 'Descripción genérica'),
(75, 'SKU-DEMO-170', 'Producto Demo 170', 'Descripción genérica'),
(76, 'SKU-DEMO-171', 'Producto Demo 171', 'Descripción genérica'),
(77, 'SKU-DEMO-172', 'Producto Demo 172', 'Descripción genérica'),
(78, 'SKU-DEMO-173', 'Producto Demo 173', 'Descripción genérica'),
(79, 'SKU-DEMO-174', 'Producto Demo 174', 'Descripción genérica'),
(80, 'SKU-DEMO-175', 'Producto Demo 175', 'Descripción genérica'),
(81, 'SKU-DEMO-176', 'Producto Demo 176', 'Descripción genérica'),
(82, 'SKU-DEMO-177', 'Producto Demo 177', 'Descripción genérica'),
(83, 'SKU-DEMO-178', 'Producto Demo 178', 'Descripción genérica'),
(84, 'SKU-DEMO-179', 'Producto Demo 179', 'Descripción genérica'),
(85, 'SKU-DEMO-180', 'Producto Demo 180', 'Descripción genérica'),
(86, 'SKU-DEMO-181', 'Producto Demo 181', 'Descripción genérica'),
(87, 'SKU-DEMO-182', 'Producto Demo 182', 'Descripción genérica'),
(88, 'SKU-DEMO-183', 'Producto Demo 183', 'Descripción genérica'),
(89, 'SKU-DEMO-184', 'Producto Demo 184', 'Descripción genérica'),
(90, 'SKU-DEMO-185', 'Producto Demo 185', 'Descripción genérica'),
(91, 'SKU-DEMO-186', 'Producto Demo 186', 'Descripción genérica'),
(92, 'SKU-DEMO-187', 'Producto Demo 187', 'Descripción genérica'),
(93, 'SKU-DEMO-188', 'Producto Demo 188', 'Descripción genérica'),
(94, 'SKU-DEMO-189', 'Producto Demo 189', 'Descripción genérica'),
(95, 'SKU-DEMO-190', 'Producto Demo 190', 'Descripción genérica'),
(96, 'SKU-DEMO-191', 'Producto Demo 191', 'Descripción genérica'),
(97, 'SKU-DEMO-192', 'Producto Demo 192', 'Descripción genérica'),
(98, 'SKU-DEMO-193', 'Producto Demo 193', 'Descripción genérica'),
(99, 'SKU-DEMO-194', 'Producto Demo 194', 'Descripción genérica'),
(100, 'SKU-DEMO-195', 'Producto Demo 195', 'Descripción genérica'),
(101, 'SKU-DEMO-196', 'Producto Demo 196', 'Descripción genérica'),
(102, 'SKU-DEMO-197', 'Producto Demo 197', 'Descripción genérica'),
(103, 'SKU-DEMO-198', 'Producto Demo 198', 'Descripción genérica'),
(104, 'SKU-DEMO-199', 'Producto Demo 199', 'Descripción genérica'),
(106, 'ACE-0045', 'ACE-0045', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre_rol`) VALUES
(1, 'Administrador'),
(4, 'Guardia'),
(3, 'Solicitante'),
(2, 'Supervisor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permisos`
--

CREATE TABLE `rol_permisos` (
  `rol_id` int(11) NOT NULL,
  `permiso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol_permisos`
--

INSERT INTO `rol_permisos` (`rol_id`, `permiso_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 34),
(1, 35),
(1, 36),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(3, 7),
(3, 9),
(4, 4),
(4, 5),
(4, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutas`
--

CREATE TABLE `rutas` (
  `id` int(11) NOT NULL,
  `nombre_ruta` varchar(150) NOT NULL,
  `fecha_planificada` date NOT NULL,
  `vehiculo_id` int(11) NOT NULL,
  `conductor_id` int(11) NOT NULL COMMENT 'FK a usuarios.id',
  `estado` enum('PLANIFICADA','EN_CURSO','COMPLETADA','CANCELADA') NOT NULL DEFAULT 'PLANIFICADA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rutas`
--

INSERT INTO `rutas` (`id`, `nombre_ruta`, `fecha_planificada`, `vehiculo_id`, `conductor_id`, `estado`) VALUES
(1, 'Reparto Zona Norte', '2025-10-12', 1, 19, 'COMPLETADA'),
(2, 'Reparto Zona Sur', '2025-11-01', 2, 20, 'EN_CURSO'),
(3, 'Reparto CABA', '2025-11-02', 1, 19, 'PLANIFICADA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_gasto`
--

CREATE TABLE `tipos_gasto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_gasto`
--

INSERT INTO `tipos_gasto` (`id`, `nombre`) VALUES
(1, 'Combustible'),
(4, 'Mantenimiento Vehicular'),
(2, 'Peajes'),
(3, 'Viáticos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firma_path` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `user`, `password_hash`, `rol_id`, `nombre_completo`, `email`, `firma_path`, `activo`) VALUES
(16, 'guardia_demo', '$2y$10$T.s.N/1w.s2.3vU30.1vROncl3E3..2mS.t9D8E.Y.aG8.tS.tS2S', 4, 'Guardia Demo (Turno Mañana)', 'guardia@sgal.com', NULL, 1),
(17, 'solicitante_demo', '$2y$10$T.s.N/1w.s2.3vU30.1vROncl3E3..2mS.t9D8E.Y.aG8.tS.tS2S', 3, 'Solicitante Demo (Pañol)', 'solicitante@sgal.com', NULL, 1),
(18, 'supervisor_demo', '$2y$10$T.s.N/1w.s2.3vU30.1vROncl3E3..2mS.t9D8E.Y.aG8.tS.tS2S', 2, 'Supervisor Demo (Logística)', 'supervisor@sgal.com', NULL, 1),
(19, 'conductor_demo_a', '$2y$10$Syn84.TnPOVSWEjpCs9KGOWFKNTq9akyMRFIOP4KZCrHbMjkkeOoK', 3, 'Conductor Demo (Reparto A)', 'conductor.a@sgal.com', NULL, 1),
(20, 'conductor_demo_b', '$2y$10$T.s.N/1w.s2.3vU30.1vROncl3E3..2mS.t9D8E.Y.aG8.tS.tS2S', 3, 'Conductor Demo (Reparto B)', 'conductor.b@sgal.com', NULL, 1),
(22, 'admin', '$2y$10$jPrBnfKoJDf5aE8Nrns6tu9Z5stelGjXkT4jejRzm6NiWoEjY2K0G', 1, 'Administrador del Sistema', 'admin@gmail.com', '22.png', 1),
(24, 'fede', '$2y$10$/wRtkMuOW2Noi2.DjY9xtODQ3mCWECakrmsi.BOscHfVPVxTAbCQa', 1, 'fede', 'mcanete@gmail.com', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `placa`, `marca`, `modelo`, `anio`) VALUES
(1, 'AA123BB', 'Ford', 'Ranger', 2021),
(2, 'AC456DE', 'Mercedes-Benz', 'Sprinter', 2022),
(3, 'AD789FG', 'Toyota', 'Hilux', 2023);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_registro_id` (`usuario_registro_id`),
  ADD KEY `camara_vinculada_id` (`camara_vinculada_id`);

--
-- Indices de la tabla `almacenes`
--
ALTER TABLE `almacenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `camaras`
--
ALTER TABLE `camaras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion_global`
--
ALTER TABLE `configuracion_global`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indices de la tabla `costos_predictivos`
--
ALTER TABLE `costos_predictivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ruta_id` (`ruta_id`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_carga_id` (`usuario_carga_id`);

--
-- Indices de la tabla `facturacion`
--
ALTER TABLE `facturacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_gasto_id` (`tipo_gasto_id`),
  ADD KEY `vehiculo_id` (`vehiculo_id`),
  ADD KEY `ruta_id` (`ruta_id`),
  ADD KEY `usuario_registro_id` (`usuario_registro_id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_producto_almacen` (`producto_id`,`almacen_id`),
  ADD KEY `almacen_id` (`almacen_id`);

--
-- Indices de la tabla `log_sistema`
--
ALTER TABLE `log_sistema`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `almacen_origen_id` (`almacen_origen_id`),
  ADD KEY `almacen_destino_id` (`almacen_destino_id`),
  ADD KEY `usuario_solicitud_id` (`usuario_solicitud_id`),
  ADD KEY `usuario_aprobacion_id` (`usuario_aprobacion_id`);

--
-- Indices de la tabla `paradas_ruta`
--
ALTER TABLE `paradas_ruta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ruta_id` (`ruta_id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave_permiso` (`clave_permiso`);

--
-- Indices de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_mes_anio` (`mes`,`anio`,`nombre`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `rol_permisos`
--
ALTER TABLE `rol_permisos`
  ADD PRIMARY KEY (`rol_id`,`permiso_id`),
  ADD KEY `permiso_id` (`permiso_id`);

--
-- Indices de la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehiculo_id` (`vehiculo_id`),
  ADD KEY `conductor_id` (`conductor_id`);

--
-- Indices de la tabla `tipos_gasto`
--
ALTER TABLE `tipos_gasto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `placa` (`placa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesos`
--
ALTER TABLE `accesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `almacenes`
--
ALTER TABLE `almacenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `camaras`
--
ALTER TABLE `camaras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `configuracion_global`
--
ALTER TABLE `configuracion_global`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `costos_predictivos`
--
ALTER TABLE `costos_predictivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturacion`
--
ALTER TABLE `facturacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=521;

--
-- AUTO_INCREMENT de la tabla `log_sistema`
--
ALTER TABLE `log_sistema`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `paradas_ruta`
--
ALTER TABLE `paradas_ruta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rutas`
--
ALTER TABLE `rutas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipos_gasto`
--
ALTER TABLE `tipos_gasto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD CONSTRAINT `accesos_ibfk_1` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `accesos_ibfk_2` FOREIGN KEY (`camara_vinculada_id`) REFERENCES `camaras` (`id`);

--
-- Filtros para la tabla `costos_predictivos`
--
ALTER TABLE `costos_predictivos`
  ADD CONSTRAINT `costos_predictivos_ibfk_1` FOREIGN KEY (`ruta_id`) REFERENCES `rutas` (`id`);

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`usuario_carga_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `facturacion`
--
ALTER TABLE `facturacion`
  ADD CONSTRAINT `facturacion_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`tipo_gasto_id`) REFERENCES `tipos_gasto` (`id`),
  ADD CONSTRAINT `gastos_ibfk_2` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`),
  ADD CONSTRAINT `gastos_ibfk_3` FOREIGN KEY (`ruta_id`) REFERENCES `rutas` (`id`),
  ADD CONSTRAINT `gastos_ibfk_4` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`almacen_id`) REFERENCES `almacenes` (`id`);

--
-- Filtros para la tabla `log_sistema`
--
ALTER TABLE `log_sistema`
  ADD CONSTRAINT `log_sistema_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  ADD CONSTRAINT `movimientos_stock_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `movimientos_stock_ibfk_2` FOREIGN KEY (`almacen_origen_id`) REFERENCES `almacenes` (`id`),
  ADD CONSTRAINT `movimientos_stock_ibfk_3` FOREIGN KEY (`almacen_destino_id`) REFERENCES `almacenes` (`id`),
  ADD CONSTRAINT `movimientos_stock_ibfk_4` FOREIGN KEY (`usuario_solicitud_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `movimientos_stock_ibfk_5` FOREIGN KEY (`usuario_aprobacion_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `paradas_ruta`
--
ALTER TABLE `paradas_ruta`
  ADD CONSTRAINT `paradas_ruta_ibfk_1` FOREIGN KEY (`ruta_id`) REFERENCES `rutas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paradas_ruta_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `rol_permisos`
--
ALTER TABLE `rol_permisos`
  ADD CONSTRAINT `rol_permisos_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rol_permisos_ibfk_2` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD CONSTRAINT `rutas_ibfk_1` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`),
  ADD CONSTRAINT `rutas_ibfk_2` FOREIGN KEY (`conductor_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
