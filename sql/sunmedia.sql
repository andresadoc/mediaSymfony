-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2019 a las 04:16:41
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE sunmedia;

--
-- Base de datos: `sunmedia`
--
USE sunmedia;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `x_position` int(11) NOT NULL,
  `y_position` int(11) NOT NULL,
  `z_position` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) DEFAULT NULL,
  `external_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `media_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `state` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campaign_advertisements`
--

CREATE TABLE `campaign_advertisements` (
  `campaign_id` int(11) NOT NULL,
  `advertisements_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `roles` tinytext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:simple_array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `is_active`, `created_at`, `modified_at`, `roles`) VALUES
(1, 'Publisher', 'publisher', '$argon2id$v=19$m=65536,t=4,p=1$Q1g2bjZFQVI2d1BiZHQ3ag$6mCzKUE8SOK2pIbs5R75Do6eW1uw2brIwFznuHGI774', 1, '2019-11-13 22:48:22', '2019-11-13 22:48:22', 'PUBLISHER'),
(2, 'Admin', 'administrator', '$argon2id$v=19$m=65536,t=4,p=1$RGdHTWV1UERDeXZncUIzcA$nf9B95idNcXD7gUxXbU8Cg+0PYNovOohBHuqZJNfObs', 1, '2019-11-13 22:51:49', '2019-11-13 22:51:49', 'ADMIN'),
(3, 'Advertiser', 'advertiser', '$argon2id$v=19$m=65536,t=4,p=1$S3NObkh4M0lvS2xEMTBadQ$RpjN1mNdTJkc0vXeDhlKZlHfEw5KhgkO9bf6+Y5z3Ps', 1, '2019-11-15 04:12:13', '2019-11-15 04:12:13', 'ADVERTISER');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5C755F1E61220EA6` (`creator_id`);

--
-- Indices de la tabla `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E373747040C86FCE` (`publisher_id`),
  ADD KEY `IDX_E373747061220EA6` (`creator_id`);

--
-- Indices de la tabla `campaign_advertisements`
--
ALTER TABLE `campaign_advertisements`
  ADD PRIMARY KEY (`campaign_id`,`advertisements_id`),
  ADD KEY `IDX_EC64FB91F639F774` (`campaign_id`),
  ADD KEY `IDX_EC64FB916DB58F3E` (`advertisements_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `advertisements`
--
ALTER TABLE `advertisements`
  ADD CONSTRAINT `FK_5C755F1E61220EA6` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `FK_E373747040C86FCE` FOREIGN KEY (`publisher_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_E373747061220EA6` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `campaign_advertisements`
--
ALTER TABLE `campaign_advertisements`
  ADD CONSTRAINT `FK_EC64FB916DB58F3E` FOREIGN KEY (`advertisements_id`) REFERENCES `advertisements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EC64FB91F639F774` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
