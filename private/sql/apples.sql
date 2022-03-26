-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         10.4.20-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura de base de datos para apples
CREATE DATABASE IF NOT EXISTS `apples` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `apples`;

-- Volcando estructura para tabla apples.comentarios
CREATE TABLE IF NOT EXISTS `comentarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuarios_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tabla` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tabla_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contenido` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contenido_formateado` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla apples.etiquetas
CREATE TABLE IF NOT EXISTS `etiquetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla apples.eventos
CREATE TABLE IF NOT EXISTS `eventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eventos_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizador` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsable` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `afiliacion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `participantes` smallint(11) DEFAULT 0,
  `etiquetas` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aceptado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla apples.eventos_usuarios
CREATE TABLE IF NOT EXISTS `eventos_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eventos_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuarios_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reserva` tinyint(1) DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla apples.mesas
CREATE TABLE IF NOT EXISTS `mesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eventos_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  ` borrado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla apples.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuarios_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etiquetas` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ubicacion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precio` float(111,2) DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla apples.publicaciones
CREATE TABLE IF NOT EXISTS `publicaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuarios_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titulo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contenido` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contenido_formateado` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publicado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla apples.turnos
CREATE TABLE IF NOT EXISTS `turnos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eventos_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `minutos` smallint(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla apples.turnos_usuarios
CREATE TABLE IF NOT EXISTS `turnos_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `turnos_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuarios_idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla apples.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apodo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellidos` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clave` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rol` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dentro_recinto` tinyint(1) DEFAULT 0,
  `puntuacion` smallint(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- La exportación de datos fue deseleccionada.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
