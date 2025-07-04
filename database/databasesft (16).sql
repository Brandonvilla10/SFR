-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 04-07-2025 a las 22:37:44
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.2.28

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int NOT NULL,
  `estado` varchar(45) COLLATE utf8mb4_general_ci NOT NULL
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
  `dias` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
(2901870, 'Lunes, Martes, Miercoles, Jueves, Viernes', '2024-01-02', '2026-01-02', 1, 4, 1, 1),
(2901879, 'Lunes, Martes, Miercoles, Jueves, Viernes', '2024-02-02', '2026-02-02', 1, 4, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jornada`
--

CREATE TABLE `jornada` (
  `id_jornada` int NOT NULL,
  `jornada` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
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
  `nombre_marca` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `id_tipo_marca` int NOT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id_marca`, `nombre_marca`, `id_tipo_marca`, `id_estado`) VALUES
(1, 'Chevrolet', 1, 1),
(2, 'Renault', 1, 1),
(3, 'Mazda', 1, 1),
(4, 'Kia', 1, 1),
(5, 'Hyundai', 1, 1),
(6, 'Toyota', 1, 1),
(7, 'Ford', 1, 1),
(8, 'Nissan', 1, 1),
(9, 'Volkswagen', 1, 1),
(10, 'BMW', 1, 1),
(11, 'Mercedes-Benz', 1, 1),
(12, 'Suzuki', 1, 1),
(13, 'Peugeot', 1, 1),
(14, 'Fiat', 1, 1),
(15, 'Audi', 1, 1),
(16, 'Honda', 1, 1),
(17, 'Jeep', 1, 1),
(18, 'Dodge', 1, 1),
(19, 'Mitsubishi', 1, 1),
(20, 'Chery', 1, 1),
(21, 'JAC', 1, 1),
(22, 'BYD', 1, 1),
(23, 'Geely', 1, 1),
(24, 'DFSK', 1, 1),
(25, 'Great Wall', 1, 1),
(26, 'Samsung', 2, 1),
(27, 'LG', 2, 1),
(28, 'Sony', 2, 1),
(29, 'HP', 2, 1),
(30, 'Dell', 2, 1),
(31, 'Lenovo', 2, 1),
(32, 'Asus', 2, 1),
(33, 'Acer', 2, 1),
(34, 'Apple', 2, 1),
(35, 'Huawei', 2, 1),
(36, 'Xiaomi', 2, 1),
(37, 'Realme', 2, 1),
(38, 'Motorola', 2, 1),
(39, 'Canon', 2, 1),
(40, 'Nikon', 2, 1),
(41, 'Bosch', 2, 1),
(42, 'Philips', 2, 1),
(43, 'Panasonic', 2, 1),
(44, 'Toshiba', 2, 1),
(45, 'Oster', 2, 1),
(46, 'Black+Decker', 2, 1),
(47, 'Whirlpool', 2, 1),
(48, 'Mabe', 2, 1),
(49, 'Imusa', 2, 1),
(50, 'Electrolux', 2, 1),
(51, 'otro...', 2, 1),
(52, 'otro...', 1, 1),
(53, 'Gran Española', 2, 1),
(54, 'Kawasaki', 1, 1),
(55, 'Fama', 2, 1),
(56, 'Akt', 1, 1),
(57, 'Yamaha', 1, 1);

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
(1, '2005', 1),
(2, '2006', 1),
(3, '2007', 1),
(4, '2008', 1),
(5, '2009', 1),
(6, '2010', 1),
(7, '2011', 1),
(8, '2012', 1),
(9, '2013', 1),
(10, '2014', 1),
(11, '2015', 1),
(12, '2016', 1),
(13, '2017', 1),
(14, '2018', 1),
(15, '2019', 1),
(16, '2020', 1),
(17, '2021', 1),
(18, '2022', 1),
(19, '2023', 1),
(20, '2024', 1),
(21, '2025', 1),
(22, '2026', 1),
(23, '2027', 1),
(24, '1990', 1),
(25, '0000', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivelformacion`
--

CREATE TABLE `nivelformacion` (
  `id_nivelFormacion` int NOT NULL,
  `nivelDeformacion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nivelformacion`
--

INSERT INTO `nivelformacion` (`id_nivelFormacion`, `nivelDeformacion`, `id_estado`) VALUES
(1, 'Formación Complementaria', 1),
(2, 'Curso Corto', 1),
(3, 'Técnico', 1),
(4, 'Tecnólogo', 1),
(5, 'Especialización Técnica', 1),
(6, 'Especialización Tecnológica', 1),
(7, 'Operario', 1),
(8, 'Auxiliar', 1),
(9, 'Técnico en articulación con media', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int NOT NULL,
  `fecha` datetime NOT NULL,
  `tipo_evento` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tabla_afectada` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_afectado` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `documento_generador` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objetos`
--

CREATE TABLE `objetos` (
  `id_serial` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `caracteristica` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_marca` int NOT NULL,
  `id_documento` int NOT NULL,
  `id_estado` int NOT NULL,
  `id_tipoObjeto` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  'INSERT', 
  'Objetos', 
  NEW.id_serial, 
  CONCAT('Nuevo objeto registrado: ', NEW.Nombre), 
  @documento_generador
)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_objetos` AFTER UPDATE ON `objetos` FOR EACH ROW BEGIN
  IF NEW.id_estado = 3 AND OLD.id_estado <> 3 THEN
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'Registro entrada', 'objetos', NEW.id_serial, CONCAT('Ingreso de objeto con serial: ', NEW.id_serial), @documento_generador
    );

  ELSEIF NEW.id_estado = 4 AND OLD.id_estado <> 4 THEN
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'Eliminado', 'objetos', NEW.id_serial, CONCAT('Objeto con serial ', NEW.id_serial, ' fue eliminado'), @documento_generador
    );

  ELSE
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'UPDATE', 'objetos', NEW.id_serial, CONCAT('Objeto con serial ', NEW.id_serial, ' fue actualizado'), @documento_generador
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa`
--

CREATE TABLE `programa` (
  `id_programaFormacion` int NOT NULL,
  `nombrePrograma` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programa`
--

INSERT INTO `programa` (`id_programaFormacion`, `nombrePrograma`, `id_estado`) VALUES
(1, 'Análisis y Desarrollo de Software', 1),
(2, 'Sistemas', 1),
(3, 'Contabilización de Operaciones Comerciales y Financieras', 1),
(4, 'Gestión Administrativa', 1),
(5, 'Electricidad Industrial', 1),
(6, 'Producción Multimedia', 1),
(7, 'Logística Empresarial', 1),
(8, 'Mantenimiento de Equipos de Cómputo', 1),
(9, 'Gestión del Talento Humano', 1),
(10, 'Construcción', 1),
(11, 'Gestión Documental', 1),
(12, 'Mecánica Industrial', 1),
(13, 'Cocina', 1),
(14, 'Panadería', 1),
(15, 'Confección Industrial', 1),
(16, 'Gestión de Recursos Naturales', 1),
(17, 'Agroindustria Alimentaria', 1),
(18, 'Gestión Agroempresarial', 1),
(19, 'Producción Agropecuaria Ecológica', 1),
(20, 'Producción Pecuaria', 1),
(21, 'Cultivos Agrícolas', 1),
(22, 'Control Ambiental', 1),
(23, 'Gestión Contable y Financiera', 1),
(24, 'Instalaciones Eléctricas Residenciales', 1),
(25, 'Diseño e Integración de Automatismos Mecatrónicos', 1),
(26, 'Diseño e Instalación de Redes de Telecomunicaciones', 1),
(27, 'Gestión Comercial y Telemercadeo', 1),
(28, 'Gestión Bancaria y de Entidades Financieras', 1),
(29, 'Salud Pública', 1),
(30, 'Atención Integral al Adulto Mayor', 1),
(31, 'Atención a la Primera Infancia', 1),
(32, 'Asistencia Administrativa', 1),
(33, 'Servicios Farmacéuticos', 1),
(34, 'Manejo Ambiental', 1),
(35, 'Ejecución de Programas Deportivos', 1),
(36, 'Diseño e Integración Multimedia', 1),
(37, 'Producción de Medios Audiovisuales Digitales', 1),
(38, 'Diseño de Productos Industriales', 1),
(39, 'Gestión de Proyectos de Desarrollo Económico y Social', 1),
(40, 'Formulación de Proyectos', 1),
(41, 'Diseño y Desarrollo de Bases de Datos', 1),
(42, 'Programación de Aplicaciones Móviles', 1),
(43, 'Diseño de Experiencia de Usuario (UX/UI)', 1),
(44, 'Operación de Plantas y Sistemas de Potabilización de Agua', 1),
(45, 'Operación de Plantas de Tratamiento de Aguas Residuales', 1),
(46, 'Seguridad y Salud en el Trabajo', 1),
(47, 'Gestión Integral de la Calidad', 1),
(48, 'Supervisión de Obras Civiles', 1),
(49, 'Mantenimiento Electromecánico Industrial', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroentradaobjetos`
--

CREATE TABLE `registroentradaobjetos` (
  `id_registroObjeto` int NOT NULL,
  `fechaEntrada` datetime NOT NULL,
  `fechaSalida` datetime DEFAULT NULL,
  `observaciones` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observacionesSalida` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_serial` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroentradavehiculos`
--

CREATE TABLE `registroentradavehiculos` (
  `id_registroVehiculo` int NOT NULL,
  `fechaEntrada` datetime NOT NULL,
  `fechaSalida` datetime DEFAULT NULL,
  `observaciones` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observacionesSalida` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_placa` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int NOT NULL,
  `nombre_rol` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador\r\n'),
(2, 'Aprendiz\r\n'),
(3, 'Funcionario'),
(4, 'Instructor'),
(5, 'Vigilante'),
(6, 'Coordinador'),
(7, 'Invitado'),
(8, 'Super Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomarca`
--

CREATE TABLE `tipomarca` (
  `id_tipo_marca` int NOT NULL,
  `nombre_tipo_marca` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipomarca`
--

INSERT INTO `tipomarca` (`id_tipo_marca`, `nombre_tipo_marca`) VALUES
(1, 'Vehiculo'),
(2, 'Objeto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoobjeto`
--

CREATE TABLE `tipoobjeto` (
  `id_tipoObjeto` int NOT NULL,
  `tipoObjeto` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipoobjeto`
--

INSERT INTO `tipoobjeto` (`id_tipoObjeto`, `tipoObjeto`, `id_estado`) VALUES
(1, 'Herramientas de Construcción', 1),
(2, 'Maquinaria Industrial', 1),
(3, 'Equipos de Soldadura', 1),
(4, 'Instrumentos de Medición', 1),
(5, 'Equipos de Seguridad Industrial', 1),
(6, 'Materiales de Construcción', 1),
(7, 'Herramientas Eléctricas', 1),
(8, 'Equipos de Carpintería', 1),
(9, 'Herramientas Manuales', 1),
(10, 'Equipos de Plomería', 1),
(11, 'Instrumentos de Topografía', 1),
(12, 'Equipos de Mecánica', 1),
(13, 'Herramientas de Albañilería', 1),
(14, 'Equipos de Laboratorio', 1),
(15, 'Dispositivos Electrónicos', 1),
(16, 'Componentes Industriales', 1),
(17, 'Equipos de Pintura', 1),
(18, 'Herramientas de Corte', 1),
(19, 'Equipos de Climatización', 1),
(20, 'Instrumentos de Control', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipovehiculo`
--

CREATE TABLE `tipovehiculo` (
  `id_tipoVehiculo` int NOT NULL,
  `tipoVehiculo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `id_estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipovehiculo`
--

INSERT INTO `tipovehiculo` (`id_tipoVehiculo`, `tipoVehiculo`, `id_estado`) VALUES
(1, 'Carro', 1),
(2, 'Motocicleta', 1),
(3, 'Bus', 1),
(4, 'Camion', 1),
(6, 'Camioneta', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_documento` int NOT NULL,
  `nombre_completo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `contraseña` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_estado` int NOT NULL,
  `id_rol` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_documento`, `nombre_completo`, `correo`, `contraseña`, `foto`, `id_estado`, `id_rol`) VALUES
(1106227450, 'Brandon Yulian Villanueva Serrano', 'brandonyulian10@gmail.com', '$2y$10$oq4awyYEiSfQN1Zan20S5.zmwo7HUxEimmkHCKAjqhrkxPHu7oFDS', '68516b663f01b5.27287644.jpg', 3, 1);

--
-- Disparadores `usuario`
--
DELIMITER $$
CREATE TRIGGER `create_usuario` AFTER INSERT ON `usuario` FOR EACH ROW INSERT INTO notificaciones (
  fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
)
VALUES (
  NOW(), 'INSERT', 'usuario', NEW.id_documento, CONCAT('Nuevo usuario registrado: ', NEW.nombre_completo), 
  @documento_generador
)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_usuario` AFTER UPDATE ON `usuario` FOR EACH ROW BEGIN
  IF NEW.id_estado = 3 AND OLD.id_estado <> 3 THEN
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'Registro entrada', 'usuario', NEW.id_documento, CONCAT('Ingreso de persona con documento: ', NEW.id_documento), @documento_generador
    );

  ELSEIF NEW.id_estado = 4 AND OLD.id_estado <> 4 THEN
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'Eliminado', 'usuario', NEW.id_documento, CONCAT('Usuario con documento ', NEW.id_documento, ' fue eliminado'), @documento_generador
    );

  ELSE
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'UPDATE', 'usuario', NEW.id_documento, CONCAT('Usuario con documento ', NEW.id_documento, ' fue actualizado'), @documento_generador
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_placa` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `caracteristicas` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `foto_tarjeta_propiedad` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_estado` int NOT NULL,
  `id_documento` int NOT NULL,
  `id_tipoVehiculo` int NOT NULL,
  `id_marca` int NOT NULL,
  `id_modelo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
CREATE TRIGGER `update_vehiculos` AFTER UPDATE ON `vehiculos` FOR EACH ROW BEGIN
  IF NEW.id_estado = 3 AND OLD.id_estado <> 3 THEN
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'Registro entrada', 'vehiculos', NEW.id_placa, CONCAT('Ingreso de vehículo con placa: ', NEW.id_placa), @documento_generador
    );

  ELSEIF NEW.id_estado = 4 AND OLD.id_estado <> 4 THEN
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'Eliminado', 'vehiculos', NEW.id_placa, CONCAT('Vehículo con placa ', NEW.id_placa, ' fue eliminado'), @documento_generador
    );

  ELSE
    INSERT INTO notificaciones (
      fecha, tipo_evento, tabla_afectada, id_afectado, descripcion, documento_generador
    ) VALUES (
      NOW(), 'UPDATE', 'vehiculos', NEW.id_placa, CONCAT('Vehículo con placa ', NEW.id_placa, ' fue actualizado'), @documento_generador
    );
  END IF;
END
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
  MODIFY `id_detalle` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `fichas`
--
ALTER TABLE `fichas`
  MODIFY `n_ficha` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2901880;

--
-- AUTO_INCREMENT de la tabla `jornada`
--
ALTER TABLE `jornada`
  MODIFY `id_jornada` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `modelo`
--
ALTER TABLE `modelo`
  MODIFY `id_modelo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `nivelformacion`
--
ALTER TABLE `nivelformacion`
  MODIFY `id_nivelFormacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programa`
--
ALTER TABLE `programa`
  MODIFY `id_programaFormacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `registroentradaobjetos`
--
ALTER TABLE `registroentradaobjetos`
  MODIFY `id_registroObjeto` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registroentradapersona`
--
ALTER TABLE `registroentradapersona`
  MODIFY `id_registro` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registroentradavehiculos`
--
ALTER TABLE `registroentradavehiculos`
  MODIFY `id_registroVehiculo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tipomarca`
--
ALTER TABLE `tipomarca`
  MODIFY `id_tipo_marca` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipoobjeto`
--
ALTER TABLE `tipoobjeto`
  MODIFY `id_tipoObjeto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `tipovehiculo`
--
ALTER TABLE `tipovehiculo`
  MODIFY `id_tipoVehiculo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `fichas_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

--
-- Filtros para la tabla `marca`
--
ALTER TABLE `marca`
  ADD CONSTRAINT `marca_ibfk_1` FOREIGN KEY (`id_tipo_marca`) REFERENCES `tipomarca` (`id_tipo_marca`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `marca_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

--
-- Filtros para la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD CONSTRAINT `modelo_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

--
-- Filtros para la tabla `nivelformacion`
--
ALTER TABLE `nivelformacion`
  ADD CONSTRAINT `nivelformacion_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

--
-- Filtros para la tabla `objetos`
--
ALTER TABLE `objetos`
  ADD CONSTRAINT `objetos_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `usuario` (`id_documento`),
  ADD CONSTRAINT `objetos_ibfk_2` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`),
  ADD CONSTRAINT `objetos_ibfk_3` FOREIGN KEY (`id_tipoObjeto`) REFERENCES `tipoobjeto` (`id_tipoObjeto`),
  ADD CONSTRAINT `objetos_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

--
-- Filtros para la tabla `programa`
--
ALTER TABLE `programa`
  ADD CONSTRAINT `programa_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

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
  ADD CONSTRAINT `registroentradavehiculos_ibfk_1` FOREIGN KEY (`id_placa`) REFERENCES `vehiculos` (`id_placa`) ON DELETE CASCADE ON UPDATE CASCADE;

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
