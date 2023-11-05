-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-11-2023 a las 03:46:23
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
  `fechaBaja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`idEmpleado`, `rol`, `nombre`, `fechaAlta`, `fechaBaja`) VALUES
(1, 'Pastelero', 'Jose', '2023-11-03 00:00:00', NULL),
(2, 'Cervezero', 'Mariano', '2023-11-03 00:00:00', NULL),
(3, 'Bartender', 'Nicolas', '2023-11-03 00:00:00', NULL),
(4, 'Cocinero', 'Luciana', '2023-11-03 00:00:00', NULL),
(5, 'Mozo', 'Estela', '2023-11-03 00:00:00', NULL),
(6, 'Mozo', 'Lucas', '2023-11-03 00:00:00', NULL),
(7, 'Mozo', 'Viviana', '2023-11-03 00:00:00', NULL),
(8, 'Socio', 'Alberto Ramirez', '2023-11-03 00:00:00', NULL),
(9, 'Socio', 'Blas', '2023-11-03 00:00:00', NULL),
(10, 'Socio', 'Romina', '2023-11-03 00:00:00', NULL);

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
(1, 'abierta', '12345'),
(2, 'abierta', '50RYL'),
(3, 'cerrada', 'H4xIx'),
(4, 'abierta', 'nHAx4'),
(5, 'abierta', 'JjB2U'),
(7, 'BAJA', 'enpR9');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `tiempoEstimado` time NOT NULL,
  `tiempoInicio` time NOT NULL,
  `tiempoFin` time DEFAULT NULL,
  `idMesa` int(11) NOT NULL,
  `foto` text NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `nombreCliente` varchar(100) NOT NULL,
  `codigoPedido` varchar(5) NOT NULL,
  `pedidoFacturado` tinyint(1) NOT NULL
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
  `fechaBaja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProducto`, `nombre`, `sector`, `precio`, `tipo`, `fechaBaja`) VALUES
(1, 'Heineken 1L', 'Cerveceria', 1200, 'Cerveza', NULL),
(2, 'Quilmes 1L', 'Cerveceria', 1200, 'Cerveza', NULL),
(3, 'Hamburguesa Veggie', 'Cocina', 2300, 'Comida', NULL),
(4, 'Mega Burguer', 'Cocina', 2700, 'Comida', NULL),
(5, 'Pizza napolitana', 'Cocina', 2300, 'Comida', NULL),
(6, 'Pizza provoletta', 'Cocina', 2500, 'Comida', NULL),
(7, 'Faina', 'Cocina', 600, 'Comida', NULL),
(8, 'Papas Fritas mediterraneas', 'Cocina', 2000, 'Comida', NULL),
(9, 'Ensalada', 'Cocina', 1200, 'Comida', NULL),
(10, 'Helado 3 bochas', 'CandyBar', 1100, 'Postre', NULL),
(11, 'Tiramisu', 'CandyBar', 2000, 'Postre', NULL),
(12, 'Torta de oreo', 'CandyBar', 2200, 'Postre', NULL),
(13, 'Malbec 1L', 'Vinoteca', 3000, 'Bebida', NULL),
(14, 'Sensei Malbec 750ML', 'Vinoteca', 3200, 'Bebida', NULL),
(15, 'Dos Puentes 750ML', 'Vinoteca', 3300, 'Bebida', NULL),
(16, 'Cabernet 750ML', 'Vinoteca', 2900, 'Bebida', NULL),
(17, 'Agua Mineral', 'Cocina', 1000, 'Bebida', NULL),
(18, 'Coca Cola 1L', 'Cocina', 1000, 'Bebida', NULL),
(19, 'Fanta', 'Cocina', 800, 'Bebida', '2023-11-03 00:00:00');

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
  MODIFY `idEmpleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `idMesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
