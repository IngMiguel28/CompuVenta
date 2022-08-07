-- Base de datos: MySQL 5.7.24
-- Versión de PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Creacion de Base de datos
--

CREATE DATABASE IF NOT EXISTS `tienda` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Compshop`;

--
-- Creacion de tabla para productos
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `stock` tinyint(250) NOT NULL,
  `imagen` varchar(100) NOT NULL
) ENGINE=MySQL DEFAULT CHARSET=utf8mb4;

--
-- Creacion de tabla Calsificacion
--

CREATE TABLE `clasificacion` (
  `id_grupo` int(11) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `nombre_grupo` varchar(255) NOT NULL,
  `clasehija` varchar(255) NOT NULL
) ENGINE=MySQL DEFAULT CHARSET=utf8mb4;

--
-- Creacion de tabla Comentarios
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `comentario` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `calificacion` tinyint(5) NOT NULL
) ENGINE=MySQL DEFAULT CHARSET=utf8mb4;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;