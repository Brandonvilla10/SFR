-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 26-05-2025 a las 11:55:07
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `databasesft`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallematricula`
--

CREATE TABLE `detallematricula` (
  `id_detalle` int NOT NULL,
  `id_ficha` int NOT NULL,
  `id_documento` int NOT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detallematricula`
--

INSERT INTO `detallematricula` (`id_detalle`, `id_ficha`, `id_documento`, `id_estado`) VALUES
(1, 2901879, 1107978187, 1),
(2, 2901870, 1223456780, 1),
(3, 2901879, 1034567891, 1),
(4, 2901870, 1206227450, 1),
(5, 2901879, 1212345679, 1),
(6, 2901870, 1107978187, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int NOT NULL,
  `estado` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `estado`) VALUES
(1, 'Activo'),
(2, 'Inactivo'),
(3, 'Dentro'),
(4, 'Eliminado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichas`
--

CREATE TABLE `fichas` (
  `n_ficha` int NOT NULL,
  `dias` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_ini` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `id_programaFormacion` int DEFAULT NULL,
  `id_nivelFormacion` int DEFAULT NULL,
  `id_jornada` int DEFAULT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fichas`
--

INSERT INTO `fichas` (`n_ficha`, `dias`, `fecha_ini`, `fecha_fin`, `id_programaFormacion`, `id_nivelFormacion`, `id_jornada`, `id_estado`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2901870, NULL, NULL, NULL, 13, 2, 3, 1),
(2901879, NULL, NULL, NULL, 1, 3, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jornada`
--

CREATE TABLE `jornada` (
  `id_jornada` int NOT NULL,
  `jornada` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `jornada`
--

INSERT INTO `jornada` (`id_jornada`, `jornada`) VALUES
(1, 'Diurna'),
(2, 'Mixta'),
(3, 'Nocturna');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id_marca` int NOT NULL,
  `nombre_marca` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_tipo_marca` int NOT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id_marca`, `nombre_marca`, `id_tipo_marca`, `id_estado`) VALUES
(1, 'Yamaha', 1, 4),
(2, 'Fender', 2, 4),
(3, 'Gibson', 2, 4),
(4, 'Roland', 2, 1),
(5, 'Casio', 2, 1),
(6, 'Chevrolet', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo`
--

CREATE TABLE `modelo` (
  `id_modelo` int NOT NULL,
  `fecha_modelo` year NOT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modelo`
--

INSERT INTO `modelo` (`id_modelo`, `fecha_modelo`, `id_estado`) VALUES
(1, '2025', 4),
(2, '2015', 1),
(3, '1901', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivelformacion`
--

CREATE TABLE `nivelformacion` (
  `id_nivelFormacion` int NOT NULL,
  `nivelDeformacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nivelformacion`
--

INSERT INTO `nivelformacion` (`id_nivelFormacion`, `nivelDeformacion`, `id_estado`) VALUES
(1, 'Auxiliar', 1),
(2, 'Operario', 1),
(3, 'Técnico', 1),
(4, 'Tecnólogo', 1),
(5, 'Especialización Tecnológica', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int NOT NULL,
  `fecha` datetime NOT NULL,
  `tipo_evento` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tabla_afectada` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_afectado` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `documento_generador` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id_notificacion`, `fecha`, `tipo_evento`, `tabla_afectada`, `id_afectado`, `descripcion`, `documento_generador`, `leida`) VALUES
(1, '0000-00-00 00:00:00', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(2, '0000-00-00 00:00:00', 'DELETE', 'vehiculos', 'MSW32Y', 'Vehículo eliminado con placa: MSW32Y', NULL, 0),
(41, '2025-04-30 07:50:46', 'DELETE', 'objetos', 'OBJ1015', 'Objeto eliminado: Tablet iPad Pro 12.9”', NULL, 0),
(42, '2025-04-30 07:50:51', 'INSERT', 'objetos', '1001', 'Nuevo objeto registrado: Guitarra Acústica', NULL, 0),
(43, '2025-04-30 07:50:51', 'INSERT', 'objetos', '1002', 'Nuevo objeto registrado: Bajo Eléctricoo', NULL, 0),
(44, '2025-04-30 07:50:51', 'INSERT', 'objetos', '1003', 'Nuevo objeto registrado: Guitarra Eléctrica', NULL, 0),
(45, '2025-04-30 07:50:51', 'INSERT', 'objetos', '1004', 'Nuevo objeto registrado: Bajo Eléctrico', NULL, 0),
(46, '2025-04-30 07:50:51', 'INSERT', 'objetos', '1005', 'Nuevo objeto registrado: Piano Digital', NULL, 0),
(47, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1001', 'Nuevo objeto registrado: Laptop Dell XPS', NULL, 0),
(48, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1002', 'Nuevo objeto registrado: Smartphone Samsung S22', NULL, 0),
(49, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1003', 'Nuevo objeto registrado: Bicicleta Montaña', NULL, 0),
(50, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1004', 'Nuevo objeto registrado: Guitarra Fender Stratocaster', NULL, 0),
(51, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1005', 'Nuevo objeto registrado: TV LG 55” 4K', NULL, 0),
(52, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1006', 'Nuevo objeto registrado: Monitor Samsung 27”', NULL, 0),
(53, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1007', 'Nuevo objeto registrado: Teclado Mecánico Razer', NULL, 0),
(54, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1008', 'Nuevo objeto registrado: Mouse Logitech G502', NULL, 0),
(55, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1009', 'Nuevo objeto registrado: Silla Gamer DXRacer', NULL, 0),
(56, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1010', 'Nuevo objeto registrado: Cámara Sony Alpha A7', NULL, 0),
(57, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1011', 'Nuevo objeto registrado: Consola PlayStation 5', NULL, 0),
(58, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1012', 'Nuevo objeto registrado: Refrigerador Samsung', NULL, 0),
(59, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1013', 'Nuevo objeto registrado: Aire Acondicionado LG', NULL, 0),
(60, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1014', 'Nuevo objeto registrado: Dron DJI Mini 3', NULL, 0),
(61, '2025-04-30 07:50:51', 'INSERT', 'objetos', 'OBJ1015', 'Nuevo objeto registrado: Tablet iPad Pro 12.9”', NULL, 0),
(62, '2025-04-30 07:55:37', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(63, '2025-04-30 18:56:01', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(64, '2025-04-30 18:56:07', 'UPDATE', 'vehiculos', 'ZSA321', 'Vehículo actualizado con placa: ZSA321', NULL, 0),
(65, '2025-04-30 18:59:47', 'INSERT', 'registroentreadapersona', '1', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(66, '2025-04-30 19:01:12', 'INSERT', 'registro_entrada_objetos', '1', 'Ingreso de objeto con serial: 1003', NULL, 0),
(67, '2025-04-30 19:01:12', 'INSERT', 'registro_entrada_objetos', '2', 'Ingreso de objeto con serial: 1004', NULL, 0),
(68, '2025-04-30 19:01:12', 'INSERT', 'registro_entrada_vehiculos', '1', 'Ingreso de vehículo con placa: DFH23H', NULL, 0),
(69, '2025-04-30 19:01:12', 'INSERT', 'registro_entrada_vehiculos', '2', 'Ingreso de vehículo con placa: ZSA321', NULL, 0),
(70, '2025-04-30 19:02:56', 'INSERT', 'registroentreadapersona', '2', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(71, '2025-04-30 19:02:56', 'INSERT', 'registro_entrada_objetos', '3', 'Ingreso de objeto con serial: 1002', NULL, 0),
(72, '2025-04-30 19:02:56', 'INSERT', 'registro_entrada_objetos', '4', 'Ingreso de objeto con serial: 1003', NULL, 0),
(73, '2025-04-30 19:02:56', 'INSERT', 'registro_entrada_objetos', '5', 'Ingreso de objeto con serial: 1004', NULL, 0),
(74, '2025-04-30 19:02:56', 'INSERT', 'registro_entrada_vehiculos', '3', 'Ingreso de vehículo con placa: DFH23H', NULL, 0),
(75, '2025-05-01 07:51:42', 'INSERT', 'registroentreadapersona', '3', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(76, '2025-05-01 07:51:42', 'INSERT', 'registro_entrada_objetos', '6', 'Ingreso de objeto con serial: 1001', NULL, 0),
(77, '2025-05-01 07:53:57', 'INSERT', 'registroentreadapersona', '4', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(78, '2025-05-01 07:53:57', 'INSERT', 'registro_entrada_objetos', '7', 'Ingreso de objeto con serial: 1002', NULL, 0),
(79, '2025-05-01 07:53:57', 'INSERT', 'registro_entrada_objetos', '8', 'Ingreso de objeto con serial: 1003', NULL, 0),
(80, '2025-05-01 07:55:38', 'INSERT', 'registroentreadapersona', '5', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(81, '2025-05-01 07:55:39', 'INSERT', 'registro_entrada_objetos', '9', 'Ingreso de objeto con serial: 1002', NULL, 0),
(82, '2025-05-01 07:56:21', 'INSERT', 'registro_entrada_objetos', '10', 'Ingreso de objeto con serial: 1001', NULL, 0),
(83, '2025-05-01 07:57:42', 'INSERT', 'registroentreadapersona', '6', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(84, '2025-05-01 07:57:42', 'INSERT', 'registro_entrada_objetos', '11', 'Ingreso de objeto con serial: 1002', NULL, 0),
(85, '2025-05-01 07:58:13', 'INSERT', 'registroentreadapersona', '7', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(86, '2025-05-01 07:58:13', 'INSERT', 'registro_entrada_objetos', '12', 'Ingreso de objeto con serial: 1001', NULL, 0),
(87, '2025-05-01 07:58:25', 'INSERT', 'registro_entrada_objetos', '13', 'Ingreso de objeto con serial: 1002', NULL, 0),
(88, '2025-05-01 07:58:58', 'INSERT', 'registro_entrada_objetos', '14', 'Ingreso de objeto con serial: 1001', NULL, 0),
(89, '2025-05-01 08:07:04', 'INSERT', 'registroentreadapersona', '8', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(90, '2025-05-01 08:07:04', 'INSERT', 'registro_entrada_objetos', '15', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(91, '2025-05-01 08:07:04', 'INSERT', 'registro_entrada_objetos', '16', 'Ingreso de objeto con serial: OBJ1005', NULL, 0),
(92, '2025-05-02 07:52:34', 'INSERT', 'registroentreadapersona', '9', 'Ingreso de persona con documento: 1106227432', NULL, 0),
(93, '2025-05-02 07:56:25', 'INSERT', 'registroentreadapersona', '10', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(94, '2025-05-02 07:56:44', 'INSERT', 'registroentreadapersona', '11', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(95, '2025-05-02 08:47:43', 'INSERT', 'registro_entrada_objetos', '17', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(96, '2025-05-02 08:47:43', 'INSERT', 'registro_entrada_objetos', '18', 'Ingreso de objeto con serial: OBJ1002', NULL, 0),
(97, '2025-05-02 08:51:26', 'INSERT', 'registroentreadapersona', '12', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(98, '2025-05-02 08:53:07', 'INSERT', 'registro_entrada_objetos', '19', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(99, '2025-05-02 08:53:07', 'INSERT', 'registro_entrada_objetos', '20', 'Ingreso de objeto con serial: OBJ1002', NULL, 0),
(100, '2025-05-02 08:55:44', 'INSERT', 'registroentreadapersona', '13', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(101, '2025-05-04 09:17:20', 'INSERT', 'registroentreadapersona', '14', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(102, '2025-05-04 09:17:20', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(103, '2025-05-04 09:17:20', 'INSERT', 'registro_entrada_objetos', '21', 'Ingreso de objeto con serial: OBJ1002', NULL, 0),
(104, '2025-05-04 09:17:20', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(105, '2025-05-04 09:17:56', 'INSERT', 'registro_entrada_objetos', '22', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(106, '2025-05-04 09:17:56', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(107, '2025-05-04 09:17:56', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(108, '2025-05-04 09:18:09', 'INSERT', 'registroentreadapersona', '15', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(109, '2025-05-04 09:18:09', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(110, '2025-05-04 09:18:31', 'INSERT', 'registroentreadapersona', '16', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(111, '2025-05-04 09:18:31', 'INSERT', 'registro_entrada_objetos', '23', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(112, '2025-05-04 09:18:31', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(113, '2025-05-04 09:21:31', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(114, '2025-05-04 14:26:09', 'INSERT', 'registroentreadapersona', '17', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(115, '2025-05-04 14:26:09', 'INSERT', 'registro_entrada_objetos', '24', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(116, '2025-05-04 14:26:09', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(117, '2025-05-04 14:26:09', 'INSERT', 'registro_entrada_objetos', '25', 'Ingreso de objeto con serial: OBJ1002', NULL, 0),
(118, '2025-05-04 14:26:09', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(119, '2025-05-04 14:26:09', 'INSERT', 'registro_entrada_objetos', '26', 'Ingreso de objeto con serial: OBJ1003', NULL, 0),
(120, '2025-05-04 14:26:09', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(121, '2025-05-04 14:27:23', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(122, '2025-05-04 14:27:23', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(123, '2025-05-04 14:27:23', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(124, '2025-05-04 15:05:35', 'INSERT', 'registroentreadapersona', '18', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(125, '2025-05-04 15:05:35', 'INSERT', 'registro_entrada_objetos', '27', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(126, '2025-05-04 15:05:35', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(127, '2025-05-04 15:06:11', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(128, '2025-05-04 15:06:11', 'INSERT', 'registro_entrada_objetos', '28', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(129, '2025-05-04 15:06:11', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(130, '2025-05-04 15:06:11', 'INSERT', 'registro_entrada_objetos', '29', 'Ingreso de objeto con serial: OBJ1007', NULL, 0),
(131, '2025-05-04 15:06:11', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(132, '2025-05-04 15:06:11', 'INSERT', 'registro_entrada_objetos', '30', 'Ingreso de objeto con serial: OBJ1008', NULL, 0),
(133, '2025-05-04 15:06:11', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: Mouse Logitech G502', NULL, 0),
(134, '2025-05-04 15:06:11', 'INSERT', 'registro_entrada_objetos', '31', 'Ingreso de objeto con serial: OBJ1009', NULL, 0),
(135, '2025-05-04 15:06:11', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(136, '2025-05-04 15:06:11', 'INSERT', 'registro_entrada_objetos', '32', 'Ingreso de objeto con serial: OBJ1010', NULL, 0),
(137, '2025-05-04 15:06:11', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(138, '2025-05-04 15:06:11', 'INSERT', 'registro_entrada_objetos', '33', 'Ingreso de objeto con serial: OBJ1011', NULL, 0),
(139, '2025-05-04 15:06:11', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(140, '2025-05-04 15:08:11', 'INSERT', 'registroentreadapersona', '19', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(141, '2025-05-04 15:08:11', 'INSERT', 'registro_entrada_objetos', '34', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(142, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(143, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(144, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(145, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: Mouse Logitech G502', NULL, 0),
(146, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(147, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(148, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(149, '2025-05-04 15:08:11', 'INSERT', 'registro_entrada_objetos', '35', 'Ingreso de objeto con serial: OBJ1015', NULL, 0),
(150, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1015', 'Objeto actualizado: Tablet iPad Pro 12.9”', NULL, 0),
(151, '2025-05-04 15:08:11', 'INSERT', 'registro_entrada_objetos', '36', 'Ingreso de objeto con serial: OBJ1014', NULL, 0),
(152, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1014', 'Objeto actualizado: Dron DJI Mini 3', NULL, 0),
(153, '2025-05-04 15:08:11', 'INSERT', 'registro_entrada_objetos', '37', 'Ingreso de objeto con serial: OBJ1013', NULL, 0),
(154, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(155, '2025-05-04 15:08:11', 'INSERT', 'registro_entrada_objetos', '38', 'Ingreso de objeto con serial: OBJ1012', NULL, 0),
(156, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1012', 'Objeto actualizado: Refrigerador Samsung', NULL, 0),
(157, '2025-05-04 15:08:11', 'INSERT', 'registro_entrada_objetos', '39', 'Ingreso de objeto con serial: OBJ1005', NULL, 0),
(158, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: TV LG 55” 4K', NULL, 0),
(159, '2025-05-04 15:08:11', 'INSERT', 'registro_entrada_objetos', '40', 'Ingreso de objeto con serial: OBJ1004', NULL, 0),
(160, '2025-05-04 15:08:11', 'UPDATE', 'objetos', 'OBJ1004', 'Objeto actualizado: Guitarra Fender Stratocaster', NULL, 0),
(161, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(162, '2025-05-04 15:09:37', 'INSERT', 'registro_entrada_objetos', '41', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(163, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(164, '2025-05-04 15:09:37', 'INSERT', 'registro_entrada_objetos', '42', 'Ingreso de objeto con serial: OBJ1007', NULL, 0),
(165, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(166, '2025-05-04 15:09:37', 'INSERT', 'registro_entrada_objetos', '43', 'Ingreso de objeto con serial: OBJ1008', NULL, 0),
(167, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: Mouse Logitech G502', NULL, 0),
(168, '2025-05-04 15:09:37', 'INSERT', 'registro_entrada_objetos', '44', 'Ingreso de objeto con serial: OBJ1009', NULL, 0),
(169, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(170, '2025-05-04 15:09:37', 'INSERT', 'registro_entrada_objetos', '45', 'Ingreso de objeto con serial: OBJ1010', NULL, 0),
(171, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(172, '2025-05-04 15:09:37', 'INSERT', 'registro_entrada_objetos', '46', 'Ingreso de objeto con serial: OBJ1011', NULL, 0),
(173, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(174, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1015', 'Objeto actualizado: Tablet iPad Pro 12.9”', NULL, 0),
(175, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1014', 'Objeto actualizado: Dron DJI Mini 3', NULL, 0),
(176, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(177, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1012', 'Objeto actualizado: Refrigerador Samsung', NULL, 0),
(178, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: TV LG 55” 4K', NULL, 0),
(179, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1004', 'Objeto actualizado: Guitarra Fender Stratocaster', NULL, 0),
(180, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(181, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(182, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(183, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: Mouse Logitech G502', NULL, 0),
(184, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(185, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(186, '2025-05-04 15:09:37', 'INSERT', 'registro_entrada_objetos', '47', 'Ingreso de objeto con serial: OBJ1003', NULL, 0),
(187, '2025-05-04 15:09:37', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(188, '2025-05-04 15:11:26', 'INSERT', 'registroentreadapersona', '20', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(189, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '48', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(190, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(191, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '49', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(192, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(193, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '50', 'Ingreso de objeto con serial: OBJ1007', NULL, 0),
(194, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(195, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '51', 'Ingreso de objeto con serial: OBJ1008', NULL, 0),
(196, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: Mouse Logitech G502', NULL, 0),
(197, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '52', 'Ingreso de objeto con serial: OBJ1009', NULL, 0),
(198, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(199, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '53', 'Ingreso de objeto con serial: OBJ1010', NULL, 0),
(200, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(201, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '54', 'Ingreso de objeto con serial: OBJ1011', NULL, 0),
(202, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(203, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '55', 'Ingreso de objeto con serial: OBJ1012', NULL, 0),
(204, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1012', 'Objeto actualizado: Refrigerador Samsung', NULL, 0),
(205, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '56', 'Ingreso de objeto con serial: OBJ1013', NULL, 0),
(206, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(207, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '57', 'Ingreso de objeto con serial: OBJ1014', NULL, 0),
(208, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1014', 'Objeto actualizado: Dron DJI Mini 3', NULL, 0),
(209, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '58', 'Ingreso de objeto con serial: OBJ1015', NULL, 0),
(210, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1015', 'Objeto actualizado: Tablet iPad Pro 12.9”', NULL, 0),
(211, '2025-05-04 15:11:26', 'INSERT', 'registro_entrada_objetos', '59', 'Ingreso de objeto con serial: OBJ1002', NULL, 0),
(212, '2025-05-04 15:11:26', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(213, '2025-05-04 15:11:59', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(214, '2025-05-04 15:11:59', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(215, '2025-05-04 15:11:59', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(216, '2025-05-04 15:11:59', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: Mouse Logitech G502', NULL, 0),
(217, '2025-05-04 15:11:59', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(218, '2025-05-04 15:11:59', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(219, '2025-05-04 15:11:59', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(220, '2025-05-04 15:11:59', 'UPDATE', 'objetos', 'OBJ1012', 'Objeto actualizado: Refrigerador Samsung', NULL, 0),
(221, '2025-05-04 15:12:00', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(222, '2025-05-04 15:12:00', 'UPDATE', 'objetos', 'OBJ1014', 'Objeto actualizado: Dron DJI Mini 3', NULL, 0),
(223, '2025-05-04 15:12:00', 'UPDATE', 'objetos', 'OBJ1015', 'Objeto actualizado: Tablet iPad Pro 12.9”', NULL, 0),
(224, '2025-05-04 15:12:00', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(225, '2025-05-04 15:13:27', 'INSERT', 'registroentreadapersona', '21', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(226, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '60', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(227, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(228, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '61', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(229, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(230, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '62', 'Ingreso de objeto con serial: OBJ1007', NULL, 0),
(231, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(232, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '63', 'Ingreso de objeto con serial: OBJ1008', NULL, 0),
(233, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: Mouse Logitech G502', NULL, 0),
(234, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '64', 'Ingreso de objeto con serial: OBJ1009', NULL, 0),
(235, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(236, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '65', 'Ingreso de objeto con serial: OBJ1010', NULL, 0),
(237, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(238, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '66', 'Ingreso de objeto con serial: OBJ1011', NULL, 0),
(239, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(240, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '67', 'Ingreso de objeto con serial: OBJ1012', NULL, 0),
(241, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1012', 'Objeto actualizado: Refrigerador Samsung', NULL, 0),
(242, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '68', 'Ingreso de objeto con serial: OBJ1013', NULL, 0),
(243, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(244, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '69', 'Ingreso de objeto con serial: OBJ1014', NULL, 0),
(245, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1014', 'Objeto actualizado: Dron DJI Mini 3', NULL, 0),
(246, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '70', 'Ingreso de objeto con serial: OBJ1015', NULL, 0),
(247, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1015', 'Objeto actualizado: Tablet iPad Pro 12.9”', NULL, 0),
(248, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '71', 'Ingreso de objeto con serial: OBJ1002', NULL, 0),
(249, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(250, '2025-05-04 15:13:27', 'INSERT', 'registro_entrada_objetos', '72', 'Ingreso de objeto con serial: OBJ1004', NULL, 0),
(251, '2025-05-04 15:13:27', 'UPDATE', 'objetos', 'OBJ1004', 'Objeto actualizado: Guitarra Fender Stratocaster', NULL, 0),
(252, '2025-05-04 15:13:45', 'INSERT', 'registro_entrada_objetos', '73', 'Ingreso de objeto con serial: OBJ1005', NULL, 0),
(253, '2025-05-04 15:13:45', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: TV LG 55” 4K', NULL, 0),
(254, '2025-05-04 15:17:37', 'INSERT', 'registroentreadapersona', '22', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(255, '2025-05-04 15:17:37', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(256, '2025-05-04 15:18:00', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(257, '2025-05-04 15:18:37', 'INSERT', 'registroentreadapersona', '23', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(258, '2025-05-04 15:18:37', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(259, '2025-05-04 15:20:37', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(260, '2025-05-04 15:20:37', 'UPDATE', 'objetos', 'OBJ1012', 'Objeto actualizado: Refrigerador Samsung', NULL, 0),
(261, '2025-05-04 15:20:37', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(262, '2025-05-04 15:20:37', 'UPDATE', 'objetos', 'OBJ1014', 'Objeto actualizado: Dron DJI Mini 3', NULL, 0),
(263, '2025-05-04 15:20:37', 'UPDATE', 'objetos', 'OBJ1015', 'Objeto actualizado: Tablet iPad Pro 12.9”', NULL, 0),
(264, '2025-05-04 15:23:14', 'INSERT', 'registroentreadapersona', '24', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(265, '2025-05-04 15:23:14', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(266, '2025-05-04 15:24:04', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(267, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(268, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(269, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(270, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1004', 'Objeto actualizado: Guitarra Fender Stratocaster', NULL, 0),
(271, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: TV LG 55” 4K', NULL, 0),
(272, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(273, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(274, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: Mouse Logitech G502', NULL, 0),
(275, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(276, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(277, '2025-05-04 15:24:04', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(278, '2025-05-04 15:43:21', 'INSERT', 'vehiculos', 'DFH232', 'Nuevo vehículo registrado con placa: DFH232', NULL, 0),
(279, '2025-05-04 15:43:37', 'INSERT', 'vehiculos', 'ZSA321a', 'Nuevo vehículo registrado con placa: ZSA321a', NULL, 0),
(280, '2025-05-04 15:44:01', 'INSERT', 'registroentreadapersona', '25', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(281, '2025-05-04 15:44:01', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(282, '2025-05-04 15:44:01', 'INSERT', 'registro_entrada_objetos', '74', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(283, '2025-05-04 15:44:01', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(284, '2025-05-04 15:44:01', 'INSERT', 'registro_entrada_vehiculos', '4', 'Ingreso de vehículo con placa: ZSA321a', NULL, 0),
(285, '2025-05-04 15:44:35', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(286, '2025-05-04 15:44:35', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(287, '2025-05-04 15:44:35', 'INSERT', 'registro_entrada_vehiculos', '5', 'Ingreso de vehículo con placa: DFH232', NULL, 0),
(288, '2025-05-04 15:47:10', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(289, '2025-05-04 15:47:15', 'UPDATE', 'vehiculos', 'ZSA321', 'Vehículo actualizado con placa: ZSA321', NULL, 0),
(290, '2025-05-04 15:54:26', 'INSERT', 'registroentreadapersona', '26', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(291, '2025-05-04 15:54:26', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(292, '2025-05-04 15:54:26', 'INSERT', 'registro_entrada_objetos', '75', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(293, '2025-05-04 15:54:26', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(294, '2025-05-04 15:54:26', 'INSERT', 'registro_entrada_objetos', '76', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(295, '2025-05-04 15:54:26', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(296, '2025-05-04 15:54:26', 'UPDATE', 'vehiculos', 'DFH232', 'Vehículo actualizado con placa: DFH232', NULL, 0),
(297, '2025-05-04 15:57:06', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(298, '2025-05-04 15:57:40', 'INSERT', 'registroentreadapersona', '27', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(299, '2025-05-04 15:57:40', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(300, '2025-05-04 15:57:40', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(301, '2025-05-04 15:59:26', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(302, '2025-05-04 15:59:26', 'INSERT', 'registro_entrada_vehiculos', '6', 'Ingreso de vehículo con placa: DFH232', NULL, 0),
(303, '2025-05-04 15:59:26', 'UPDATE', 'vehiculos', 'DFH232', 'Vehículo actualizado con placa: DFH232', NULL, 0),
(304, '2025-05-04 16:00:42', 'INSERT', 'registroentreadapersona', '28', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(305, '2025-05-04 16:00:42', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(306, '2025-05-04 19:39:17', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(307, '2025-05-04 19:39:47', 'INSERT', 'registroentreadapersona', '29', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(308, '2025-05-04 19:39:47', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(309, '2025-05-04 19:39:59', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(310, '2025-05-04 19:40:36', 'INSERT', 'registroentreadapersona', '30', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(311, '2025-05-04 19:40:36', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(312, '2025-05-04 19:43:21', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(313, '2025-05-04 19:46:08', 'INSERT', 'registroentreadapersona', '31', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(314, '2025-05-04 19:46:09', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(315, '2025-05-04 19:46:09', 'INSERT', 'registro_entrada_objetos', '77', 'Ingreso de objeto con serial: OBJ1010', NULL, 0),
(316, '2025-05-04 19:46:09', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(317, '2025-05-04 19:46:09', 'INSERT', 'registro_entrada_objetos', '78', 'Ingreso de objeto con serial: OBJ1009', NULL, 0),
(318, '2025-05-04 19:46:09', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(319, '2025-05-04 19:46:31', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(320, '2025-05-04 19:46:50', 'INSERT', 'registroentreadapersona', '32', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(321, '2025-05-04 19:46:50', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(322, '2025-05-04 19:47:00', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(323, '2025-05-04 20:40:49', 'INSERT', 'registroentreadapersona', '33', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(324, '2025-05-04 20:40:50', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(325, '2025-05-04 20:40:50', 'INSERT', 'registro_entrada_objetos', '79', 'Ingreso de objeto con serial: OBJ1002', NULL, 0),
(326, '2025-05-04 20:40:50', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(327, '2025-05-04 20:41:12', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(328, '2025-05-04 20:44:02', 'INSERT', 'registroentreadapersona', '34', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(329, '2025-05-04 20:44:02', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(330, '2025-05-04 20:44:02', 'INSERT', 'registro_entrada_objetos', '80', 'Ingreso de objeto con serial: OBJ1003', NULL, 0),
(331, '2025-05-04 20:44:02', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(332, '2025-05-04 20:44:34', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(333, '2025-05-04 20:44:45', 'INSERT', 'registroentreadapersona', '35', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(334, '2025-05-04 20:44:45', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(335, '2025-05-04 20:44:45', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(336, '2025-05-05 06:14:20', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(337, '2025-05-05 06:16:17', 'INSERT', 'registroentreadapersona', '36', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(338, '2025-05-05 06:16:17', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(339, '2025-05-05 06:16:17', 'INSERT', 'registro_entrada_objetos', '81', 'Ingreso de objeto con serial: OBJ1004', NULL, 0),
(340, '2025-05-05 06:16:17', 'UPDATE', 'objetos', 'OBJ1004', 'Objeto actualizado: Guitarra Fender Stratocaster', NULL, 0),
(341, '2025-05-05 06:17:14', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(342, '2025-05-05 06:18:48', 'INSERT', 'registroentreadapersona', '37', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(343, '2025-05-05 06:18:48', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(344, '2025-05-05 06:18:48', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(345, '2025-05-05 06:20:33', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(346, '2025-05-05 06:21:16', 'INSERT', 'registroentreadapersona', '38', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(347, '2025-05-05 06:21:16', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(348, '2025-05-05 06:21:16', 'INSERT', 'registro_entrada_objetos', '82', 'Ingreso de objeto con serial: OBJ1011', NULL, 0),
(349, '2025-05-05 06:21:16', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(350, '2025-05-05 06:21:56', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(351, '2025-05-05 06:21:56', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(352, '2025-05-05 06:25:55', 'INSERT', 'registroentreadapersona', '39', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(353, '2025-05-05 06:25:55', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(354, '2025-05-05 06:26:14', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(355, '2025-05-05 06:39:02', 'INSERT', 'registroentreadapersona', '40', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(356, '2025-05-05 06:39:02', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(357, '2025-05-05 06:39:02', 'INSERT', 'registro_entrada_objetos', '83', 'Ingreso de objeto con serial: OBJ1003', NULL, 0),
(358, '2025-05-05 06:39:02', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(359, '2025-05-05 06:39:02', 'INSERT', 'registro_entrada_objetos', '84', 'Ingreso de objeto con serial: OBJ1005', NULL, 0),
(360, '2025-05-05 06:39:02', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: TV LG 55” 4K', NULL, 0),
(361, '2025-05-05 06:40:33', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(362, '2025-05-05 06:43:26', 'INSERT', 'registroentreadapersona', '41', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(363, '2025-05-05 06:43:26', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(364, '2025-05-05 06:43:26', 'INSERT', 'registro_entrada_objetos', '85', 'Ingreso de objeto con serial: OBJ1010', NULL, 0),
(365, '2025-05-05 06:43:26', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(366, '2025-05-05 06:44:26', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(367, '2025-05-05 06:44:26', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(368, '2025-05-05 06:45:29', 'INSERT', 'registroentreadapersona', '42', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(369, '2025-05-05 06:45:29', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(370, '2025-05-05 06:45:29', 'INSERT', 'registro_entrada_objetos', '86', 'Ingreso de objeto con serial: OBJ1014', NULL, 0),
(371, '2025-05-05 06:45:29', 'UPDATE', 'objetos', 'OBJ1014', 'Objeto actualizado: Dron DJI Mini 3', NULL, 0),
(372, '2025-05-05 06:49:06', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(373, '2025-05-05 06:49:06', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(374, '2025-05-05 06:49:25', 'INSERT', 'registroentreadapersona', '43', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(375, '2025-05-05 06:49:25', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(376, '2025-05-05 06:49:25', 'INSERT', 'registro_entrada_objetos', '87', 'Ingreso de objeto con serial: OBJ1010', NULL, 0),
(377, '2025-05-05 06:49:25', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(378, '2025-05-05 06:55:19', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(379, '2025-05-05 06:55:36', 'INSERT', 'registroentreadapersona', '44', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(380, '2025-05-05 06:55:36', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(381, '2025-05-05 06:55:36', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(382, '2025-05-05 06:57:15', 'INSERT', 'registroentreadapersona', '45', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(383, '2025-05-05 06:57:15', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(384, '2025-05-05 06:57:36', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(385, '2025-05-05 06:58:10', 'INSERT', 'registroentreadapersona', '46', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(386, '2025-05-05 06:58:10', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(387, '2025-05-05 06:58:10', 'INSERT', 'registro_entrada_objetos', '88', 'Ingreso de objeto con serial: OBJ1013', NULL, 0),
(388, '2025-05-05 06:58:10', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(389, '2025-05-05 06:58:15', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(390, '2025-05-05 06:58:40', 'INSERT', 'registroentreadapersona', '47', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(391, '2025-05-05 06:58:40', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(392, '2025-05-05 06:58:40', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(393, '2025-05-05 06:59:06', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(394, '2025-05-05 06:59:06', 'INSERT', 'registro_entrada_objetos', '89', 'Ingreso de objeto con serial: OBJ1003', NULL, 0),
(395, '2025-05-05 06:59:06', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(396, '2025-05-05 06:59:06', 'INSERT', 'registro_entrada_objetos', '90', 'Ingreso de objeto con serial: OBJ1002', NULL, 0),
(397, '2025-05-05 06:59:06', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(398, '2025-05-05 06:59:06', 'INSERT', 'registro_entrada_objetos', '91', 'Ingreso de objeto con serial: OBJ1004', NULL, 0),
(399, '2025-05-05 06:59:06', 'UPDATE', 'objetos', 'OBJ1004', 'Objeto actualizado: Guitarra Fender Stratocaster', NULL, 0),
(400, '2025-05-05 06:59:06', 'INSERT', 'registro_entrada_objetos', '92', 'Ingreso de objeto con serial: OBJ1005', NULL, 0),
(401, '2025-05-05 06:59:06', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: TV LG 55” 4K', NULL, 0),
(402, '2025-05-05 06:59:06', 'INSERT', 'registro_entrada_objetos', '93', 'Ingreso de objeto con serial: OBJ1010', NULL, 0),
(403, '2025-05-05 06:59:06', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(404, '2025-05-05 06:59:06', 'INSERT', 'registro_entrada_objetos', '94', 'Ingreso de objeto con serial: OBJ1011', NULL, 0),
(405, '2025-05-05 06:59:06', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(406, '2025-05-05 06:59:06', 'INSERT', 'registro_entrada_objetos', '95', 'Ingreso de objeto con serial: OBJ1014', NULL, 0),
(407, '2025-05-05 06:59:06', 'UPDATE', 'objetos', 'OBJ1014', 'Objeto actualizado: Dron DJI Mini 3', NULL, 0),
(408, '2025-05-05 06:59:24', 'INSERT', 'registroentreadapersona', '48', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(409, '2025-05-05 06:59:24', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(410, '2025-05-05 06:59:24', 'INSERT', 'registro_entrada_objetos', '96', 'Ingreso de objeto con serial: OBJ1012', NULL, 0),
(411, '2025-05-05 06:59:24', 'UPDATE', 'objetos', 'OBJ1012', 'Objeto actualizado: Refrigerador Samsung', NULL, 0),
(412, '2025-05-05 07:08:08', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(413, '2025-05-05 07:08:30', 'INSERT', 'registroentreadapersona', '49', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(414, '2025-05-05 07:08:30', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(415, '2025-05-05 07:08:30', 'INSERT', 'registro_entrada_objetos', '97', 'Ingreso de objeto con serial: OBJ1013', NULL, 0),
(416, '2025-05-05 07:08:30', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(417, '2025-05-05 07:08:54', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(418, '2025-05-05 07:08:54', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(419, '2025-05-05 07:09:17', 'INSERT', 'registroentreadapersona', '50', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(420, '2025-05-05 07:09:17', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(421, '2025-05-05 07:09:17', 'INSERT', 'registro_entrada_objetos', '98', 'Ingreso de objeto con serial: OBJ1008', NULL, 0),
(422, '2025-05-05 07:09:17', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: Mouse Logitech G502', NULL, 0),
(423, '2025-05-05 07:42:16', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(424, '2025-05-05 07:42:16', 'UPDATE', 'vehiculos', 'DFH232', 'Vehículo actualizado con placa: DFH232', NULL, 0),
(425, '2025-05-05 07:42:34', 'INSERT', 'registroentreadapersona', '51', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(426, '2025-05-05 07:42:34', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(427, '2025-05-05 07:42:44', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(428, '2025-05-05 07:43:51', 'INSERT', 'registroentreadapersona', '52', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(429, '2025-05-05 07:43:51', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(430, '2025-05-05 07:43:51', 'INSERT', 'registro_entrada_vehiculos', '7', 'Ingreso de vehículo con placa: DFH232', NULL, 0),
(431, '2025-05-05 07:43:51', 'UPDATE', 'vehiculos', 'DFH232', 'Vehículo actualizado con placa: DFH232', NULL, 0),
(432, '2025-05-05 07:45:16', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(433, '2025-05-05 07:45:36', 'INSERT', 'registroentreadapersona', '53', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(434, '2025-05-05 07:45:36', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(435, '2025-05-05 07:45:36', 'INSERT', 'registro_entrada_vehiculos', '8', 'Ingreso de vehículo con placa: ZSA321a', NULL, 0),
(436, '2025-05-05 07:45:36', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(437, '2025-05-05 07:45:56', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(438, '2025-05-05 07:49:19', 'INSERT', 'registroentreadapersona', '54', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(439, '2025-05-05 07:49:19', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(440, '2025-05-05 07:49:19', 'UPDATE', 'vehiculos', 'DFH232', 'Vehículo actualizado con placa: DFH232', NULL, 0),
(441, '2025-05-05 07:49:30', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(442, '2025-05-05 07:49:30', 'INSERT', 'registro_entrada_vehiculos', '9', 'Ingreso de vehículo con placa: DFH232', NULL, 0),
(443, '2025-05-05 07:49:30', 'UPDATE', 'vehiculos', 'DFH232', 'Vehículo actualizado con placa: DFH232', NULL, 0),
(444, '2025-05-05 07:49:47', 'INSERT', 'registroentreadapersona', '55', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(445, '2025-05-05 07:49:47', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(446, '2025-05-05 07:50:56', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(447, '2025-05-05 07:50:56', 'UPDATE', 'vehiculos', 'DFH232', 'Vehículo actualizado con placa: DFH232', NULL, 0),
(448, '2025-05-05 07:53:25', 'INSERT', 'registroentreadapersona', '56', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(449, '2025-05-05 07:53:25', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(450, '2025-05-05 07:53:25', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(451, '2025-05-05 08:07:02', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(452, '2025-05-05 08:07:02', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(453, '2025-05-05 08:12:05', 'INSERT', 'registroentreadapersona', '57', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(454, '2025-05-05 08:12:05', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0);
INSERT INTO `notificaciones` (`id_notificacion`, `fecha`, `tipo_evento`, `tabla_afectada`, `id_afectado`, `descripcion`, `documento_generador`, `leida`) VALUES
(455, '2025-05-05 08:12:05', 'INSERT', 'registro_entrada_vehiculos', '10', 'Ingreso de vehículo con placa: ZSA321a', NULL, 0),
(456, '2025-05-05 08:12:05', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(457, '2025-05-05 08:14:16', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(458, '2025-05-05 08:14:57', 'INSERT', 'registroentreadapersona', '58', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(459, '2025-05-05 08:14:57', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(460, '2025-05-05 08:14:57', 'INSERT', 'registro_entrada_vehiculos', '11', 'Ingreso de vehículo con placa: DFH232', NULL, 0),
(461, '2025-05-05 08:14:57', 'UPDATE', 'vehiculos', 'DFH232', 'Vehículo actualizado con placa: DFH232', NULL, 0),
(462, '2025-05-05 08:16:15', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(463, '2025-05-05 08:16:15', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(464, '2025-05-05 08:16:15', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(465, '2025-05-05 08:30:36', 'INSERT', 'registroentreadapersona', '59', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(466, '2025-05-05 08:30:36', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(467, '2025-05-05 08:30:36', 'INSERT', 'registro_entrada_objetos', '99', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(468, '2025-05-05 08:30:36', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(469, '2025-05-05 08:30:36', 'INSERT', 'registro_entrada_objetos', '100', 'Ingreso de objeto con serial: OBJ1002', NULL, 0),
(470, '2025-05-05 08:30:36', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(471, '2025-05-05 08:30:36', 'INSERT', 'registro_entrada_objetos', '101', 'Ingreso de objeto con serial: OBJ1003', NULL, 0),
(472, '2025-05-05 08:30:36', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(473, '2025-05-05 08:30:36', 'INSERT', 'registro_entrada_vehiculos', '12', 'Ingreso de vehículo con placa: ZSA321a', NULL, 0),
(474, '2025-05-05 08:30:36', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(475, '2025-05-05 08:33:01', 'INSERT', 'registroentreadapersona', '60', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(476, '2025-05-05 08:33:01', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(477, '2025-05-05 08:33:01', 'INSERT', 'registro_entrada_objetos', '102', 'Ingreso de objeto con serial: 1002', NULL, 0),
(478, '2025-05-05 08:33:01', 'UPDATE', 'objetos', '1002', 'Objeto actualizado: Bajo Eléctricoo', NULL, 0),
(479, '2025-05-05 08:33:01', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(480, '2025-05-05 08:33:46', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(481, '2025-05-05 08:33:46', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(482, '2025-05-05 08:33:46', 'UPDATE', 'vehiculos', 'DFH232', 'Vehículo actualizado con placa: DFH232', NULL, 0),
(483, '2025-05-05 09:46:48', 'INSERT', 'registroentreadapersona', '61', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(484, '2025-05-05 09:46:48', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(485, '2025-05-05 09:46:48', 'INSERT', 'registro_entrada_objetos', '103', 'Ingreso de objeto con serial: OBJ1002', NULL, 0),
(486, '2025-05-05 09:46:48', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(487, '2025-05-05 09:47:28', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(488, '2025-05-05 09:47:28', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(489, '2025-05-05 09:48:45', 'INSERT', 'registroentreadapersona', '62', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(490, '2025-05-05 09:48:45', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(491, '2025-05-05 09:48:45', 'INSERT', 'registro_entrada_objetos', '104', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(492, '2025-05-05 09:48:45', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(493, '2025-05-05 09:49:35', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(494, '2025-05-05 09:50:29', 'INSERT', 'registroentreadapersona', '63', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(495, '2025-05-05 09:50:29', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(496, '2025-05-05 09:50:29', 'INSERT', 'registro_entrada_objetos', '105', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(497, '2025-05-05 09:50:29', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(498, '2025-05-05 09:51:21', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(499, '2025-05-05 09:51:21', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(500, '2025-05-05 09:56:45', 'INSERT', 'registroentreadapersona', '64', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(501, '2025-05-05 09:56:45', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(502, '2025-05-05 09:56:45', 'INSERT', 'registro_entrada_objetos', '106', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(503, '2025-05-05 09:56:45', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(504, '2025-05-05 09:56:45', 'INSERT', 'registro_entrada_objetos', '107', 'Ingreso de objeto con serial: OBJ1007', NULL, 0),
(505, '2025-05-05 09:56:45', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(506, '2025-05-05 09:57:37', 'UPDATE', 'objetos', '1001', 'Objeto actualizado: Guitarra Acústica', NULL, 0),
(507, '2025-05-05 09:57:37', 'UPDATE', 'objetos', '1002', 'Objeto actualizado: Bajo Eléctricoo', NULL, 0),
(508, '2025-05-05 09:57:37', 'UPDATE', 'objetos', '1003', 'Objeto actualizado: Guitarra Eléctrica', NULL, 0),
(509, '2025-05-05 09:57:37', 'UPDATE', 'objetos', '1004', 'Objeto actualizado: Bajo Eléctrico', NULL, 0),
(510, '2025-05-05 09:57:37', 'UPDATE', 'objetos', '1005', 'Objeto actualizado: Piano Digital', NULL, 0),
(511, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(512, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: Smartphone Samsung S22', NULL, 0),
(513, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: Bicicleta Montaña', NULL, 0),
(514, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1004', 'Objeto actualizado: Guitarra Fender Stratocaster', NULL, 0),
(515, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: TV LG 55” 4K', NULL, 0),
(516, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(517, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(518, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: Mouse Logitech G502', NULL, 0),
(519, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1009', 'Objeto actualizado: Silla Gamer DXRacer', NULL, 0),
(520, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1010', 'Objeto actualizado: Cámara Sony Alpha A7', NULL, 0),
(521, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1011', 'Objeto actualizado: Consola PlayStation 5', NULL, 0),
(522, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1012', 'Objeto actualizado: Refrigerador Samsung', NULL, 0),
(523, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1013', 'Objeto actualizado: Aire Acondicionado LG', NULL, 0),
(524, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1014', 'Objeto actualizado: Dron DJI Mini 3', NULL, 0),
(525, '2025-05-05 09:57:37', 'UPDATE', 'objetos', 'OBJ1015', 'Objeto actualizado: Tablet iPad Pro 12.9”', NULL, 0),
(526, '2025-05-05 09:57:52', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(527, '2025-05-05 10:00:59', 'INSERT', 'registroentreadapersona', '65', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(528, '2025-05-05 10:00:59', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(529, '2025-05-05 10:03:01', 'INSERT', 'registroentreadapersona', '66', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(530, '2025-05-05 10:03:01', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(531, '2025-05-05 10:04:15', 'UPDATE', 'objetos', '1001', 'Objeto actualizado: Guitarra Acústica', NULL, 0),
(532, '2025-05-05 10:04:56', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(533, '2025-05-05 10:06:20', 'INSERT', 'registroentreadapersona', '67', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(534, '2025-05-05 10:06:20', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(535, '2025-05-05 10:06:20', 'INSERT', 'registro_entrada_objetos', '108', 'Ingreso de objeto con serial: 1004', NULL, 0),
(536, '2025-05-05 10:06:20', 'UPDATE', 'objetos', '1004', 'Objeto actualizado: Bajo Eléctrico', NULL, 0),
(537, '2025-05-05 10:06:20', 'INSERT', 'registro_entrada_vehiculos', '13', 'Ingreso de vehículo con placa: ZSA321', NULL, 0),
(538, '2025-05-05 10:06:20', 'UPDATE', 'vehiculos', 'ZSA321', 'Vehículo actualizado con placa: ZSA321', NULL, 0),
(539, '2025-05-05 10:06:43', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(540, '2025-05-05 10:06:43', 'UPDATE', 'objetos', '1004', 'Objeto actualizado: Bajo Eléctrico', NULL, 0),
(541, '2025-05-05 10:06:43', 'UPDATE', 'vehiculos', 'ZSA321', 'Vehículo actualizado con placa: ZSA321', NULL, 0),
(542, '2025-05-05 10:07:19', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(543, '2025-05-05 10:07:52', 'INSERT', 'registroentreadapersona', '68', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(544, '2025-05-05 10:07:52', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(545, '2025-05-05 10:07:52', 'INSERT', 'registro_entrada_objetos', '109', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(546, '2025-05-05 10:07:52', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(547, '2025-05-05 10:07:52', 'INSERT', 'registro_entrada_vehiculos', '14', 'Ingreso de vehículo con placa: ZSA321a', NULL, 0),
(548, '2025-05-05 10:07:52', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(549, '2025-05-05 10:08:08', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(550, '2025-05-05 10:08:08', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(551, '2025-05-05 10:08:08', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(552, '2025-05-05 10:20:24', 'INSERT', 'registroentreadapersona', '69', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(553, '2025-05-05 10:20:24', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(554, '2025-05-05 10:20:24', 'INSERT', 'registro_entrada_objetos', '110', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(555, '2025-05-05 10:20:24', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(556, '2025-05-05 10:20:24', 'INSERT', 'registro_entrada_objetos', '111', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(557, '2025-05-05 10:20:24', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(558, '2025-05-05 10:20:24', 'INSERT', 'registro_entrada_objetos', '112', 'Ingreso de objeto con serial: OBJ1007', NULL, 0),
(559, '2025-05-05 10:20:24', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(560, '2025-05-05 10:22:08', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(561, '2025-05-05 10:22:08', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(562, '2025-05-05 10:22:08', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(563, '2025-05-05 10:36:21', 'INSERT', 'registroentreadapersona', '70', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(564, '2025-05-05 10:36:21', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(565, '2025-05-05 10:36:30', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(566, '2025-05-05 10:36:44', 'INSERT', 'registroentreadapersona', '71', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(567, '2025-05-05 10:36:44', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(568, '2025-05-05 10:36:44', 'INSERT', 'registro_entrada_objetos', '113', 'Ingreso de objeto con serial: 1003', NULL, 0),
(569, '2025-05-05 10:36:44', 'UPDATE', 'objetos', '1003', 'Objeto actualizado: Guitarra Eléctrica', NULL, 0),
(570, '2025-05-05 10:36:44', 'INSERT', 'registro_entrada_objetos', '114', 'Ingreso de objeto con serial: 1004', NULL, 0),
(571, '2025-05-05 10:36:44', 'UPDATE', 'objetos', '1004', 'Objeto actualizado: Bajo Eléctrico', NULL, 0),
(572, '2025-05-05 10:36:44', 'INSERT', 'registro_entrada_vehiculos', '15', 'Ingreso de vehículo con placa: DFH23H', NULL, 0),
(573, '2025-05-05 10:36:44', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(574, '2025-05-05 10:37:02', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(575, '2025-05-05 10:37:02', 'UPDATE', 'objetos', '1004', 'Objeto actualizado: Bajo Eléctrico', NULL, 0),
(576, '2025-05-05 10:37:02', 'UPDATE', 'objetos', '1003', 'Objeto actualizado: Guitarra Eléctrica', NULL, 0),
(577, '2025-05-05 10:37:02', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(578, '2025-05-05 10:45:11', 'INSERT', 'registroentreadapersona', '72', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(579, '2025-05-05 10:45:11', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(580, '2025-05-05 10:45:11', 'INSERT', 'registro_entrada_objetos', '115', 'Ingreso de objeto con serial: 1003', NULL, 0),
(581, '2025-05-05 10:45:11', 'UPDATE', 'objetos', '1003', 'Objeto actualizado: Guitarra Eléctrica', NULL, 0),
(582, '2025-05-05 10:45:11', 'INSERT', 'registro_entrada_objetos', '116', 'Ingreso de objeto con serial: 1005', NULL, 0),
(583, '2025-05-05 10:45:11', 'UPDATE', 'objetos', '1005', 'Objeto actualizado: Piano Digital', NULL, 0),
(584, '2025-05-05 10:45:11', 'INSERT', 'registro_entrada_vehiculos', '16', 'Ingreso de vehículo con placa: ZSA321', NULL, 0),
(585, '2025-05-05 10:45:11', 'UPDATE', 'vehiculos', 'ZSA321', 'Vehículo actualizado con placa: ZSA321', NULL, 0),
(586, '2025-05-05 10:47:22', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(587, '2025-05-05 10:47:22', 'UPDATE', 'objetos', '1005', 'Objeto actualizado: Piano Digital', NULL, 0),
(588, '2025-05-05 10:48:41', 'INSERT', 'registroentreadapersona', '73', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(589, '2025-05-05 10:48:42', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(590, '2025-05-05 10:48:42', 'INSERT', 'registro_entrada_objetos', '117', 'Ingreso de objeto con serial: 1004', NULL, 0),
(591, '2025-05-05 10:48:42', 'UPDATE', 'objetos', '1004', 'Objeto actualizado: Bajo Eléctrico', NULL, 0),
(592, '2025-05-05 10:49:49', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(593, '2025-05-05 10:50:34', 'INSERT', 'registroentreadapersona', '74', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(594, '2025-05-05 10:50:34', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(595, '2025-05-05 10:50:34', 'INSERT', 'registro_entrada_vehiculos', '17', 'Ingreso de vehículo con placa: DFH23H', NULL, 0),
(596, '2025-05-05 10:50:34', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(597, '2025-05-05 10:51:04', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(598, '2025-05-05 10:51:23', 'INSERT', 'registroentreadapersona', '75', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(599, '2025-05-05 10:51:23', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(600, '2025-05-05 10:51:50', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(601, '2025-05-05 10:51:50', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(602, '2025-05-05 10:52:21', 'INSERT', 'registroentreadapersona', '76', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(603, '2025-05-05 10:52:21', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(604, '2025-05-05 10:52:21', 'INSERT', 'registro_entrada_vehiculos', '18', 'Ingreso de vehículo con placa: DFH23H', NULL, 0),
(605, '2025-05-05 10:52:21', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(606, '2025-05-05 10:52:39', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(607, '2025-05-05 10:52:39', 'UPDATE', 'vehiculos', 'ZSA321', 'Vehículo actualizado con placa: ZSA321', NULL, 0),
(608, '2025-05-05 11:24:01', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(609, '2025-05-05 11:34:59', 'INSERT', 'registroentreadapersona', '77', 'Ingreso de persona con documento: 1107978187', NULL, 0),
(610, '2025-05-05 11:34:59', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(611, '2025-05-05 11:34:59', 'INSERT', 'registro_entrada_objetos', '118', 'Ingreso de objeto con serial: 1002', NULL, 0),
(612, '2025-05-05 11:34:59', 'UPDATE', 'objetos', '1002', 'Objeto actualizado: Bajo Eléctricoo', NULL, 0),
(613, '2025-05-05 11:34:59', 'INSERT', 'registro_entrada_objetos', '119', 'Ingreso de objeto con serial: 1005', NULL, 0),
(614, '2025-05-05 11:34:59', 'UPDATE', 'objetos', '1005', 'Objeto actualizado: Piano Digital', NULL, 0),
(615, '2025-05-05 11:35:57', 'UPDATE', 'usuario', '1107978187', 'Usuario actualizado: Alejandro Velandia Machado', NULL, 0),
(616, '2025-05-05 11:35:57', 'UPDATE', 'objetos', '1002', 'Objeto actualizado: Bajo Eléctricoo', NULL, 0),
(617, '2025-05-05 11:35:57', 'UPDATE', 'objetos', '1003', 'Objeto actualizado: Guitarra Eléctrica', NULL, 0),
(618, '2025-05-05 11:35:57', 'UPDATE', 'objetos', '1004', 'Objeto actualizado: Bajo Eléctrico', NULL, 0),
(619, '2025-05-05 11:35:57', 'UPDATE', 'objetos', '1005', 'Objeto actualizado: Piano Digital', NULL, 0),
(620, '2025-05-06 16:57:13', 'INSERT', 'registroentreadapersona', '78', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(621, '2025-05-06 16:57:13', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(622, '2025-05-06 16:57:13', 'INSERT', 'registro_entrada_objetos', '120', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(623, '2025-05-06 16:57:13', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(624, '2025-05-06 16:57:13', 'INSERT', 'registro_entrada_objetos', '121', 'Ingreso de objeto con serial: OBJ1007', NULL, 0),
(625, '2025-05-06 16:57:13', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(626, '2025-05-06 16:58:04', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(627, '2025-05-06 16:58:04', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(628, '2025-05-06 16:58:04', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(629, '2025-05-06 16:59:24', 'INSERT', 'registroentreadapersona', '79', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(630, '2025-05-06 16:59:24', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(631, '2025-05-06 16:59:24', 'INSERT', 'registro_entrada_objetos', '122', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(632, '2025-05-06 16:59:24', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(633, '2025-05-06 17:00:25', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(634, '2025-05-06 17:02:50', 'INSERT', 'registroentreadapersona', '80', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(635, '2025-05-06 17:02:50', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(636, '2025-05-06 17:03:06', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(637, '2025-05-06 17:03:06', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(638, '2025-05-06 17:07:06', 'INSERT', 'registroentreadapersona', '81', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(639, '2025-05-06 17:07:06', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(640, '2025-05-06 17:07:06', 'INSERT', 'registro_entrada_objetos', '123', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(641, '2025-05-06 17:07:06', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(642, '2025-05-06 17:07:33', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(643, '2025-05-06 17:07:33', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(644, '2025-05-06 17:07:33', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: Teclado Mecánico Razer', NULL, 0),
(645, '2025-05-06 17:26:14', 'INSERT', 'registroentreadapersona', '82', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(646, '2025-05-06 17:26:14', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(647, '2025-05-06 17:26:14', 'INSERT', 'registro_entrada_objetos', '124', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(648, '2025-05-06 17:26:14', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(649, '2025-05-06 17:26:34', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(650, '2025-05-06 17:27:17', 'INSERT', 'registroentreadapersona', '83', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(651, '2025-05-06 17:27:17', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(652, '2025-05-06 17:27:32', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(653, '2025-05-06 17:27:32', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(654, '2025-05-06 17:31:12', 'INSERT', 'registroentreadapersona', '84', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(655, '2025-05-06 17:31:12', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(656, '2025-05-06 17:31:12', 'INSERT', 'registro_entrada_objetos', '125', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(657, '2025-05-06 17:31:12', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(658, '2025-05-06 17:32:20', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(659, '2025-05-06 17:32:20', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(660, '2025-05-07 10:35:38', 'INSERT', 'registroentreadapersona', '85', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(661, '2025-05-07 10:35:38', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(662, '2025-05-07 10:35:38', 'INSERT', 'registro_entrada_objetos', '126', 'Ingreso de objeto con serial: OBJ1001', NULL, 0),
(663, '2025-05-07 10:35:38', 'UPDATE', 'objetos', 'OBJ1001', 'Objeto actualizado: Laptop Dell XPS', NULL, 0),
(664, '2025-05-07 10:35:38', 'INSERT', 'registro_entrada_objetos', '127', 'Ingreso de objeto con serial: OBJ1006', NULL, 0),
(665, '2025-05-07 10:35:38', 'UPDATE', 'objetos', 'OBJ1006', 'Objeto actualizado: Monitor Samsung 27”', NULL, 0),
(666, '2025-05-07 10:41:11', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(667, '2025-05-07 10:41:30', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(668, '2025-05-07 11:21:13', 'UPDATE', 'vehiculos', 'ZSA321', 'Vehículo actualizado con placa: ZSA321', NULL, 0),
(669, '2025-05-07 11:39:12', 'UPDATE', 'vehiculos', 'ZSA321', 'Vehículo actualizado con placa: ZSA321', NULL, 0),
(670, '2025-05-07 11:39:21', 'UPDATE', 'vehiculos', 'DFH232', 'Vehículo actualizado con placa: DFH232', NULL, 0),
(671, '2025-05-08 07:11:31', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(672, '2025-05-08 07:14:43', 'INSERT', 'usuario', '1192676342', 'Nuevo usuario registrado: dsfgsdgsgsgh', NULL, 0),
(673, '2025-05-08 10:51:11', 'UPDATE', 'usuario', '1106227432', 'Usuario actualizado: Gabriela Devia Marin', NULL, 0),
(674, '2025-05-08 11:05:39', 'UPDATE', 'usuario', '1106227432', 'Usuario actualizado: Gabriela Devia Marin', NULL, 0),
(675, '2025-05-08 11:12:26', 'UPDATE', 'usuario', '1106227432', 'Usuario actualizado: Gabriela Devia Marin', NULL, 0),
(676, '2025-05-08 11:35:13', 'INSERT', 'registroentreadapersona', '86', 'Ingreso de persona con documento: 1106227450', NULL, 0),
(677, '2025-05-08 11:35:13', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(678, '2025-05-11 13:37:39', 'UPDATE', 'usuario', '1106227450', 'Usuario actualizado: Brandon Yulian Villanueva Serrano', NULL, 0),
(679, '2025-05-11 17:39:22', 'UPDATE', 'usuario', '56789874', 'Usuario actualizado: Cesar Arturo Esquivel', NULL, 0),
(680, '2025-05-11 17:40:50', 'UPDATE', 'usuario', '110473654', 'Usuario actualizado: Perla Ximena Pinilla Foncesa', NULL, 0),
(681, '2025-05-11 17:40:55', 'UPDATE', 'usuario', '110473654', 'Usuario actualizado: Perla Ximena Pinilla Foncesa', NULL, 0),
(682, '2025-05-11 17:41:07', 'UPDATE', 'usuario', '110473654', 'Usuario actualizado: Perla Ximena Pinilla Foncesa', NULL, 0),
(683, '2025-05-11 17:45:07', 'UPDATE', 'usuario', '1106225365', 'Usuario actualizado: Cesar Adrian Camargo', NULL, 0),
(684, '2025-05-11 17:49:17', 'UPDATE', 'usuario', '56789874', 'Usuario actualizado: Cesar Arturo Esquivel', NULL, 0),
(685, '2025-05-11 18:50:51', 'INSERT', 'usuario', '98475838', 'Nuevo usuario registrado: Ana maria esmeralda mendoza', NULL, 0),
(686, '2025-05-12 06:42:19', 'DELETE', 'vehiculos', 'DFH232', 'Vehículo eliminado con placa: DFH232', NULL, 0),
(687, '2025-05-14 06:21:27', 'UPDATE', 'objetos', '1002', 'Objeto actualizado: Bajo Eléctricoo', NULL, 0),
(688, '2025-05-14 06:22:08', 'UPDATE', 'objetos', '1002', 'Objeto actualizado: Bajo Eléctricoo', NULL, 0),
(689, '2025-05-14 06:22:44', 'UPDATE', 'usuario', '93365453', 'Usuario actualizado: Camilo', NULL, 0),
(690, '2025-05-14 07:10:09', 'ELIMINADO', 'objetos', '1002', 'Objeto marcado como eliminado: Bajo Eléctricoo', NULL, 0),
(691, '2025-05-14 07:10:09', 'UPDATE', 'objetos', '1002', 'Objeto actualizado: 1002', NULL, 0),
(692, '2025-05-14 09:46:40', 'ELIMINADO', 'objetos', 'OBJ1002', 'Objeto marcado como eliminado: Smartphone Samsung S22', 'Brandon Yulian Villanueva Serrano', 0),
(693, '2025-05-14 09:46:40', 'UPDATE', 'objetos', 'OBJ1002', 'Objeto actualizado: OBJ1002', 'Brandon Yulian Villanueva Serrano', 0),
(694, '2025-05-14 09:47:29', 'ELIMINADO', 'objetos', 'OBJ1008', 'Objeto marcado como eliminado: Mouse Logitech G502', 'Brandon Yulian Villanueva Serrano', 0),
(695, '2025-05-14 09:47:29', 'UPDATE', 'objetos', 'OBJ1008', 'Objeto actualizado: OBJ1008', 'Brandon Yulian Villanueva Serrano', 0),
(696, '2025-05-14 09:47:34', 'ELIMINADO', 'objetos', 'OBJ1003', 'Objeto marcado como eliminado: Bicicleta Montaña', 'Brandon Yulian Villanueva Serrano', 0),
(697, '2025-05-14 09:47:34', 'UPDATE', 'objetos', 'OBJ1003', 'Objeto actualizado: OBJ1003', 'Brandon Yulian Villanueva Serrano', 0),
(698, '2025-05-14 09:47:38', 'ELIMINADO', 'objetos', 'OBJ1004', 'Objeto marcado como eliminado: Guitarra Fender Stratocaster', 'Brandon Yulian Villanueva Serrano', 0),
(699, '2025-05-14 09:47:38', 'UPDATE', 'objetos', 'OBJ1004', 'Objeto actualizado: OBJ1004', 'Brandon Yulian Villanueva Serrano', 0),
(700, '2025-05-14 09:47:40', 'ELIMINADO', 'objetos', 'OBJ1005', 'Objeto marcado como eliminado: TV LG 55” 4K', 'Brandon Yulian Villanueva Serrano', 0),
(701, '2025-05-14 09:47:40', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: OBJ1005', 'Brandon Yulian Villanueva Serrano', 0),
(702, '2025-05-14 09:50:56', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: OBJ1005', 'Brandon Yulian Villanueva Serrano', 0),
(703, '2025-05-14 09:51:12', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: OBJ1005', 'Brandon Yulian Villanueva Serrano', 0),
(704, '2025-05-14 09:51:39', 'UPDATE', 'objetos', 'OBJ1005', 'Objeto actualizado: OBJ1005', 'Brandon Yulian Villanueva Serrano', 0),
(705, '2025-05-14 10:19:48', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(706, '2025-05-14 16:57:42', 'ELIMINADO', 'vehiculos', 'ZSA321a', 'Vehículo eliminado con placa: ZSA321a', NULL, 0),
(707, '2025-05-14 16:57:42', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(708, '2025-05-15 06:13:17', 'UPDATE', 'vehiculos', 'ZSA321a', 'Vehículo actualizado con placa: ZSA321a', NULL, 0),
(709, '2025-05-16 06:44:35', 'ELIMINADO', 'vehiculos', 'DFH23H', 'Vehículo eliminado con placa: DFH23H', NULL, 0),
(710, '2025-05-16 06:44:35', 'UPDATE', 'vehiculos', 'DFH23H', 'Vehículo actualizado con placa: DFH23H', NULL, 0),
(711, '2025-05-16 07:21:42', 'ELIMINADO', 'objetos', 'OBJ1007', 'Objeto marcado como eliminado: Teclado Mecánico Razer', 'Brandon Yulian Villanueva Serrano', 0),
(712, '2025-05-16 07:21:42', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: OBJ1007', 'Brandon Yulian Villanueva Serrano', 0),
(713, '2025-05-16 07:21:58', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: OBJ1007', NULL, 0),
(714, '2025-05-16 07:22:06', 'ELIMINADO', 'objetos', 'OBJ1007', 'Objeto marcado como eliminado: Teclado Mecánico Razer', 'Brandon Yulian Villanueva Serrano', 0),
(715, '2025-05-16 07:22:06', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: OBJ1007', 'Brandon Yulian Villanueva Serrano', 0),
(716, '2025-05-16 07:22:09', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: OBJ1007', 'Brandon Yulian Villanueva Serrano', 0),
(717, '2025-05-16 07:22:20', 'UPDATE', 'objetos', 'OBJ1007', 'Objeto actualizado: OBJ1007', NULL, 0),
(718, '2025-05-21 06:55:22', 'INSERT', 'vehiculos', 'OIJHGCXZ<ZDFH', 'Nuevo vehículo registrado con placa: OIJHGCXZ<ZDFH', 'Juan estaban vallejo', 0),
(719, '2025-05-21 07:21:57', 'INSERT', 'vehiculos', 'JHP07', 'Nuevo vehículo registrado con placa: JHP07', 'Juan estaban vallejo', 0),
(720, '2025-05-21 07:26:49', 'INSERT', 'vehiculos', 'ASDFHN', 'Nuevo vehículo registrado con placa: ASDFHN', 'Juan estaban vallejo', 0),
(721, '2025-05-21 07:36:13', 'INSERT', 'vehiculos', 'DFSDFGH', 'Nuevo vehículo registrado con placa: DFSDFGH', 'Juan estaban vallejo', 0),
(722, '2025-05-21 07:42:25', 'INSERT', 'vehiculos', 'PHGFDXCVBNM', 'Nuevo vehículo registrado con placa: PHGFDXCVBNM', 'Juan estaban vallejo', 0),
(723, '2025-05-21 08:18:28', 'INSERT', 'vehiculos', 'WFGTREFS', 'Nuevo vehículo registrado con placa: WFGTREFS', 'Juan estaban vallejo', 0),
(724, '2025-05-21 10:56:39', 'INSERT', 'vehiculos', 'ASFDGFJHGFD', 'Nuevo vehículo registrado con placa: ASFDGFJHGFD', 'Juan estaban vallejo', 0),
(725, '2025-05-21 10:57:13', 'UPDATE', 'objetos', '1003', 'Objeto actualizado: 1003', NULL, 0),
(726, '2025-05-22 08:35:44', 'INSERT', 'vehiculos', 'KJHGFDS', 'Nuevo vehículo registrado con placa: KJHGFDS', 'Juan estaban vallejo', 0),
(727, '2025-05-22 10:14:41', 'Actualizo', 'usuario', '1016010239', 'Usuario actualizado: Juan estaban vallejo', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objetos`
--

CREATE TABLE `objetos` (
  `id_serial` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `caracteristica` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_marca` int NOT NULL,
  `id_documento` int NOT NULL,
  `id_estado` int NOT NULL,
  `id_tipoObjeto` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objetos`
--

INSERT INTO `objetos` (`id_serial`, `nombre`, `caracteristica`, `foto`, `id_marca`, `id_documento`, `id_estado`, `id_tipoObjeto`) VALUES
('1001', 'Guitarra Acústica', 'Cuerpo de madera de caoba', NULL, 1, 1107978187, 3, 1),
('1002', 'Bajo Eléctricoo', '4 cuerdas, pastillas activadas\r\n', '68247ce0cc4452.35858380.jpg', 2, 1107978187, 4, 1),
('1003', 'Guitarra EléctricaDFSF', 'Humbuckers, acabado sunburstASFW', '67a8d643309d28.60300968.png', 2, 1107978187, 1, 1),
('1004', 'Bajo Eléctrico', '4 cuerdas, pastillas activas', '67a8d643309d28.60300968.png', 3, 1107978187, 1, 1),
('1005', 'Piano Digital', 'Teclas contrapesadas, sonido realista', '67a8d643309d28.60300968.png', 5, 1107978187, 1, 1),
('OBJ1001', 'Laptop Dell XPS', '16GB RAM, 512GB SSD', '67a8d643309d28.60300968.png', 1, 1106227450, 2, 1),
('OBJ1002', 'Smartphone Samsung S22', '128GB, 8GB RAM', NULL, 2, 1106227450, 4, 1),
('OBJ1003', 'Bicicleta Montaña', 'Marco de aluminio, 21 velocidades', '67a8d643309d28.60300968.png', 3, 1106227450, 4, 1),
('OBJ1004', 'Guitarra Fender Stratocaster', 'Color negro, 6 cuerdas', '67a8d643309d28.60300968.png', 4, 1106227450, 4, 1),
('OBJ1005', 'TV LG 55” 4K', 'HDR10, Smart TV', '6808364837f355.62823818.jpeg', 5, 1106227450, 4, 1),
('OBJ1006', 'Monitor Samsung 27”', 'IPS, 144Hz', '67a8d643309d28.60300968.png', 1, 1106227450, 2, 1),
('OBJ1007', 'Teclado Mecánico Razer', 'Switches Green', '67a8d643309d28.60300968.png', 1, 1106227450, 1, 1),
('OBJ1008', 'Mouse Logitech G502', 'RGB, Sensor Hero 25K', '67a8d643309d28.60300968.png', 1, 1106227450, 4, 1),
('OBJ1009', 'Silla Gamer DXRacer', 'Negro y rojo, reclinable', '67a8d643309d28.60300968.png', 1, 1106227450, 1, 1),
('OBJ1010', 'Cámara Sony Alpha A7', 'Full Frame, 24MP', '67a8d643309d28.60300968.png', 1, 1106227450, 1, 1),
('OBJ1011', 'Consola PlayStation 5', 'Edición estándar', '67a8d643309d28.60300968.png', 1, 1106227450, 1, 1),
('OBJ1012', 'Refrigerador Samsung', 'Inverter, 500L', '67a8d643309d28.60300968.png', 1, 1106227450, 1, 1),
('OBJ1013', 'Aire Acondicionado LG', '12,000 BTU, WiFi', '67a8d643309d28.60300968.png', 1, 1106227450, 1, 1),
('OBJ1014', 'Dron DJI Mini 3', '4K Video, GPS', '67a8d643309d28.60300968.png', 1, 1106227450, 1, 1),
('OBJ1015', 'Tablet iPad Pro 12.9”', 'M2, 256GB', '67a8d643309d28.60300968.png', 1, 1106227450, 1, 1);

--
-- Disparadores `objetos`
--
DELIMITER $$
CREATE TRIGGER `create_objeto` AFTER INSERT ON `objetos` FOR EACH ROW INSERT INTO notificaciones (
  fecha, 
  tipo_evento, 
  tabla_afectada, 
  id_afectado, 
  descripcion, 
  documento_generador
)
VALUES (
  NOW(), 
  'Creo nuevos', 
  'objetos', 
  NEW.id_serial, 
  CONCAT('Nuevo objeto registrado: ', NEW.Nombre), 
  @documento_generador
)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_eliminado_objeto` AFTER UPDATE ON `objetos` FOR EACH ROW BEGIN
  IF NEW.id_estado = 4 AND OLD.id_estado <> 4 THEN
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'ELIMINADO', 'objetos', NEW.id_serial, CONCAT('Objeto marcado como eliminado: ', NEW.Nombre), @documento_generador
    );
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_objetos` AFTER UPDATE ON `objetos` FOR EACH ROW INSERT INTO notificaciones (
  fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
)
VALUES (
  NOW(), 'UPDATE', 'objetos', NEW.id_serial, CONCAT('Objeto actualizado: ', NEW.id_serial), @documento_generador
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa`
--

CREATE TABLE `programa` (
  `id_programaFormacion` int NOT NULL,
  `nombrePrograma` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programa`
--

INSERT INTO `programa` (`id_programaFormacion`, `nombrePrograma`, `id_estado`) VALUES
(1, 'Análisis y Desarrollo de Software', 1),
(2, 'Gestión Administrativa', 1),
(3, 'Diseño de Productos Industriales', 1),
(4, 'Mecatrónica', 1),
(5, 'Costureria', 1),
(6, 'Contabilidad y Finanzas', 1),
(7, 'Producción de Multimedia', 1),
(8, 'Electricidad Industrial', 1),
(9, 'Cocina', 1),
(10, 'Seguridad y Salud en el Trabajo', 1),
(11, 'Gestión de Redes de Datos', 1),
(12, 'Construcción de Edificaciones', 1),
(13, 'Mantenimiento de Equipos Biomédicos', 1),
(14, 'Producción Agropecuaria', 1),
(15, 'Operaciones de Comercio Exterior', 1),
(16, 'Atención Integral a la Primera Infancia', 1),
(17, 'Desarrollo de Aplicaciones Móviles', 1),
(18, 'Gestión de Empresas Turísticas', 1),
(19, 'Mantenimiento Electrónico e Instrumental Industrial', 1),
(20, 'Asistencia en Organización de Archivos', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroentradaobjetos`
--

CREATE TABLE `registroentradaobjetos` (
  `id_registroObjeto` int NOT NULL,
  `fechaEntrada` datetime NOT NULL,
  `fechaSalida` datetime DEFAULT NULL,
  `observaciones` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observacionesSalida` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registroentradaobjetos`
--

INSERT INTO `registroentradaobjetos` (`id_registroObjeto`, `fechaEntrada`, `fechaSalida`, `observaciones`, `observacionesSalida`, `id_serial`) VALUES
(108, '2025-05-05 10:06:20', '2025-05-05 10:06:43', NULL, NULL, '1004'),
(109, '2025-05-05 10:07:52', '2025-05-05 10:08:08', NULL, NULL, 'OBJ1001'),
(110, '2025-05-05 10:20:24', '2025-05-05 10:22:08', NULL, NULL, 'OBJ1001'),
(111, '2025-05-05 10:20:24', '2025-05-06 16:58:04', 'ahora esta saliendo epa', NULL, 'OBJ1006'),
(112, '2025-05-05 10:20:24', '2025-05-05 10:22:08', NULL, NULL, 'OBJ1007'),
(113, '2025-05-05 10:36:44', '2025-05-05 10:37:02', NULL, NULL, '1003'),
(114, '2025-05-05 10:36:44', '2025-05-05 10:37:02', NULL, NULL, '1004'),
(115, '2025-05-05 10:45:11', '2025-05-05 11:35:57', NULL, NULL, '1003'),
(116, '2025-05-05 10:45:11', '2025-05-05 10:47:22', 'awsedrtghjk', NULL, '1005'),
(117, '2025-05-05 10:48:42', '2025-05-05 11:35:57', NULL, NULL, '1004'),
(118, '2025-05-05 11:34:59', '2025-05-05 11:35:57', NULL, NULL, '1002'),
(119, '2025-05-05 11:34:59', '2025-05-05 11:35:57', NULL, NULL, '1005'),
(120, '2025-05-06 16:57:13', '2025-05-06 16:58:04', 'ahora estoy saliendo epa', NULL, 'OBJ1001'),
(121, '2025-05-06 16:57:13', '2025-05-06 17:07:33', 'dtfgyhj', NULL, 'OBJ1007'),
(122, '2025-05-06 16:59:24', '2025-05-06 17:03:06', 'sacando esto', NULL, 'OBJ1001'),
(123, '2025-05-06 17:07:06', '2025-05-06 17:07:33', 'xdctfvgybh', NULL, 'OBJ1006'),
(124, '2025-05-06 17:26:14', '2025-05-06 17:27:32', 'Sin tecaldo', NULL, 'OBJ1001'),
(125, '2025-05-06 17:31:12', '2025-05-06 17:32:20', 'esta de salida', NULL, 'OBJ1001'),
(126, '2025-05-07 10:35:38', NULL, NULL, NULL, 'OBJ1001'),
(127, '2025-05-07 10:35:38', NULL, NULL, NULL, 'OBJ1006');

--
-- Disparadores `registroentradaobjetos`
--
DELIMITER $$
CREATE TRIGGER `insert_objeto` AFTER INSERT ON `registroentradaobjetos` FOR EACH ROW INSERT INTO notificaciones (
  fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
)
VALUES (
  NOW(), 'Registro', 'registro_entrada_objetos', NEW.id_registroObjeto, CONCAT('Ingreso de objeto con serial: ', NEW.id_serial), 
  @documento_generador
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroentradapersona`
--

CREATE TABLE `registroentradapersona` (
  `id_registro` int NOT NULL,
  `fechaEntrada` datetime NOT NULL,
  `fechaSalida` datetime DEFAULT NULL,
  `id_documento` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registroentradapersona`
--

INSERT INTO `registroentradapersona` (`id_registro`, `fechaEntrada`, `fechaSalida`, `id_documento`) VALUES
(65, '2025-05-05 10:00:59', '2025-05-05 10:07:19', 1106227450),
(66, '2025-05-04 10:03:01', '2025-05-05 10:04:56', 1107978187),
(67, '2025-05-05 10:06:20', '2025-05-05 10:06:43', 1107978187),
(68, '2025-05-05 10:07:52', '2025-05-05 10:08:08', 1106227450),
(69, '2025-05-05 10:20:24', '2025-05-05 10:22:08', 1106227450),
(70, '2025-05-05 10:36:21', '2025-05-05 10:36:30', 1107978187),
(71, '2025-05-05 10:36:44', '2025-05-05 10:37:02', 1107978187),
(72, '2025-05-05 10:45:11', '2025-05-05 10:47:22', 1107978187),
(73, '2025-05-05 10:48:41', '2025-05-05 10:49:49', 1107978187),
(74, '2025-05-05 10:50:34', '2025-05-05 10:51:04', 1107978187),
(75, '2025-05-05 10:51:23', '2025-05-05 10:51:50', 1107978187),
(76, '2025-05-05 10:52:21', '2025-05-05 10:52:39', 1107978187),
(77, '2025-05-05 11:34:59', '2025-05-05 11:35:57', 1107978187),
(78, '2025-05-06 16:57:13', '2025-05-06 16:58:04', 1106227450),
(79, '2025-05-06 16:59:24', '2025-05-06 17:00:25', 1106227450),
(80, '2025-05-06 17:02:50', '2025-05-06 17:03:06', 1106227450),
(81, '2025-05-06 17:07:06', '2025-05-06 17:07:33', 1106227450),
(82, '2025-05-06 17:26:14', '2025-05-06 17:26:34', 1106227450),
(83, '2025-05-06 17:27:17', '2025-05-06 17:27:32', 1106227450),
(84, '2025-05-06 17:31:12', '2025-05-06 17:32:20', 1106227450),
(85, '2025-05-04 10:35:38', NULL, 1106227450),
(86, '2025-05-08 11:35:13', NULL, 1106227450);

--
-- Disparadores `registroentradapersona`
--
DELIMITER $$
CREATE TRIGGER `insertar_persona` AFTER INSERT ON `registroentradapersona` FOR EACH ROW INSERT INTO notificaciones (
  fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
)
VALUES (
  NOW(), 'Inserto', 'registroentreadapersona', NEW.id_registro, CONCAT('Ingreso de persona con documento: ', NEW.id_documento), 
  @documento_generador
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroentradavehiculos`
--

CREATE TABLE `registroentradavehiculos` (
  `id_registroVehiculo` int NOT NULL,
  `fechaEntrada` datetime NOT NULL,
  `fechaSalida` datetime DEFAULT NULL,
  `observaciones` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observacionesSalida` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_placa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registroentradavehiculos`
--

INSERT INTO `registroentradavehiculos` (`id_registroVehiculo`, `fechaEntrada`, `fechaSalida`, `observaciones`, `observacionesSalida`, `id_placa`) VALUES
(13, '2025-05-05 10:06:20', '2025-05-05 10:06:43', NULL, '', 'ZSA321'),
(14, '2025-05-05 10:07:52', '2025-05-05 10:08:08', NULL, '', 'ZSA321a'),
(15, '2025-05-05 10:36:44', '2025-05-05 10:37:02', NULL, '', 'DFH23H'),
(16, '2025-05-05 10:45:11', '2025-05-05 10:52:39', NULL, '', 'ZSA321'),
(17, '2025-05-05 10:50:34', '2025-05-05 10:51:50', NULL, '', 'DFH23H'),
(18, '2025-05-05 10:52:21', NULL, NULL, '', 'DFH23H');

--
-- Disparadores `registroentradavehiculos`
--
DELIMITER $$
CREATE TRIGGER `insert_vehiculo` AFTER INSERT ON `registroentradavehiculos` FOR EACH ROW INSERT INTO notificaciones (
  fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
)
VALUES (
  NOW(), 'INSERT', 'registro_entrada_vehiculos', NEW.id_registroVehiculo, CONCAT('Ingreso de vehículo con placa: ', NEW.id_placa),
  @documento_generador
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int NOT NULL,
  `nombre_rol` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador\r\n'),
(2, 'Aprendiz\r\n'),
(3, 'Funcionario'),
(4, 'Instructor'),
(5, 'Vigilante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomarca`
--

CREATE TABLE `tipomarca` (
  `id_tipo_marca` int NOT NULL,
  `nombre_tipo_marca` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipomarca`
--

INSERT INTO `tipomarca` (`id_tipo_marca`, `nombre_tipo_marca`) VALUES
(1, 'vehiculos'),
(2, 'objetos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoobjeto`
--

CREATE TABLE `tipoobjeto` (
  `id_tipoObjeto` int NOT NULL,
  `tipoObjeto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipoobjeto`
--

INSERT INTO `tipoobjeto` (`id_tipoObjeto`, `tipoObjeto`, `id_estado`) VALUES
(1, 'Electronico\r\n', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipovehiculo`
--

CREATE TABLE `tipovehiculo` (
  `id_tipoVehiculo` int NOT NULL,
  `tipoVehiculo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipovehiculo`
--

INSERT INTO `tipovehiculo` (`id_tipoVehiculo`, `tipoVehiculo`, `id_estado`) VALUES
(1, 'MOTO', 1),
(2, 'CARRO', 1),
(3, 'Camioneta', 1),
(4, 'Camión', 1),
(5, 'Bus', 1),
(6, 'Microbús', 1),
(7, 'Bicicleta', 1),
(8, 'Cuatrimoto', 1),
(9, 'Volqueta', 1),
(10, 'Tractocamión', 1),
(11, 'Pick-up', 1),
(12, 'Van', 1),
(13, 'SUV', 1),
(14, 'Motocicleta', 1),
(15, 'Automóvil', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_documento` int NOT NULL,
  `nombre_completo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contraseña` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_estado` int NOT NULL,
  `id_rol` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_documento`, `nombre_completo`, `correo`, `contraseña`, `foto`, `id_estado`, `id_rol`) VALUES
(56789874, 'Cesar Arturo Esquivel', 'cesararturoesquivel@gmail.con', '$2y$12$rK3an.1RWxAWLQ0AanqFIuLJ5kv.FCyirFffjG11kJ/434gP0ePQy', '', 2, 4),
(93365453, 'Camilo', 'camilo@gmail.com', '$2y$12$ZcUeTJotI6.cdCXYcftOV.U1sUb/LL/1NJjDZE6auN3PvcpQ3pUFC', '68247d047b2fe7.84195852.png', 1, 4),
(93386330, 'Cesar Esquivel', 'cesaresquivel@gmail.com', '', '', 2, 2),
(98475838, 'Ana maria esmeralda mendoza', 'anamar@gmail.com', '$2y$10$bW/2C7t4ky.6YZ9qQsLSTOQ8Jn68Vnk7VX6ItfKIzu5iBh4WBtOPe', NULL, 1, 2),
(110473654, 'Perla Ximena Pinilla Foncesa', 'perlapinifo04@gmail.com', '', '', 2, 2),
(1016010239, 'Juan estaban vallejo', 'juanestebanvallejo@gmail.clm', '$2y$12$p5UcCZ2wl4HNJ1Se3a8ppehDJzWDPowi.wzKHuoeD0VVdhJGs5.6u', '', 1, 1),
(1023456789, 'María González', 'maria.gonzalez@mail.com', 'def45678', '', 2, 2),
(1034567891, 'Carlos Ramírez', 'carlos.ramirez@mail.com', 'ghi78901', '', 2, 2),
(1045678902, 'Ana Martínez', 'ana.martinez@mail.com', 'jkl01234', '', 2, 2),
(1056789013, 'Pedro Sánchez', 'pedro.sanchez@mail.com', 'mno34567', '', 2, 2),
(1067890124, 'Laura Torres', 'laura.torres@mail.com', 'pqr67890', '', 2, 2),
(1078901235, 'Andrés Castro', 'andres.castro@mail.com', 'stu90123', '', 2, 2),
(1089012346, 'Sofía López', 'sofia.lopez@mail.com', 'vwx23456', '', 2, 2),
(1090123457, 'David Herrera', 'david.herrera@mail.com', 'yzx56789', '', 2, 2),
(1101234568, 'Elena Díaz', 'elena.diaz@mail.com', 'abc89012', '', 2, 2),
(1106225365, 'Cesar Adrian Camargo', 'cesarAdrianCamargo@gmail.com', '$2y$12$mKLAErtUHJdVJNpMSOGVJuHIWpGLa0kfR4XCih3EE8L6UNc0sBYNG', '', 2, 1),
(1106227123, 'Mailo villanueva', 'mailo@gmail.con', '$2y$12$EONGG.jG0eACG9PEqeWuEO67iLBeBajNNG3CH2E2oTgONU11rfBla', NULL, 2, 2),
(1106227432, 'Gabriela Devia Marin', 'gabrieladeviamarin@gmail.com', '$2y$10$26KGkXegunxC7nwdD8y5xOegOWUvCUk.UkLxXEtfkE66zRaBG13g6', '680470227f8912.98104658.jpg', 2, 1),
(1106227450, 'Brandon Yulian Villanueva Serrano', 'brandonyulian10@gmail.com', '$2y$10$ajtfGIgDwPyO3Z3qPFM/QeI1ngGmtv9bqCb7qfL9y/7f73SH1BY.e', '681c9f73dd1550.66361453.png', 1, 1),
(1107234125, 'Elizabet Sofia Enciso', 'ElizabetSofiaEnciso@gmail.com', '$2y$12$XijgfdJzPtmVUMS.eWb2OOJsZGu.uPl.IMPJwgPrFavN3b0hy/tQK', '', 1, 2),
(1107978187, 'Alejandro Velandia Machado', 'alejandrovelandia@gmai.com', '$2y$12$GFvAq1Idgt3sJdpoZ/1IRe0M2fV1gIIwVxPZaUs.CvhglAiGd0B46', '', 1, 1),
(1112345679, 'Miguel Rojas', 'miguel.rojas@mail.com', 'def12345', '', 2, 2),
(1116123232, 'ian samuel Villnaueva', 'iansamuel@gmaul.co9jm', '$2y$12$0jez6hyq4w2fj.pPV/DkFeRyWNNbW.Ghpb66tuMcz7kOvGEVp0z0a', NULL, 2, 2),
(1123456780, 'Paola Jiménez', 'paola.jimenez@mail.com', 'ghi45678', '', 2, 2),
(1134567891, 'Oscar Vargas', 'oscar.vargas@mail.com', 'jkl78901', '', 2, 2),
(1145678902, 'Natalia Cruz', 'natalia.cruz@mail.com', 'mno01234', '', 2, 2),
(1156789013, 'Javier Mendoza', 'javier.mendoza@mail.com', 'pqr34567', '', 2, 2),
(1167890124, 'Carolina Reyes', 'carolina.reyes@mail.com', 'stu67890', '', 2, 2),
(1178901235, 'Raúl Ortega', 'raul.ortega@mail.com', 'vwx90123', '', 2, 2),
(1189012346, 'Camila Pineda', 'camila.pineda@mail.com', 'yzx23456', '', 2, 2),
(1190123457, 'Fernando Silva', 'fernando.silva@mail.com', 'abc56789', '', 2, 2),
(1192676342, 'dsfgsdgsgsgh', 'alejandrowervelandia@gmai.com', '$2y$10$SU4LA8VSlJJlhO1DVUj9xu2CH8l18dFnzzq1Elkp57jWYeswvmbPq', NULL, 2, 2),
(1201234568, 'Verónica Castillo', 'veronica.castillo@mail.com', 'def89012', '', 2, 2),
(1206227450, 'Juan Pérez', 'juan.perez@mail.com', 'abc12345', '', 2, 2),
(1212345679, 'Héctor Ramos', 'hector.ramos@mail.com', 'ghi12345', '', 2, 2),
(1223456780, 'Gabriela Figueroa', 'gabriela.figueroa@mail.com', 'jkl45678', '', 2, 1),
(1234567891, 'Ricardo Cárdenas', 'ricardo.cardenas@mail.com', 'mno78901', '', 2, 2),
(1245678902, 'Isabel Molina', 'isabel.molina@mail.com', 'pqr01234', '', 2, 2),
(1256789013, 'Alejandro Guzmán', 'alejandro.guzman@mail.com', 'stu34567', '', 2, 2),
(1267890124, 'Daniela Peña', 'daniela.pena@mail.com', 'vwx67890', '', 2, 2),
(1278901235, 'Luis Fernández', 'luis.fernandez@mail.com', 'yzx90123', '', 2, 2),
(1289012346, 'Patricia Quintero', 'patricia.quintero@mail.com', 'abc23456', '', 2, 2),
(1290123457, 'Sebastián Estrada', 'sebastian.estrada@mail.com', 'def56789', '', 2, 2),
(1301234568, 'Valeria Álvarez', 'valeria.alvarez@mail.com', 'ghi89012', '', 2, 2);

--
-- Disparadores `usuario`
--
DELIMITER $$
CREATE TRIGGER `insert_usuario` AFTER INSERT ON `usuario` FOR EACH ROW INSERT INTO notificaciones (
  fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
)
VALUES (
  NOW(), 'Registro', 'usuario', NEW.id_documento, CONCAT('Nuevo usuario registrado: ', NEW.nombre_completo), 
  @documento_generador
)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_usuario` AFTER UPDATE ON `usuario` FOR EACH ROW INSERT INTO notificaciones (
  fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
)
VALUES (
  NOW(), 'Actualizo', 'usuario', NEW.id_documento, CONCAT('Usuario actualizado: ', NEW.Nombre_Completo), @documento_generador
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_placa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `caracteristicas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_tarjeta_propiedad` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_estado` int NOT NULL,
  `id_documento` int NOT NULL,
  `id_tipoVehiculo` int NOT NULL,
  `id_marca` int NOT NULL,
  `id_modelo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id_placa`, `foto`, `caracteristicas`, `foto_tarjeta_propiedad`, `id_estado`, `id_documento`, `id_tipoVehiculo`, `id_marca`, `id_modelo`) VALUES
('ASDFHN', NULL, 'sdfsdf', NULL, 3, 1106227432, 9, 6, 2),
('ASFDGFJHGFD', NULL, 'ASDGHDHFDGFSDA', NULL, 1, 1106227432, 12, 6, 2),
('DFH23H', 'vehiculo_68121999d287f3.15696567.png', 'hola', 'tarjeta_68121dc9916601.34785742.jpeg', 4, 1107978187, 1, 1, 3),
('DFSDFGH', NULL, 'adfh', NULL, 1, 1106227432, 13, 6, 1),
('JHP07', NULL, 'vgtfrc', NULL, 1, 1106227432, 12, 1, 2),
('KJHGFDS', NULL, 'vcsa', NULL, 3, 1106227432, 13, 5, 2),
('OIJHGCXZ<ZDFH', NULL, '<zxdw', NULL, 1, 1106227432, 13, 2, 1),
('PHGFDXCVBNM', NULL, 'lkjhgfc', NULL, 4, 1106227432, 12, 5, 1),
('WFGTREFS', NULL, 'tgedwd', NULL, 3, 1106227432, 10, 4, 1),
('ZSA321', 'vehiculo_68051c98074a76.46750118.jpg', 'hola a todesasasa', 'tarjeta_680476aae993a5.84479628.jpg', 1, 1107978187, 2, 2, 1),
('ZSA321a', 'vehiculo_68051c98074a76.46750118.jpg', 'hola a todesasasa', 'tarjeta_680476aae993a5.84479628.jpg', 1, 1106227450, 2, 2, 1);

--
-- Disparadores `vehiculos`
--
DELIMITER $$
CREATE TRIGGER `create_vehiculo` AFTER INSERT ON `vehiculos` FOR EACH ROW INSERT INTO notificaciones (
  fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
)
VALUES (
  NOW(), 'INSERT', 'vehiculos', NEW.id_placa, CONCAT('Nuevo vehículo registrado con placa: ', NEW.id_placa),
  @documento_generador
)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_eliminado_vehiculo` AFTER UPDATE ON `vehiculos` FOR EACH ROW BEGIN
  IF NEW.id_estado = 4 AND OLD.id_estado <> 4 THEN
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'ELIMINADO', 'vehiculos', NEW.id_placa, CONCAT('Vehículo eliminado con placa: ', NEW.id_placa), @documento_generador
    );
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_vehiculos` AFTER UPDATE ON `vehiculos` FOR EACH ROW INSERT INTO notificaciones (
  fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
)
VALUES (
  NOW(), 'Actualizacion', 'vehiculos', NEW.id_placa, CONCAT('Vehículo actualizado con placa: ', NEW.id_placa), @documento_generador
)
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detallematricula`
--
ALTER TABLE `detallematricula`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_ficha` (`id_ficha`),
  ADD KEY `id_documento` (`id_documento`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `fichas`
--
ALTER TABLE `fichas`
  ADD PRIMARY KEY (`n_ficha`),
  ADD KEY `id_jornada` (`id_jornada`),
  ADD KEY `id_nivelFormacion` (`id_nivelFormacion`),
  ADD KEY `id_programaFormacion` (`id_programaFormacion`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `jornada`
--
ALTER TABLE `jornada`
  ADD PRIMARY KEY (`id_jornada`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`),
  ADD KEY `id_tipo_marca` (`id_tipo_marca`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD PRIMARY KEY (`id_modelo`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `nivelformacion`
--
ALTER TABLE `nivelformacion`
  ADD PRIMARY KEY (`id_nivelFormacion`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`);

--
-- Indices de la tabla `objetos`
--
ALTER TABLE `objetos`
  ADD PRIMARY KEY (`id_serial`),
  ADD KEY `id_documento` (`id_documento`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `id_tipoObjeto` (`id_tipoObjeto`);

--
-- Indices de la tabla `programa`
--
ALTER TABLE `programa`
  ADD PRIMARY KEY (`id_programaFormacion`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `registroentradaobjetos`
--
ALTER TABLE `registroentradaobjetos`
  ADD PRIMARY KEY (`id_registroObjeto`),
  ADD KEY `id_serial` (`id_serial`);

--
-- Indices de la tabla `registroentradapersona`
--
ALTER TABLE `registroentradapersona`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_documento` (`id_documento`);

--
-- Indices de la tabla `registroentradavehiculos`
--
ALTER TABLE `registroentradavehiculos`
  ADD PRIMARY KEY (`id_registroVehiculo`),
  ADD KEY `id_placa` (`id_placa`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tipomarca`
--
ALTER TABLE `tipomarca`
  ADD PRIMARY KEY (`id_tipo_marca`);

--
-- Indices de la tabla `tipoobjeto`
--
ALTER TABLE `tipoobjeto`
  ADD PRIMARY KEY (`id_tipoObjeto`);

--
-- Indices de la tabla `tipovehiculo`
--
ALTER TABLE `tipovehiculo`
  ADD PRIMARY KEY (`id_tipoVehiculo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id_placa`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_documento` (`id_documento`),
  ADD KEY `id_tipoVehiculo` (`id_tipoVehiculo`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `id_modelo` (`id_modelo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detallematricula`
--
ALTER TABLE `detallematricula`
  MODIFY `id_detalle` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `fichas`
--
ALTER TABLE `fichas`
  MODIFY `n_ficha` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2901883;

--
-- AUTO_INCREMENT de la tabla `jornada`
--
ALTER TABLE `jornada`
  MODIFY `id_jornada` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `modelo`
--
ALTER TABLE `modelo`
  MODIFY `id_modelo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `nivelformacion`
--
ALTER TABLE `nivelformacion`
  MODIFY `id_nivelFormacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=728;

--
-- AUTO_INCREMENT de la tabla `programa`
--
ALTER TABLE `programa`
  MODIFY `id_programaFormacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `registroentradaobjetos`
--
ALTER TABLE `registroentradaobjetos`
  MODIFY `id_registroObjeto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT de la tabla `registroentradapersona`
--
ALTER TABLE `registroentradapersona`
  MODIFY `id_registro` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT de la tabla `registroentradavehiculos`
--
ALTER TABLE `registroentradavehiculos`
  MODIFY `id_registroVehiculo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipomarca`
--
ALTER TABLE `tipomarca`
  MODIFY `id_tipo_marca` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipoobjeto`
--
ALTER TABLE `tipoobjeto`
  MODIFY `id_tipoObjeto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipovehiculo`
--
ALTER TABLE `tipovehiculo`
  MODIFY `id_tipoVehiculo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detallematricula`
--
ALTER TABLE `detallematricula`
  ADD CONSTRAINT `detallematricula_ibfk_1` FOREIGN KEY (`id_ficha`) REFERENCES `fichas` (`n_ficha`),
  ADD CONSTRAINT `detallematricula_ibfk_2` FOREIGN KEY (`id_documento`) REFERENCES `usuario` (`id_documento`),
  ADD CONSTRAINT `detallematricula_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

--
-- Filtros para la tabla `fichas`
--
ALTER TABLE `fichas`
  ADD CONSTRAINT `fichas_ibfk_1` FOREIGN KEY (`id_jornada`) REFERENCES `jornada` (`id_jornada`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fichas_ibfk_2` FOREIGN KEY (`id_nivelFormacion`) REFERENCES `nivelformacion` (`id_nivelFormacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fichas_ibfk_3` FOREIGN KEY (`id_programaFormacion`) REFERENCES `programa` (`id_programaFormacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fichas_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `marca`
--
ALTER TABLE `marca`
  ADD CONSTRAINT `marca_ibfk_1` FOREIGN KEY (`id_tipo_marca`) REFERENCES `tipomarca` (`id_tipo_marca`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `marca_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD CONSTRAINT `modelo_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `nivelformacion`
--
ALTER TABLE `nivelformacion`
  ADD CONSTRAINT `nivelformacion_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `objetos`
--
ALTER TABLE `objetos`
  ADD CONSTRAINT `objetos_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `usuario` (`id_documento`),
  ADD CONSTRAINT `objetos_ibfk_2` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`),
  ADD CONSTRAINT `objetos_ibfk_3` FOREIGN KEY (`id_tipoObjeto`) REFERENCES `tipoobjeto` (`id_tipoObjeto`),
  ADD CONSTRAINT `objetos_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `programa`
--
ALTER TABLE `programa`
  ADD CONSTRAINT `programa_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `registroentradaobjetos`
--
ALTER TABLE `registroentradaobjetos`
  ADD CONSTRAINT `registroentradaobjetos_ibfk_1` FOREIGN KEY (`id_serial`) REFERENCES `objetos` (`id_serial`);

--
-- Filtros para la tabla `registroentradapersona`
--
ALTER TABLE `registroentradapersona`
  ADD CONSTRAINT `registroentradapersona_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `usuario` (`id_documento`);

--
-- Filtros para la tabla `registroentradavehiculos`
--
ALTER TABLE `registroentradavehiculos`
  ADD CONSTRAINT `registroentradavehiculos_ibfk_1` FOREIGN KEY (`id_placa`) REFERENCES `vehiculos` (`id_placa`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_4` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`),
  ADD CONSTRAINT `vehiculos_ibfk_2` FOREIGN KEY (`id_documento`) REFERENCES `usuario` (`id_documento`),
  ADD CONSTRAINT `vehiculos_ibfk_3` FOREIGN KEY (`id_tipoVehiculo`) REFERENCES `tipovehiculo` (`id_tipoVehiculo`),
  ADD CONSTRAINT `vehiculos_ibfk_4` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`),
  ADD CONSTRAINT `vehiculos_ibfk_5` FOREIGN KEY (`id_modelo`) REFERENCES `modelo` (`id_modelo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
