-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-11-2023 a las 00:05:08
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
-- Base de datos: `trabajo-practico`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `idEmpleado` int(11) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `nombre` varchar(70) NOT NULL,
  `fechaAlta` datetime NOT NULL,
  `fechaBaja` datetime DEFAULT NULL,
  `clave` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`idEmpleado`, `rol`, `nombre`, `fechaAlta`, `fechaBaja`, `clave`) VALUES
(1, 'Socio', 'Jose', '2023-11-03 00:00:00', NULL, '123jose'),
(2, 'Cervezero', 'Mariano', '2023-11-03 00:00:00', NULL, '123mariano'),
(3, 'Bartender', 'Nicolas', '2023-11-03 00:00:00', NULL, '123nicolas'),
(4, 'Cocinero', 'Luciana', '2023-11-03 00:00:00', NULL, '123luciana'),
(5, 'Mozo', 'Estela', '2023-11-03 00:00:00', NULL, '123estela'),
(6, 'Mozo', 'Lucas', '2023-11-03 00:00:00', NULL, '123lucas'),
(7, 'Mozo', 'Viviana', '2023-11-03 00:00:00', NULL, '123viviana'),
(8, 'Socio', 'Alberto Ramirez', '2023-11-03 00:00:00', NULL, '123albert'),
(9, 'Socio', 'Blas', '2023-11-03 00:00:00', NULL, '123blas'),
(10, 'Socio', 'Romina', '2023-11-03 00:00:00', NULL, '123romina'),
(11, 'Mozo', 'Julieta', '2023-11-06 00:00:00', NULL, '123julieta'),
(12, 'socio', 'Rocio', '2023-11-14 00:00:00', NULL, '123rocio'),
(13, 'Socio', 'Mariano', '0000-00-00 00:00:00', NULL, '123Marian'),
(14, 'Candybar', 'Sofia', '2023-11-15 00:00:00', NULL, '123sofia'),
(16, 'Cervecero', 'Gaston', '2023-11-15 00:00:00', NULL, '123gaston'),
(17, 'Socio', 'admin', '2023-11-15 00:00:00', NULL, 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `idEncuesta` int(11) NOT NULL,
  `codigoMesa` varchar(5) NOT NULL,
  `comentario` varchar(66) NOT NULL,
  `puntuacionMesa` int(11) NOT NULL,
  `puntuacionMozo` int(11) NOT NULL,
  `puntuacionRestaurante` int(11) NOT NULL,
  `puntuacionCocinero` int(11) NOT NULL,
  `idMozo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturaciones`
--

CREATE TABLE `facturaciones` (
  `idFacturacion` int(11) NOT NULL,
  `detalle` varchar(100) NOT NULL,
  `total` float NOT NULL,
  `idMesa` int(11) NOT NULL,
  `metodoPago` tinyint(1) NOT NULL,
  `fechaFacturacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `idMesa` int(11) NOT NULL,
  `estado` text NOT NULL,
  `codigoMesa` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`idMesa`, `estado`, `codigoMesa`) VALUES
(1, 'cerrada', '12345'),
(2, 'cerrada', '50RYL'),
(3, 'cerrada', 'H4xIx'),
(4, 'cerrada', 'nHAx4'),
(5, 'cerrada', 'JjB2U'),
(7, 'cerrada', 'enpR9'),
(8, 'cerrada', 'ECtXS'),
(9, 'cerrada', 'ngRK6'),
(10, 'cerrada', 'y1OYi');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `tiempoEstimadoPreparacion` time NOT NULL,
  `tiempoInicio` time DEFAULT NULL,
  `tiempoFin` time DEFAULT NULL,
  `idMesa` int(11) NOT NULL,
  `fotoMesa` text NOT NULL,
  `nombreCliente` varchar(100) NOT NULL,
  `codigoPedido` varchar(8) NOT NULL,
  `pedidoFacturado` tinyint(1) NOT NULL,
  `costoTotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`idPedido`, `estado`, `tiempoEstimadoPreparacion`, `tiempoInicio`, `tiempoFin`, `idMesa`, `fotoMesa`, `nombreCliente`, `codigoPedido`, `pedidoFacturado`, `costoTotal`) VALUES
(1, 'listo para servir', '00:00:00', '00:16:51', NULL, 0, '2023-11-04_00-16-51_Marcos_Mesa_2.jpg', 'Marcos Acuña', '', 0, 0),
(2, 'En preparacion', '00:00:20', '23:46:06', NULL, 4, '2023-11-04_00-23-32_Esteban_Mesa_4.jpg', 'Esteban', 'l8Te7', 0, 0),
(3, 'listo para servir', '00:10:00', '17:57:30', '18:01:12', 1, '2023-11-04_01-26-55_Raul_Mesa_1.jpg', 'Raul', 'qbf8D', 0, 0),
(4, 'entregado', '00:20:00', '23:52:32', '00:21:31', 1, '2023-11-04_01-30-08_Raul_Mesa_1.jpg', 'Raul', 'zqhl8', 1, 0),
(5, 'listo para servir', '00:13:00', '12:51:40', '18:02:18', 1, '2023-11-04_01-32-42_Raul_Mesa_1.jpg', 'Raul', 'gpmfC', 0, 0),
(6, 'En preparacion', '00:15:00', '12:44:27', NULL, 5, './imgs/2023-11-05_20-31-31_Estela Ramirez_Mesa_5.jpg', 'Estela Ramirez', 'aZVJe', 0, 0),
(7, 'pendiente', '00:00:00', '20:57:05', NULL, 4, './imgs/2023-11-05_20-57-05_Mariano_Mesa_4.jpg', 'Mariano', '6IQiy', 0, 0),
(8, 'En preparacion', '00:18:00', '12:38:14', NULL, 4, './imgs/2023-11-05_20-57-35_Mariano_Mesa_4.jpg', 'Mariano', 'tHYky', 0, 0),
(9, 'pendiente', '00:00:00', '16:44:49', NULL, 3, './imgs/2023-11-07_16-44-49_Lucas_Mesa_3.jpg', 'Lucas', 'mFeIk', 0, 0),
(10, 'En preparacion', '00:18:00', '12:42:32', NULL, 3, './imgs/2023-11-07_16-46-06_Lucas_Mesa_3.jpg', 'Lucas', 'FCXzB', 0, 0),
(11, 'En preparación', '00:13:00', '13:03:17', NULL, 3, './imgs/2023-11-10_13-02-19_Lucas_Mesa_3.jpg', 'Lucas', '3QVRa', 0, 0),
(12, 'En preparacion', '00:10:00', '13:05:33', NULL, 8, './imgs/2023-11-10_13-05-03_Ramiro_Mesa_8.jpg', 'Ramiro', '59m5j', 0, 0),
(13, 'En preparacion', '00:10:00', '13:30:06', NULL, 8, './imgs/2023-11-10_13-26-36_Ramiro_Mesa_8.jpg', 'Ramiro', 'xNgxm', 0, 0),
(14, 'entregado', '00:15:00', '14:40:05', '14:40:41', 10, './imgs/2023-11-13_14-37-39_Hernan_Mesa_10.jpg', 'Hernan', 'sMISh', 0, 0),
(15, 'pendiente', '00:00:00', NULL, NULL, 9, './imgs/2023-11-13_14-57-24_Juliana_Mesa_9.jpg', 'Juliana', 'HodRk', 0, 0),
(16, 'pendiente', '00:00:00', NULL, NULL, 9, './imgs/2023-11-13_14-58-16_Juliana_Mesa_9.jpg', 'Juliana', 'Vorn9', 0, 0),
(17, 'pendiente', '00:00:00', NULL, NULL, 2, './imgs/2023-11-15_17-54-23_Paula_Mesa_2.jpg', 'Paula', '55Xdr', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_productos`
--

CREATE TABLE `pedidos_productos` (
  `id` int(11) NOT NULL,
  `codPedido` varchar(10) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `tiempoEstimado` datetime NOT NULL,
  `estado` varchar(25) NOT NULL,
  `idEmpleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProducto` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `precio` float NOT NULL,
  `tipo` varchar(15) NOT NULL,
  `fechaBaja` datetime DEFAULT NULL,
  `tiempoEstimado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`idEmpleado`);

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`idEncuesta`);

--
-- Indices de la tabla `facturaciones`
--
ALTER TABLE `facturaciones`
  ADD PRIMARY KEY (`idFacturacion`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`idMesa`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`);

--
-- Indices de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `idEmpleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `idEncuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturaciones`
--
ALTER TABLE `facturaciones`
  MODIFY `idFacturacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `idMesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
