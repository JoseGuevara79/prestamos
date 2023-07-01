-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-06-2023 a las 20:05:31
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbprestamos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `IdArea` int(11) NOT NULL,
  `NombreArea` varchar(255) NOT NULL,
  `Localizacion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`IdArea`, `NombreArea`, `Localizacion`) VALUES
(1, 'Area de Ing.Civil', 'Oficina de gerencia'),
(2, 'Area de Ing.Sistemas', 'Oficina de gerencia'),
(3, 'Area de Agronomía', 'Ofcina 102'),
(4, 'Area de geologia', 'off 011'),
(5, 'Area de Quimica', 'off 302'),
(9, 'Ing Quimica', 'Off Principal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `IdCategoria` int(11) NOT NULL,
  `NombreCategoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`IdCategoria`, `NombreCategoria`) VALUES
(1, 'Monitores'),
(2, 'Teclados'),
(3, 'parlantes'),
(4, 'mouses'),
(5, 'mouses'),
(7, 'parlantes'),
(8, 'celulares'),
(9, 'laptops'),
(16, 'impresoras'),
(17, 'nuevacat'),
(20, 'nuevahoy');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleprestamos`
--

CREATE TABLE `detalleprestamos` (
  `IdDetalle` int(11) NOT NULL,
  `CantidadEquipos` int(11) DEFAULT NULL,
  `IdPrestamo` int(11) DEFAULT NULL,
  `IdEquipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalleprestamos`
--

INSERT INTO `detalleprestamos` (`IdDetalle`, `CantidadEquipos`, `IdPrestamo`, `IdEquipo`) VALUES
(1, 2, 1, 6),
(2, 2, 2, 10),
(3, 1, 12, 10),
(4, 1, 12, 7),
(5, 1, 12, 17),
(6, 1, 13, 15),
(7, 1, 14, 6),
(8, 1, 15, 2),
(9, 1, 15, 1),
(10, 1, 16, 16),
(11, 1, 17, 10),
(12, 1, 17, 6),
(13, 1, 18, 15),
(14, 1, 18, 6),
(15, 1, 19, 6),
(16, 1, 19, 7),
(17, 1, 20, 7),
(18, 1, 20, 15),
(19, 1, 20, 2),
(20, 1, 20, 10),
(21, 1, 20, 2),
(22, 1, 20, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `IdEquipo` int(11) NOT NULL,
  `IdCategoria` int(11) DEFAULT NULL,
  `NombreEquipo` varchar(255) DEFAULT NULL,
  `Marca` varchar(255) DEFAULT NULL,
  `NumSerie` varchar(255) DEFAULT NULL,
  `Modelo` varchar(255) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Disponibilidad` tinyint(1) DEFAULT 0 COMMENT '1=Disponible, 0=No disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`IdEquipo`, `IdCategoria`, `NombreEquipo`, `Marca`, `NumSerie`, `Modelo`, `Descripcion`, `Disponibilidad`) VALUES
(1, 1, 'MoSamsumg', 'Samsumg', '233nfftt445', 'MSdj22', 'En buen estado', 1),
(2, 4, 'logitech234', 'logitech', '5788yg6', '2023vt', 'En buen estado', 1),
(6, 16, 'Brother2023', 'Brother', '54766dfd7', '3492hbd', 'En buen estado', 0),
(7, 1, 'LEDTR', 'LG', '456455656L', '23034DF', 'En buen estado', 0),
(10, 7, 'phones', 'Pionner', '3344556', 'dst34', 'En buen estado', 1),
(15, 20, 'ventilador', 'khor', '24658b7876n', '2172023', 'funciona bien', 1),
(16, 20, 'two', 'khor', '24658f655', '2172023', 'funciona bien', 1),
(17, 8, 'celularIOS', 'APLE', '4677788Y55', '2020', 'funciona bien', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `IdPrestamo` int(11) NOT NULL,
  `IdUser` int(11) DEFAULT NULL,
  `FechaPrestamo` date DEFAULT NULL,
  `FechaDevolucion` date DEFAULT NULL,
  `FechaFinalizar` date DEFAULT NULL,
  `EstadoPrestamo` tinyint(1) DEFAULT 0 COMMENT '1=Disponible, 0=No disponible',
  `Vigencia` tinyint(4) DEFAULT 1,
  `IdArea` int(11) DEFAULT NULL,
  `prestamoper` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`IdPrestamo`, `IdUser`, `FechaPrestamo`, `FechaDevolucion`, `FechaFinalizar`, `EstadoPrestamo`, `Vigencia`, `IdArea`, `prestamoper`) VALUES
(1, 2, '2023-05-24', '2023-05-30', NULL, 1, 1, 1, 'Lucia Suarez'),
(2, 2, '2023-05-26', '2023-05-30', '2023-06-14', 1, 1, 2, 'Pedro Perez'),
(12, 3, '2023-06-08', '2023-06-22', '2023-06-24', 1, 1, 2, 'Lucia Suarez'),
(13, 3, '2023-06-15', '2023-06-16', '2023-06-16', 0, 1, 2, 'Dias Jose'),
(14, 3, '2023-06-09', '2023-06-16', '2023-06-26', 0, 1, 2, 'Lucha Dias'),
(15, 3, '2023-06-16', '2023-06-17', NULL, 1, 1, 2, 'Bernarda Guerrero'),
(16, 3, '2023-06-16', '2023-06-17', NULL, 1, 1, 2, 'Lucha Montiel'),
(17, 3, '2023-06-09', '2023-06-16', NULL, 1, 1, 2, 'Yuli Paez'),
(18, 3, '2023-06-22', '2023-06-23', NULL, 1, 1, 2, 'Anahi Montes'),
(19, 3, '2023-06-12', '2023-06-13', NULL, 1, 1, 1, 'Lucia Suarez'),
(20, 3, '2023-06-14', '2023-06-16', NULL, 1, 1, 2, 'Lu Soledad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IdUser` int(11) NOT NULL,
  `NombreUser` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Correo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IdUser`, `NombreUser`, `Password`, `Correo`) VALUES
(2, 'anthony', '123456', 'anthony@gmail.com'),
(3, 'jose', 'jose', 'jose@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`IdArea`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`IdCategoria`);

--
-- Indices de la tabla `detalleprestamos`
--
ALTER TABLE `detalleprestamos`
  ADD PRIMARY KEY (`IdDetalle`),
  ADD KEY `IdPrestamo` (`IdPrestamo`),
  ADD KEY `IdEquipo` (`IdEquipo`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`IdEquipo`),
  ADD KEY `IdCategoria` (`IdCategoria`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`IdPrestamo`),
  ADD KEY `IdUser` (`IdUser`),
  ADD KEY `fk_prestamos_areas` (`IdArea`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IdUser`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `IdArea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `IdCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `detalleprestamos`
--
ALTER TABLE `detalleprestamos`
  MODIFY `IdDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `IdEquipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `IdPrestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IdUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalleprestamos`
--
ALTER TABLE `detalleprestamos`
  ADD CONSTRAINT `detalleprestamos_ibfk_1` FOREIGN KEY (`IdPrestamo`) REFERENCES `prestamos` (`IdPrestamo`),
  ADD CONSTRAINT `detalleprestamos_ibfk_2` FOREIGN KEY (`IdEquipo`) REFERENCES `equipos` (`IdEquipo`);

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ibfk_1` FOREIGN KEY (`IdCategoria`) REFERENCES `categorias` (`IdCategoria`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `fk_prestamos_areas` FOREIGN KEY (`IdArea`) REFERENCES `areas` (`IdArea`),
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`IdUser`) REFERENCES `usuarios` (`IdUser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
