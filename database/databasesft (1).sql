-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 20-04-2025 a las 15:49:52
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
-- Base de datos: `databasesft`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallematricula`
--

CREATE TABLE `detallematricula` (
  `id_detalle` int(11) NOT NULL,
  `id_ficha` int(11) NOT NULL,
  `id_documento` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL
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
  `id_estado` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `estado`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichas`
--

CREATE TABLE `fichas` (
  `n_ficha` int(11) NOT NULL,
  `dias` varchar(100) DEFAULT NULL,
  `id_programaFormacion` int(11) DEFAULT NULL,
  `id_nivelFormacion` int(11) DEFAULT NULL,
  `id_jornada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fichas`
--

INSERT INTO `fichas` (`n_ficha`, `dias`, `id_programaFormacion`, `id_nivelFormacion`, `id_jornada`) VALUES
(1, NULL, NULL, NULL, NULL),
(2901870, NULL, 13, 2, 3),
(2901879, NULL, 1, 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jornada`
--

CREATE TABLE `jornada` (
  `id_jornada` int(11) NOT NULL,
  `jornada` varchar(255) NOT NULL
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
  `id_marca` int(11) NOT NULL,
  `nombre_marca` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id_marca`, `nombre_marca`) VALUES
(1, 'Yamaha'),
(2, 'Fender'),
(3, 'Gibson'),
(4, 'Roland'),
(5, 'Casio'),
(6, 'Chevrolet');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo`
--

CREATE TABLE `modelo` (
  `id_modelo` int(11) NOT NULL,
  `fecha_modelo` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modelo`
--

INSERT INTO `modelo` (`id_modelo`, `fecha_modelo`) VALUES
(1, '2025'),
(2, '2015'),
(3, '1901');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivelformacion`
--

CREATE TABLE `nivelformacion` (
  `id_nivelFormacion` int(11) NOT NULL,
  `nivelDeformacion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nivelformacion`
--

INSERT INTO `nivelformacion` (`id_nivelFormacion`, `nivelDeformacion`) VALUES
(1, 'Auxiliar'),
(2, 'Operario'),
(3, 'Técnico'),
(4, 'Tecnólogo'),
(5, 'Especialización Tecnológica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objetos`
--

CREATE TABLE `objetos` (
  `id_serial` varchar(15) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `caracteristica` varchar(100) NOT NULL,
  `foto` varchar(500) DEFAULT NULL,
  `id_marca` int(11) NOT NULL,
  `id_documento` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_tipoObjeto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objetos`
--

INSERT INTO `objetos` (`id_serial`, `nombre`, `caracteristica`, `foto`, `id_marca`, `id_documento`, `id_estado`, `id_tipoObjeto`) VALUES
('OBJ1015', 'Tablet iPad Pro 12.9”', 'M2, 256GB', '67a8d643309d28.60300968.png', 1, 1106227450, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa`
--

CREATE TABLE `programa` (
  `id_programaFormacion` int(11) NOT NULL,
  `nombrePrograma` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programa`
--

INSERT INTO `programa` (`id_programaFormacion`, `nombrePrograma`) VALUES
(1, 'Análisis y Desarrollo de Software'),
(2, 'Gestión Administrativa'),
(3, 'Diseño de Productos Industriales'),
(4, 'Mecatrónica'),
(5, 'Costureria'),
(6, 'Contabilidad y Finanzas'),
(7, 'Producción de Multimedia'),
(8, 'Electricidad Industrial'),
(9, 'Cocina'),
(10, 'Seguridad y Salud en el Trabajo'),
(11, 'Gestión de Redes de Datos'),
(12, 'Construcción de Edificaciones'),
(13, 'Mantenimiento de Equipos Biomédicos'),
(14, 'Producción Agropecuaria'),
(15, 'Operaciones de Comercio Exterior'),
(16, 'Atención Integral a la Primera Infancia'),
(17, 'Desarrollo de Aplicaciones Móviles'),
(18, 'Gestión de Empresas Turísticas'),
(19, 'Mantenimiento Electrónico e Instrumental Industrial'),
(20, 'Asistencia en Organización de Archivos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroentradaobjetos`
--

CREATE TABLE `registroentradaobjetos` (
  `id_registroObjeto` int(11) NOT NULL,
  `fechaEntrada` datetime NOT NULL,
  `fechaSalida` datetime NOT NULL,
  `observaciones` varchar(255) NOT NULL,
  `id_serial` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroentradapersona`
--

CREATE TABLE `registroentradapersona` (
  `id_registro` int(11) NOT NULL,
  `fechaEntrada` datetime NOT NULL,
  `fechaSalida` datetime NOT NULL,
  `id_documento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroentradavehiculos`
--

CREATE TABLE `registroentradavehiculos` (
  `id_registroVehiculo` int(11) NOT NULL,
  `fechaEntrada` datetime NOT NULL,
  `fechaSalida` datetime NOT NULL,
  `observaciones` int(11) NOT NULL,
  `id_placa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador\r\n'),
(2, 'Aprendiz\r\n'),
(3, 'Funcionario'),
(4, 'Instructor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoobjeto`
--

CREATE TABLE `tipoobjeto` (
  `id_tipoObjeto` int(11) NOT NULL,
  `tipoObjeto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipoobjeto`
--

INSERT INTO `tipoobjeto` (`id_tipoObjeto`, `tipoObjeto`) VALUES
(1, 'Electronico\r\n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipovehiculo`
--

CREATE TABLE `tipovehiculo` (
  `id_tipoVehiculo` int(11) NOT NULL,
  `tipoVehiculo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipovehiculo`
--

INSERT INTO `tipovehiculo` (`id_tipoVehiculo`, `tipoVehiculo`) VALUES
(1, 'MOTO'),
(2, 'CARRO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_documento` int(11) NOT NULL,
  `nombre_completo` varchar(50) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `contraseña` varchar(500) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_documento`, `nombre_completo`, `correo`, `contraseña`, `foto`, `id_estado`, `id_rol`) VALUES
(56789874, 'Cesar Arturo Esquivel', 'cesararturoesquivel@gmail.con', '$2y$12$rK3an.1RWxAWLQ0AanqFIuLJ5kv.FCyirFffjG11kJ/434gP0ePQy', '', 1, 4),
(93365453, 'Camilo', 'camilo@gmail.com', '$2y$12$ZcUeTJotI6.cdCXYcftOV.U1sUb/LL/1NJjDZE6auN3PvcpQ3pUFC', '', 1, 4),
(93386330, 'Cesar Esquivel', 'cesaresquivel@gmail.com', '', '', 2, 2),
(110473654, 'Perla Ximena Pinilla Foncesa', 'perlapinifo04@gmail.com', '', '', 1, 2),
(1023456789, 'María González', 'maria.gonzalez@mail.com', 'def45678', '', 2, 2),
(1034567891, 'Carlos Ramírez', 'carlos.ramirez@mail.com', 'ghi78901', '', 2, 2),
(1045678902, 'Ana Martínez', 'ana.martinez@mail.com', 'jkl01234', '', 2, 2),
(1056789013, 'Pedro Sánchez', 'pedro.sanchez@mail.com', 'mno34567', '', 2, 2),
(1067890124, 'Laura Torres', 'laura.torres@mail.com', 'pqr67890', '', 2, 2),
(1078901235, 'Andrés Castro', 'andres.castro@mail.com', 'stu90123', '', 2, 2),
(1089012346, 'Sofía López', 'sofia.lopez@mail.com', 'vwx23456', '', 2, 2),
(1090123457, 'David Herrera', 'david.herrera@mail.com', 'yzx56789', '', 2, 2),
(1101234568, 'Elena Díaz', 'elena.diaz@mail.com', 'abc89012', '', 2, 2),
(1106225365, 'Cesar Adrian Camargo', 'cesarAdrianCamargo@gmail.com', '$2y$12$mKLAErtUHJdVJNpMSOGVJuHIWpGLa0kfR4XCih3EE8L6UNc0sBYNG', '', 1, 1),
(1106227123, 'Mailo villanueva', 'mailo@gmail.con', '$2y$12$EONGG.jG0eACG9PEqeWuEO67iLBeBajNNG3CH2E2oTgONU11rfBla', NULL, 2, 2),
(1106227432, 'Gabriela Devia Marin', 'gabrieladeviamarin@gmail.com', '$2y$10$26KGkXegunxC7nwdD8y5xOegOWUvCUk.UkLxXEtfkE66zRaBG13g6', '680470227f8912.98104658.jpg', 1, 1),
(1106227450, 'Brandon Yulian Villanueva Serrano', 'brandonyulian10@gmail.com', '$2y$10$ajtfGIgDwPyO3Z3qPFM/QeI1ngGmtv9bqCb7qfL9y/7f73SH1BY.e', '', 1, 1),
(1107234125, 'Elizabet Sofia Enciso', 'ElizabetSofiaEnciso@gmail.com', '$2y$12$XijgfdJzPtmVUMS.eWb2OOJsZGu.uPl.IMPJwgPrFavN3b0hy/tQK', '', 1, 2),
(1107883452, 'Juan estaban vallejo', 'juanestebanvallejo@gmail.clm', '$2y$12$p5UcCZ2wl4HNJ1Se3a8ppehDJzWDPowi.wzKHuoeD0VVdhJGs5.6u', '', 1, 1),
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_placa` varchar(255) NOT NULL,
  `foto` varchar(500) DEFAULT NULL,
  `caracteristicas` varchar(255) NOT NULL,
  `foto_tarjeta_propiedad` varchar(500) DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `id_documento` int(11) NOT NULL,
  `id_tipoVehiculo` int(11) NOT NULL,
  `id_marca` int(11) NOT NULL,
  `id_modelo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id_placa`, `foto`, `caracteristicas`, `foto_tarjeta_propiedad`, `id_estado`, `id_documento`, `id_tipoVehiculo`, `id_marca`, `id_modelo`) VALUES
('ZSA321', 'vehiculo_68047d2246e409.68143330.png', 'hola a todesasasa', 'tarjeta_680476aae993a5.84479628.jpg', 2, 1106227432, 2, 2, 1);

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
  ADD KEY `id_programaFormacion` (`id_programaFormacion`);

--
-- Indices de la tabla `jornada`
--
ALTER TABLE `jornada`
  ADD PRIMARY KEY (`id_jornada`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`);

--
-- Indices de la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD PRIMARY KEY (`id_modelo`);

--
-- Indices de la tabla `nivelformacion`
--
ALTER TABLE `nivelformacion`
  ADD PRIMARY KEY (`id_nivelFormacion`);

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
  ADD PRIMARY KEY (`id_programaFormacion`);

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
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `fichas`
--
ALTER TABLE `fichas`
  MODIFY `n_ficha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2901883;

--
-- AUTO_INCREMENT de la tabla `jornada`
--
ALTER TABLE `jornada`
  MODIFY `id_jornada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `modelo`
--
ALTER TABLE `modelo`
  MODIFY `id_modelo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `nivelformacion`
--
ALTER TABLE `nivelformacion`
  MODIFY `id_nivelFormacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `programa`
--
ALTER TABLE `programa`
  MODIFY `id_programaFormacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `registroentradaobjetos`
--
ALTER TABLE `registroentradaobjetos`
  MODIFY `id_registroObjeto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registroentradapersona`
--
ALTER TABLE `registroentradapersona`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registroentradavehiculos`
--
ALTER TABLE `registroentradavehiculos`
  MODIFY `id_registroVehiculo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipoobjeto`
--
ALTER TABLE `tipoobjeto`
  MODIFY `id_tipoObjeto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipovehiculo`
--
ALTER TABLE `tipovehiculo`
  MODIFY `id_tipoVehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `fichas_ibfk_3` FOREIGN KEY (`id_programaFormacion`) REFERENCES `programa` (`id_programaFormacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `objetos`
--
ALTER TABLE `objetos`
  ADD CONSTRAINT `objetos_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `usuario` (`id_documento`),
  ADD CONSTRAINT `objetos_ibfk_2` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`),
  ADD CONSTRAINT `objetos_ibfk_3` FOREIGN KEY (`id_tipoObjeto`) REFERENCES `tipoobjeto` (`id_tipoObjeto`);

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
