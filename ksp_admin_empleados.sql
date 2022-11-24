-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.1.31-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para ksp_admin_empleados
CREATE DATABASE IF NOT EXISTS `ksp_admin_empleados` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ksp_admin_empleados`;

-- Volcando estructura para tabla ksp_admin_empleados.beneficiarios
CREATE TABLE IF NOT EXISTS `beneficiarios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_empleado` int(10) unsigned NOT NULL COMMENT 'relacion empleados.id',
  `nombre` varchar(200) NOT NULL,
  `parentesco` varchar(200) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` enum('M','F') NOT NULL COMMENT 'M=Masculino | F=Femenino',
  PRIMARY KEY (`id`,`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.
-- Volcando estructura para tabla ksp_admin_empleados.empleados
CREATE TABLE IF NOT EXISTS `empleados` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `puesto` varchar(200) NOT NULL,
  `salario` decimal(10,2) NOT NULL,
  `estatus` bit(1) NOT NULL DEFAULT b'1' COMMENT '1=Activo | 0=Inactivo',
  `fecha_contratacion` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.
-- Volcando estructura para procedimiento ksp_admin_empleados.sp_beneficiarios_eliminar
DELIMITER //
CREATE PROCEDURE `sp_beneficiarios_eliminar`(
	IN `_id` INT(11)

)
SALIR:BEGIN
	IF NOT EXISTS(SELECT id FROM beneficiarios WHERE id=_id) THEN
		SELECT 'No encontró' response, 'Registro no existente.' msg, 404 status_code;
		LEAVE SALIR;
	END IF;
	
	DELETE FROM beneficiarios WHERE id=_id;
	IF ROW_COUNT() > 0 THEN
		SELECT 'Eliminado' response, 'Registro eliminado correctamente.' msg;	
	ELSE
		SELECT 'Error de procedimiento' response, 'Ocurrio un error al eliminar, favor de intentar más tarde.' msg, 500 status_code;
	END IF; 
END//
DELIMITER ;

-- Volcando estructura para procedimiento ksp_admin_empleados.sp_beneficiarios_guardar
DELIMITER //
CREATE PROCEDURE `sp_beneficiarios_guardar`(
	IN `_id` INT(11),
	IN `_id_empleado` INT(10),
	IN `_nombre` VARCHAR(200),
	IN `_parentesco` VARCHAR(200),
	IN `_fecha_nacimiento` DATE,
	IN `_sexo` ENUM('M','F')


)
SALIR:BEGIN
	IF NOT EXISTS(SELECT id FROM beneficiarios WHERE id=_id) THEN
		-- INSERTAR
		IF EXISTS(SELECT id FROM beneficiarios WHERE nombre=_nombre) THEN
			SELECT 'Existente' response, 'Registro existente, favor de capturar uno diferente.' msg, 202 status_code;
			LEAVE SALIR;
		END IF;
		
		INSERT INTO beneficiarios SET id_empleado=_id_empleado, nombre=_nombre, parentesco=_parentesco, fecha_nacimiento=_fecha_nacimiento, sexo=_sexo;
		IF ROW_COUNT() > 0 THEN
			SELECT 'Agregado' response, 'Registro agregado correctamente.' msg, LAST_INSERT_ID() id;	
		ELSE
			SELECT 'Error de procedimiento' response, 'Ocurrio un error al agregar, favor de intentar más tarde.' msg, 500 status_code;
		END IF; 
	ELSE
		-- MODIFICAR
		IF EXISTS(SELECT id FROM beneficiarios WHERE nombre=_nombre and id<>_id) THEN
			SELECT 'Existente' response, 'Registro existente, favor de capturar un nombre diferente.' msg, 202 status_code;
			LEAVE SALIR;
		END IF;
		
		UPDATE beneficiarios SET id_empleado=_id_empleado, nombre=_nombre, parentesco=_parentesco, fecha_nacimiento=_fecha_nacimiento, sexo=_sexo WHERE id=_id;
		IF ROW_COUNT() > 0 THEN
			SELECT 'Modificado' response, 'Registro modificado correctamente.' msg, _id AS id;	
		ELSE
			SELECT 'No modificado' response, 'No se detecto cambio en los datos para realizar la modificación.' msg, 202 status_code;
		END IF; 
	END IF;
	
END//
DELIMITER ;

-- Volcando estructura para procedimiento ksp_admin_empleados.sp_empleados_eliminar
DELIMITER //
CREATE PROCEDURE `sp_empleados_eliminar`(
	IN `_id` INT(10)
)
SALIR:BEGIN
	IF NOT EXISTS(SELECT id FROM empleados WHERE id=_id) THEN
		SELECT 'No encontró' response, 'Registro no existente.' msg, 404 status_code;
		LEAVE SALIR;
	END IF;
	
	DELETE FROM empleados WHERE id=_id;
	IF ROW_COUNT() > 0 THEN
		SELECT 'Eliminado' response, 'Registro eliminado correctamente.' msg;	
	ELSE
		SELECT 'Error de procedimiento' response, 'Ocurrio un error al eliminar, favor de intentar más tarde.' msg, 500 status_code;
	END IF; 
END//
DELIMITER ;

-- Volcando estructura para procedimiento ksp_admin_empleados.sp_empleados_guardar
DELIMITER //
CREATE PROCEDURE `sp_empleados_guardar`(
	IN `_id` INT(10),
	IN `_nombre` VARCHAR(200),
	IN `_puesto` VARCHAR(200),
	IN `_salario` DECIMAL(10,2),
	IN `_estatus` BIT,
	IN `_fecha_contratacion` DATE






)
SALIR:BEGIN
	IF NOT EXISTS(SELECT id FROM empleados WHERE id=_id) THEN
		-- INSERTAR
		IF EXISTS(SELECT id FROM empleados WHERE nombre=_nombre AND puesto=_puesto) THEN
			SELECT 'Existente' response, 'Registro existente, favor de capturar uno diferente.' msg, 202 status_code;
			LEAVE SALIR;
		END IF;
		
		INSERT INTO empleados SET nombre=_nombre, puesto=_puesto, salario=_salario, estatus=_estatus, fecha_contratacion=_fecha_contratacion;
		IF ROW_COUNT() > 0 THEN
			SELECT 'Agregado' response, 'Registro agregado correctamente.' msg, LAST_INSERT_ID() id;	
		ELSE
			SELECT 'Error de procedimiento' response, 'Ocurrio un error al agregar, favor de intentar más tarde.' msg, 500 status_code;
		END IF; 
	ELSE
		-- MODIFICAR
		IF EXISTS(SELECT id FROM empleados WHERE nombre=_nombre and id<>_id) THEN
			SELECT 'Existente' response, 'Registro existente, favor de capturar un nombre diferente.' msg, 202 status_code;
			LEAVE SALIR;
		END IF;
		
		UPDATE empleados SET nombre=_nombre, puesto=_puesto, salario=_salario, estatus=_estatus, fecha_contratacion=_fecha_contratacion WHERE id=_id;
		IF ROW_COUNT() > 0 THEN
			SELECT 'Modificado' response, 'Registro modificado correctamente.' msg, _id AS id;	
		ELSE
			SELECT 'No modificado' response, 'No se detecto cambio en los datos para realizar la modificación.' msg, 202 status_code;
		END IF; 
	END IF;
	
END//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;