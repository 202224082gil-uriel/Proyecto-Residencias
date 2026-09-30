-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-09-2026 a las 07:48:41
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
-- Base de datos: `bd_residencias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `tipo_documento` varchar(100) NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id`, `nombre_archivo`, `ruta_archivo`, `tipo_documento`, `fecha_subida`) VALUES
(1, 'Solicitud de Residencia Profesional 2025', 'C:\\xamppwitMysql\\htdocs\\proyresid\\uploads', 'Solicitud', '2026-09-26 05:37:54'),
(2, 'Asignación de Asesor Residencia 2025', 'C:\\xamppwitMysql\\htdocs\\proyresid\\uploads\r\n', 'Asignación', '2026-09-26 05:37:54'),
(3, 'Acta de Calificación Final Residencia', 'C:\\xamppwitMysql\\htdocs\\proyresid\\uploads', 'Carta Liberación', '2026-09-26 05:37:54'),
(4, 'Formato Evaluación y Seguimiento Anexo XXIX', 'uploads/SGI_TecNM_A_XXIX_FORM_EVALU_Y_SEGUIMIENTO_RES_PRO.docx', 'Anexo XXIX', '2026-09-26 05:37:54'),
(5, 'Formato Reporte Residencias Anexo XXX', 'C:\\xamppwitMysql\\htdocs\\proyresid\\uploads', 'Anexo XXX', '2026-09-26 05:37:54'),
(6, 'Estructura Reporte Preliminar Anexo XXVII', 'C:\\xamppwitMysql\\htdocs\\proyresid\\uploads', 'Anteproyecto', '2026-09-26 05:37:54'),
(7, 'Estructura Reporte Final Anexo XXVIII', 'C:\\xamppwitMysql\\htdocs\\proyresid\\uploads', 'Reporte Final', '2026-09-26 05:37:54'),
(8, 'Lineamiento de Residencia Profesional', 'C:\\xamppwitMysql\\htdocs\\proyresid\\uploads', 'Lineamiento', '2026-09-26 05:37:54');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
