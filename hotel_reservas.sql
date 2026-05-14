-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-05-2026 a las 21:04:16
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
-- Base de datos: `hotel_reservas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document_types`
--

CREATE TABLE `document_types` (
  `ID` int(11) NOT NULL,
  `Name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `document_types`
--

INSERT INTO `document_types` (`ID`, `Name`) VALUES
(1, 'Cédula de Ciudadanía'),
(2, 'Pasaporte'),
(3, 'Cédula de Extranjería');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id`, `nombre`, `descripcion`) VALUES
(1, 'disponible', 'disponible para su uso'),
(2, 'ocupado', 'la habitacion esta ocupado no se puede usar por el momento.'),
(3, 'mantenimiento', 'la habitacion se encuentra en mantenimiento'),
(4, 'Confirmado', 'la habitacion fue reservada con exito'),
(5, 'Cancelado', 'La reserva fue Cancelada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id` int(11) NOT NULL,
  `num_habitacion` varchar(10) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `num_camas` int(11) DEFAULT NULL,
  `max_personas` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id`, `num_habitacion`, `id_categoria`, `num_camas`, `max_personas`, `descripcion`, `precio`, `id_estado`) VALUES
(1, '101', 1, 1, 2, 'Habitación sencilla con servicios básicos.', 150000.00, 1),
(2, '102', 1, 2, 3, 'Habitación estándar con dos camas individuales.', 150000.00, 1),
(3, '201', 2, 1, 2, 'Habitación deluxe con aire acondicionado y minibar.', 300000.00, 1),
(4, '301', 3, 2, 4, 'Suite de lujo con sala de estar y vista panorámica.', 550000.00, 1),
(5, '101', 1, 1, 1, 'Habitación sencilla perfecta para viajeros individuales.', 150000.00, 1),
(6, '102', 1, 1, 1, 'Habitación estándar con servicios esenciales.', 150000.00, 1),
(7, '103', 1, 2, 2, 'Habitación estándar doble amplia.', 150000.00, 1),
(8, '104', 1, 2, 2, 'Habitación estándar con vista al jardín.', 150000.00, 1),
(9, '105', 2, 1, 2, 'Deluxe con cama King y acabados modernos.', 300000.00, 1),
(10, '201', 2, 1, 2, 'Deluxe superior con iluminación inteligente.', 300000.00, 1),
(11, '202', 2, 2, 4, 'Deluxe familiar con dos camas dobles.', 300000.00, 1),
(12, '203', 2, 2, 4, 'Deluxe espaciosa para grupos pequeños.', 300000.00, 1),
(13, '204', 3, 1, 2, 'Habitación ejecutiva con escritorio de trabajo y café premium.', 550000.00, 1),
(14, '205', 3, 1, 2, 'Suite ejecutiva ideal para estancias de negocios.', 550000.00, 1),
(15, '206', 3, 2, 4, 'Ejecutiva doble con acceso a sala de reuniones.', 550000.00, 1),
(16, '207', 3, 1, 2, 'Confort ejecutivo con máxima insonorización.', 550000.00, 1),
(17, '301', 4, 1, 2, 'Suite de lujo con sala de estar independiente.', 1200000.00, 1),
(18, '302', 4, 1, 2, 'Suite con bañera de hidromasaje y vista panorámica.', 1200000.00, 1),
(19, '303', 4, 2, 4, 'Suite familiar premium con minibar incluido.', 1200000.00, 1),
(20, '304', 4, 1, 2, 'Suite romántica con decoración especial.', 1200000.00, 1),
(21, '305', 5, 2, 6, 'Máximo lujo. Tres ambientes y terraza privada.', 3500000.00, 1),
(22, '306', 5, 1, 2, 'Suite Imperial con atención personalizada 24h.', 3500000.00, 1),
(23, '307', 5, 2, 5, 'Penthouse Suite con cocina y comedor privado.', 3500000.00, 1),
(24, '308', 5, 1, 2, 'La Suite más exclusiva del hotel con vista al mar/ciudad.', 3500000.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pagos`
--

CREATE TABLE `metodos_pagos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_pagos`
--

INSERT INTO `metodos_pagos` (`id`, `nombre`) VALUES
(1, 'nequi'),
(2, 'daviplata'),
(3, 'bancolombia'),
(4, 'efecty');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_habitacion` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `num_personas` int(11) NOT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_metodo_pago` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `id_user`, `id_habitacion`, `fecha_inicio`, `fecha_final`, `num_personas`, `id_estado`, `Descripcion`, `precio`, `id_metodo_pago`, `created_at`, `updated_at`) VALUES
(5, 23, 3, '2026-05-28', '2026-05-29', 1, 1, '0', 300000.00, 2, '2026-04-27 19:48:46', '2026-05-11 16:07:42'),
(6, 23, 1, '2026-04-28', '2026-04-30', 1, 1, '', 240000.00, 1, '2026-04-27 21:02:32', '2026-04-27 21:02:32'),
(7, 24, 1, '2026-04-29', '2026-05-02', 4, 1, '', 390000.00, 2, '2026-04-27 22:19:13', '2026-04-27 22:19:13'),
(8, 24, 1, '2026-04-28', '2026-04-29', 1, 1, '', 120000.00, 1, '2026-04-28 02:11:13', '2026-04-28 02:11:13'),
(9, 23, 14, '2026-04-30', '2026-05-01', 1, 1, '', 550000.00, 2, '2026-04-29 14:19:14', '2026-04-29 14:19:14'),
(10, 23, 8, '2026-06-03', '2026-06-04', 1, 1, '0', 150000.00, 1, '2026-04-30 11:48:16', '2026-05-11 16:07:16'),
(11, 23, 3, '2026-05-20', '2026-05-21', 1, 1, '0', 300000.00, 1, '2026-05-04 15:59:08', '2026-05-11 16:06:28'),
(12, 23, 1, '2026-06-04', '2026-06-05', 2, 1, '0', 160000.00, 2, '2026-05-04 15:59:54', '2026-05-11 16:06:50'),
(17, 25, 1, '2026-05-15', '2026-05-17', 1, 1, '0', 300000.00, 2, '2026-05-11 14:03:49', '2026-05-11 14:53:30'),
(18, 25, 10, '2026-05-12', '2026-05-14', 3, 1, '0', 640000.00, 4, '2026-05-11 14:34:23', '2026-05-11 14:53:36'),
(20, 25, 1, '2026-07-11', '2026-07-13', 1, 1, '0', 300000.00, 3, '2026-05-11 14:46:45', '2026-05-11 14:52:54'),
(22, 25, 4, '2026-05-13', '2026-05-14', 1, 1, '', 550000.00, 1, '2026-05-11 15:06:56', '2026-05-11 15:06:56'),
(23, 25, 13, '2026-05-26', '2026-05-28', 2, 1, '', 1120000.00, 2, '2026-05-11 15:22:49', '2026-05-11 15:22:49'),
(26, 25, 9, '2026-05-14', '2026-05-16', 1, 1, '', 600000.00, 1, '2026-05-14 02:53:37', '2026-05-14 02:53:37'),
(27, 25, 9, '2026-05-14', '2026-05-16', 1, 1, '', 600000.00, 1, '2026-05-14 02:54:50', '2026-05-14 02:54:50'),
(29, 25, 13, '2026-05-14', '2026-05-15', 1, 1, 'prueba de correos', 550000.00, 1, '2026-05-14 12:38:47', '2026-05-14 12:38:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `type_rooms`
--

CREATE TABLE `type_rooms` (
  `ID` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `descripcion` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `type_rooms`
--

INSERT INTO `type_rooms` (`ID`, `name`, `descripcion`) VALUES
(1, 'standar', 'Esta habitación ofrece un refugio acogedor y eficiente, diseñado para quienes buscan un descanso reparador sin complicaciones. Con un estilo contemporáneo y minimalista, el espacio está optimizado para garantizar tu comodidad.'),
(2, 'deluxe', 'Eleva tu experiencia con nuestra Habitación Deluxe. Este espacio no solo es más amplio, sino que ha sido curado con detalles de diseño superior y vistas privilegiadas. Es el lugar donde la elegancia se encuentra con la relajación.'),
(3, 'plata', 'Nuestras habitaciones de tipo plata es más que una habitación; es un apartamento privado diseñado para cautivar los sentidos. Con ambientes separados para el descanso y el ocio, ofrece la privacidad y el prestigio que los huéspedes más exigentes esperan.'),
(4, 'oro', 'Ambas descripciones dejan unos 20 caracteres libres, lo cual es ideal si luego decides añadir un punto extra o si el sistema de codificación de caracteres (como UTF-8) ocupa un poco más de espacio en ciertos símbolos.'),
(5, 'diamante', 'Disfrute de un santuario de lujo moderno con nuestra exclusiva Habitación Diamante. Con tecnología domótica integral y una estética geométrica impecable, cada detalle brilla con luz propia para ofrecerle una estancia multifacética.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `ID` int(20) NOT NULL,
  `document_type_id` int(20) NOT NULL,
  `document_number` bigint(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`ID`, `document_type_id`, `document_number`, `name`, `last_name`, `phone`, `email`, `password`) VALUES
(1, 1, 1030281010, 'eder', 'ramirez', '310 238 0409', 'ederley@gmail.com', '$2y$10$diwimErpYXyhUahc144ZbuUuNFOXy8VBWR0XiJhxfIbAjVTeTesQe'),
(3, 1, 1030281011, 'samuel', 'rodriguez', '321 401 1031', 'samuel@gmail.com', '$2y$10$cJLcupk7lkWfapTJ9k8ToustTYS8AV7iMkqW9Zi0swdnzsRC725hO'),
(4, 2, 1030281012, 'ederley', 'chamoy', '310 250 9365', 'holamundo@gmail.com', '$2y$10$.CBoKmuiq.qX32X5T9Ef5u2aHbsjgDitp7/UpnlH8muVS9oulBK4i'),
(6, 3, 1029189900, 'ederley', 'rodriguez', '321 409 2020', 'muelon1010@gmail.com', '$2y$10$AVNcmymVCHzlP8kdSwKD8.hJZy2fqL7tNk1TDt54Zptb88Li9/K4i'),
(7, 2, 1010389113, 'blue', 'red', '110 101 1010', 'barcelona@gmail.com', '$2y$10$Q6iqlxXbsnW9E5ZpDZHUcO1wPZdtEThx0ttoxo6qlEmqgpaNFkcUe'),
(8, 1, 1020, 'ederley', 'eder', '310 200 2020', 'eder123@gmail.com', '$2y$10$pJr5efehMRUK6ASuASdkG.UQP2g2uwtFmALmhlMLpWtZajzngCzey'),
(9, 1, 101010010, 'mira', 'florez', '100 100 1000', 'correopum@gmail.com', '$2y$10$kn4rfSj3mLS5HIj4G08ewee7nWpWp7JszxEA3NRAartCnP/MlkfdK'),
(16, 1, 1030299999, 'lucas', 'dalto', '310 200 1030', 'programador@gmail.com', '$2y$10$a0A3U0Z5tbegeCFqencrkOinr24IOKENG6l.HN2hUEOeoMJJvZc1m'),
(17, 1, 1040281011, 'todo', 'code', '321 403 306', 'todoCode@gmail.com', '$2y$10$YtXr/6GcVm32JvaW3CvP3.0m3ITdJvM3gbS0go.bBrKiuur9kScg2'),
(18, 1, 1020181777, 'gom', 'giom', '1030281909', 'programadoreder10000@gmail.com', '$2y$10$RlgQTmiYqqQTnm4p904Q..j3o1W3/Dm2U.kNNJ4KigzizvGmLL3MC'),
(19, 1, 1111111111111, 'test', 'test', '1111111111111', 'test@gmail.com', '$2y$10$OWKYXvgBllsyMlZru16SPei5uz.mIDBLcp69Ua8KtQ/3Cr8PnnO.i'),
(20, 1, 1030281, 'test', 'test', '1030281919', 'tester@gmail.com', '$2y$10$nUiOa.oHNKwuFjACJr9eWebGvxsEEHJRpVXL3S4ZA2xUeylLfmXDW'),
(21, 1, 10000000, 'prueba', 'prueba', '10000000000', 'prueba@gmail.com', '$2y$10$Y5/jMNMSJ973ZUCAbJiRnemI1cw5N3Z90V2P8ViiH6y9HgoOSHHMO'),
(22, 1, 1020180000, 'prueba', 'prueba', '111111111111111', 'prueba1@gmail.com', '$2y$10$exmfK8AjBp4hwygVsJ0oIu0xWs7n//qmrCuKo.l/qCrMtkLk9IZpS'),
(23, 1, 1030289090, 'william', 'duarte', '3102394020', 'adsosenawd@gmail.com', '$2y$10$w.IDAFTiqRg7ntsAGQ3VFOy19cqTt2mG3tDU6Je/yE1J34FM0bN/G'),
(24, 3, 4838299283, 'watson', 'honks', '31020929', 'watson@gmail.com', '$2y$10$nPo0m.QDKUAiXWYmc65Y6.C9Lp17V8ray1nMcWVhzZ/VsIBn0Ies6'),
(25, 1, 58934859494, 'eder', 'ley', '390546307', 'programadoreder0@gmail.com', '$2y$10$0fPdlMd9e7J410IXeBXg3.kowkIq7jUkly5G7TXcIVTfmtxl6d9b.');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_estado` (`id_estado`),
  ADD KEY `fk_habitacion_categoria` (`id_categoria`);

--
-- Indices de la tabla `metodos_pagos`
--
ALTER TABLE `metodos_pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reserva_habitacion` (`id_habitacion`),
  ADD KEY `fk_reserva_estado` (`id_estado`),
  ADD KEY `fk_reserva_usuario` (`id_user`),
  ADD KEY `fk_reserva_metodo_pago` (`id_metodo_pago`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `type_rooms`
--
ALTER TABLE `type_rooms`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `document_number` (`document_number`),
  ADD KEY `document_type_id` (`document_type_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `document_types`
--
ALTER TABLE `document_types`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `metodos_pagos`
--
ALTER TABLE `metodos_pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `type_rooms`
--
ALTER TABLE `type_rooms`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `fk_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `fk_habitacion_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `type_rooms` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reserva_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `fk_reserva_habitacion` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id`),
  ADD CONSTRAINT `fk_reserva_metodo_pago` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pagos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reserva_usuario` FOREIGN KEY (`id_user`) REFERENCES `users` (`ID`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_usuarios_documentos` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
