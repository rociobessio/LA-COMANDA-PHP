-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2023 a las 18:49:36
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
(2, 'Cervezero', 'Mariano', '2023-11-03 00:00:00', '2023-11-20 00:00:00', '123mariano'),
(3, 'Cervezero', 'Emanuel', '2023-11-03 00:00:00', NULL, '123emanuel'),
(4, 'Cocinero', 'Luciana', '2023-11-03 00:00:00', NULL, '123luciana'),
(5, 'Mozo', 'Estela', '2023-11-03 00:00:00', NULL, '123estela'),
(6, 'Mozo', 'Lucas', '2023-11-03 00:00:00', NULL, '123lucas'),
(7, 'Mozo', 'Viviana', '2023-11-03 00:00:00', NULL, '123viviana'),
(8, 'Socio', 'Alberto Ramirez', '2023-11-03 00:00:00', NULL, '123albert'),
(9, 'Cocinero', 'Blas', '2023-11-03 00:00:00', NULL, '123blas'),
(10, 'Bartender', 'Romina', '2023-11-03 00:00:00', NULL, '123romina'),
(11, 'Mozo', 'Julieta', '2023-11-06 00:00:00', NULL, '123julieta'),
(12, 'socio', 'Rocio', '2023-11-14 00:00:00', NULL, '123rocio'),
(13, 'Socio', 'Mariano', '0000-00-00 00:00:00', NULL, '123Marian'),
(14, 'Candybar', 'Sofia', '2023-11-15 00:00:00', NULL, '123sofia'),
(16, 'Cervezero', 'Gaston', '2023-11-15 00:00:00', NULL, '123gaston'),
(17, 'Socio', 'admin', '2023-11-15 00:00:00', NULL, 'admin'),
(18, 'Bartender', 'Manuel Agustin', '2023-11-27 00:00:00', '2023-11-27 00:00:00', '123manuelags'),
(19, 'CandyBar', 'mariana', '2023-11-27 00:00:00', NULL, '123mariana');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `idEncuesta` int(11) NOT NULL,
  `codPedido` varchar(5) NOT NULL,
  `comentario` varchar(66) NOT NULL,
  `puntuacionMesa` int(11) NOT NULL,
  `puntuacionMozo` int(11) NOT NULL,
  `puntuacionRestaurante` int(11) NOT NULL,
  `puntuacionCocinero` int(11) NOT NULL,
  `codMesa` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturaciones`
--

CREATE TABLE `facturaciones` (
  `idFacturacion` int(11) NOT NULL,
  `total` float NOT NULL,
  `idMesa` int(11) NOT NULL,
  `metodoPago` varchar(10) NOT NULL,
  `fechaFacturacion` datetime NOT NULL,
  `pagada` tinyint(1) NOT NULL,
  `codPedido` varchar(5) NOT NULL
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
(3, 'con cliente comiendo', 'H4xIx'),
(4, 'con cliente esperando pedido', 'nHAx4'),
(5, 'con cliente comiendo', 'JjB2U'),
(7, 'con cliente comiendo', 'enpR9'),
(8, 'con cliente pagando', 'ECtXS'),
(9, 'con cliente comiendo', 'ngRK6'),
(10, 'con cliente esperando pedido', 'y1OYi'),
(11, 'cerrada', 'CmZuT'),
(12, 'cerrada', 'XdcMA'),
(13, 'cerrada', '1T37X'),
(14, 'cerrada', 'bdVdB'),
(15, 'cerrada', 'KNd7x');

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
  `fotoMesa` text DEFAULT NULL,
  `nombreCliente` varchar(100) NOT NULL,
  `codigoPedido` varchar(8) NOT NULL,
  `pedidoFacturado` tinyint(1) NOT NULL,
  `costoTotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`idPedido`, `estado`, `tiempoEstimadoPreparacion`, `tiempoInicio`, `tiempoFin`, `idMesa`, `fotoMesa`, `nombreCliente`, `codigoPedido`, `pedidoFacturado`, `costoTotal`) VALUES
(24, 'entregado', '00:20:00', '18:25:34', '19:50:49', 1, NULL, 'Esteban', 'yKSJZ', 0, 9000),
(25, 'entregado', '00:15:00', '20:08:58', '20:09:22', 2, NULL, 'Miriam', 'M8bwq', 0, 0),
(26, 'listo para servir', '00:00:00', '19:01:08', '19:03:18', 3, './imgs/2023-11-17_10-52-23_Lucas_Mesa_3.jpg', 'Lucas', 'jI010', 0, 0),
(28, 'entregado', '00:05:00', '00:20:19', '00:20:26', 5, NULL, 'Sofia', 'qGJyw', 0, 3300),
(29, 'entregado', '00:05:00', '10:59:58', '11:26:54', 5, './imgs/2023-11-24_10-25-14_Marta_Mesa_5.jpg', 'Marta', '5lKzN', 0, 6200),
(30, 'entregado', '00:05:00', '17:30:28', '17:30:43', 7, './imgs/2023-11-26_17-02-29_Lucas_Mesa_7.jpg', 'Lucas', 'XgVfG', 0, 4500),
(31, 'listo para servir', '00:05:00', '11:58:23', '12:09:14', 8, './imgs/2023-11-27_11-25-46_Joaquin Ramirez_Mesa_8.jpg', 'Joaquin Ramirez', '3qE1v', 0, 9700),
(32, 'entregado', '00:05:00', '12:14:38', '12:15:08', 9, './imgs/2023-11-27_12-08-00_Juanita Viale_Mesa_9.jpg', 'Juanita Viale', 'RGztd', 0, 8700),
(33, 'En preparacion', '00:20:00', '12:18:06', NULL, 10, './imgs/2023-11-27_12-16-45_Yanina_Mesa_10.jpg', 'Yanina', '7oljc', 0, 2700),
(34, 'entregado', '00:20:00', '14:44:12', '14:45:03', 5, './imgs/2023-11-27_13-19-29_Lucrecia_Mesa_5.jpg', 'Lucrecia', 'wqwd6', 0, 2700);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_productos`
--

CREATE TABLE `pedidos_productos` (
  `id` int(11) NOT NULL,
  `codPedido` varchar(10) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `tiempoEstimado` time NOT NULL,
  `estado` varchar(25) NOT NULL,
  `idEmpleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_productos`
--

INSERT INTO `pedidos_productos` (`id`, `codPedido`, `idProducto`, `tiempoEstimado`, `estado`, `idEmpleado`) VALUES
(24, 'qGJyw', 32, '00:05:00', 'entregado', 10),
(25, 'qGJyw', 33, '00:05:00', 'entregado', 10),
(26, 'qGJyw', 34, '00:05:00', 'entregado', 10),
(27, 'qGJyw', 35, '00:05:00', 'entregado', 10),
(28, '5lKzN', 24, '00:15:00', 'entregado', 9),
(29, '5lKzN', 30, '00:05:00', 'entregado', 0),
(30, '5lKzN', 26, '00:05:00', 'entregado', 9),
(31, '5lKzN', 26, '00:05:00', 'entregado', 0),
(32, 'XgVfG', 23, '00:15:00', 'entregado', 9),
(33, 'XgVfG', 29, '00:05:00', 'entregado', 16),
(34, 'XgVfG', 26, '00:05:00', 'entregado', 14),
(35, '3qE1v', 34, '00:05:00', 'entregado', 10),
(36, '3qE1v', 40, '00:18:00', 'entregado', 9),
(37, '3qE1v', 33, '00:05:00', 'entregado', 10),
(38, '3qE1v', 23, '00:15:00', 'entregado', 9),
(39, '3qE1v', 26, '00:05:00', 'entregado', 14),
(40, 'RGztd', 22, '00:20:00', 'entregado', 9),
(41, 'RGztd', 30, '00:05:00', 'entregado', 16),
(42, 'RGztd', 31, '00:05:00', 'entregado', 16),
(43, 'RGztd', 40, '00:18:00', 'entregado', 9),
(44, 'RGztd', 28, '00:05:00', 'entregado', 14),
(45, '7oljc', 21, '00:20:00', 'En preparacion', 9),
(46, '7oljc', 30, '00:05:00', 'pendiente', 0),
(47, 'wqwd6', 21, '00:20:00', 'entregado', 9),
(48, 'wqwd6', 31, '00:05:00', 'entregado', 16);

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
  `tiempoEstimado` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProducto`, `nombre`, `sector`, `precio`, `tipo`, `fechaBaja`, `tiempoEstimado`) VALUES
(21, 'Pizza Muzzarella', 'Cocina', 1200, 'Comida', NULL, '00:20:00'),
(22, 'Pizza Provoletta', 'Cocina', 1500, 'Comida', NULL, '00:20:00'),
(23, 'Hamburguesa Veggie', 'Cocina', 2000, 'Comida', NULL, '00:15:00'),
(24, 'Hamburguesa Kombat', 'Cocina', 2700, 'Comida', NULL, '00:15:00'),
(25, 'Tiramisu', 'CandyBar', 1000, 'Postre', NULL, '00:05:00'),
(26, 'Torta Oreo', 'CandyBar', 1000, 'Postre', NULL, '00:05:00'),
(27, 'Helado 2 Bochas', 'CandyBar', 1200, 'Postre', NULL, '00:05:00'),
(28, 'Helado 3 Bochas', 'CandyBar', 1500, 'Postre', NULL, '00:05:00'),
(29, 'Cerveza IPA', 'Cerveceria', 1500, 'Cerveza', NULL, '00:05:00'),
(30, 'Cerveza Honey', 'Cerveceria', 1500, 'Cerveza', NULL, '00:05:00'),
(31, 'Cerveza Scottish', 'Cerveceria', 1500, 'Cerveza', NULL, '00:05:00'),
(32, 'Campari', 'Barra', 2000, 'Trago', NULL, '00:05:00'),
(33, 'Daikiri', 'Barra', 2000, 'Trago', NULL, '00:05:00'),
(34, 'Margarita', 'Barra', 2000, 'Trago', NULL, '00:05:00'),
(35, 'Coca-Cola 1L', 'Barra', 1300, 'Bebida', NULL, '00:05:00'),
(36, 'Sprite 1L', 'Barra', 1300, 'Bebida', NULL, '00:05:00'),
(37, 'Agua Mineral 1L', 'Barra', 1000, 'Bebida', NULL, '00:05:00'),
(38, 'Malbec 700ml', 'Vinoteca', 2700, 'Bebida', NULL, '00:05:00'),
(39, 'Dadá 700ml', 'Vinoteca', 3000, 'Bebida', NULL, '00:05:00'),
(40, 'Papas Fritas Mediterraneas', 'Cocina', 2700, 'Comida', NULL, '00:18:00'),
(41, 'Pizza Napolitana', 'Cocina', 3000, 'Comida', NULL, '00:25:00');

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
  MODIFY `idEmpleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `idMesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
