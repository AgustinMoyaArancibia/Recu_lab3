-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2024 a las 04:03:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tienda`
--

CREATE TABLE `tienda` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `talla` enum('S','M','L') NOT NULL,
  `color` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `imagen` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tienda`
--

INSERT INTO `tienda` (`id`, `nombre`, `precio`, `tipo`, `talla`, `color`, `stock`, `imagen`) VALUES
(1, 'Producto Modificado', 100.00, 'Camiseta', 'M', 'Rojo', 30, ''),
(2, 'Camiseta Básica', 10.99, 'Camiseta', 'M', 'Blanco', 39, './ImagenesDeVenta/2024/Camiseta Básica_Camiseta_M_agus@moya_2024-07-04.jpg'),
(4, 'Pantalón Jeans', 29.99, 'Pantalón', 'S', 'Azul', 20, ''),
(5, 'Pantalón Jeans', 29.99, 'Pantalón', 'M', 'Azul', 25, ''),
(6, 'Pantalón Jeans', 29.99, 'Pantalón', 'L', 'Azul', 15, ''),
(7, 'Camiseta Deportiva', 15.99, 'Camiseta', 'S', 'Negro', 10, ''),
(8, 'Camiseta Deportiva', 15.99, 'Camiseta', 'M', 'Negro', 20, ''),
(9, 'Camiseta Deportiva', 15.99, 'Camiseta', 'L', 'Negro', 25, ''),
(10, 'Pantalón Chino', 24.99, 'Pantalón', 'S', 'Beige', 30, ''),
(11, 'Pantalón Chino', 24.99, 'Pantalón', 'M', 'Beige', 35, ''),
(12, 'Pantalón Chino', 24.99, 'Pantalón', 'L', 'Beige', 20, ''),
(13, 'Camiseta Deportiva', 49.99, 'Camiseta', 'M', 'Rojo', 200, './Fotos/2024/Camiseta Deportiva_Camiseta.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `perfil` varchar(50) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `fechaAlta` date NOT NULL DEFAULT current_timestamp(),
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `mail`, `usuario`, `clave`, `perfil`, `foto`, `fechaAlta`, `fechaBaja`) VALUES
(1, 'admin@example.com', 'admin', 'admin', 'admin', 'uta/a/foto1.j', '2024-07-06', NULL),
(2, 'cliente1@example.com', 'cliente1', 'password1', 'cliente', 'ruta/a/foto1.jpg', '2024-07-06', NULL),
(3, 'empleado1@example.com', 'empleado1', 'password2', 'empleado', 'ruta/a/foto2.jpg', '2024-07-06', NULL),
(5, 'agus@moya', 'agusMoya', '$2y$10$qJdAWbYmdjTodrtAIgR00OfqMCWBSnDg4ahWnSphs6u8YEuCOtTRe', 'admin', 'agusMoya_administrador_2024-07-08.jpg', '2024-07-08', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `tipo` enum('Camiseta','Pantalón') NOT NULL,
  `talla` enum('S','M','L') NOT NULL,
  `stock` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `numero_pedido` int(11) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `email`, `nombre`, `tipo`, `talla`, `stock`, `fecha`, `numero_pedido`, `imagen`) VALUES
(1, 'usuario1@example.com', 'Camiseta Básica', 'Camiseta', 'M', 5, '2024-06-01', 0, ''),
(2, 'usuario2@example.com', 'Pantalón Jeans', 'Pantalón', 'S', 2, '2024-06-02', 0, ''),
(3, 'usuario1@example.com', 'Camiseta Deportiva', 'Camiseta', 'L', 3, '2024-06-03', 0, ''),
(4, 'usuario3@example.com', 'Pantalón Chino', 'Pantalón', 'M', 4, '2022-06-09', 0, ''),
(5, 'usuario4@example.com', 'Camiseta Básica', 'Camiseta', 'S', 1, '2024-06-05', 0, ''),
(6, 'usuario5@example.com', 'Pantalón Jeans', 'Pantalón', 'L', 6, '2024-06-06', 0, ''),
(7, 'usuario3@example.com', 'Camiseta Deportiva', 'Camiseta', 'M', 2, '2024-06-07', 0, ''),
(8, 'usuario2@example.com', 'Pantalón Chino', 'Pantalón', 'S', 3, '2024-06-08', 0, ''),
(9, 'usuario5@example.com', 'Camiseta Básica', 'Camiseta', 'L', 4, '2024-06-09', 0, ''),
(10, 'usuario1@example.com', 'Pantalón Jeans', 'Pantalón', 'M', 5, '2024-06-10', 0, ''),
(11, 'agus@moya', 'Camiseta Deportiva', 'Camiseta', 'S', 1, '2024-07-04', 0, ''),
(12, 'agus@moya', 'Pantalón Chino', 'Pantalón', 'L', 2, '2024-07-03', 1231232, 'asdasdasas'),
(13, 'agus@moya', 'Camiseta Básica', 'Camiseta', 'M', 1, '2024-07-04', 5959, 'Camiseta Básica_Camiseta_M_agus@moya_2024-07-04.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tienda`
--
ALTER TABLE `tienda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tienda`
--
ALTER TABLE `tienda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
