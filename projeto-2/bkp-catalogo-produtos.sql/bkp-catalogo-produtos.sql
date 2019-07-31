# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.31)
# Database: formacao_php
# Generation Time: 2018-02-03 11:08:44 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table products
# ------------------------------------------------------------
# 
CREATE DATABASE formacao_php COLLATE utf8_unicode_ci;

USE formacao_php;

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;

INSERT INTO `products` (`id`, `category_id`, `name`, `price`, `amount`, `description`, `slug`, `created_at`, `updated_at`)
VALUES
	(1,2,'Curso de PHP Edited 23',19.90,10,'Curso online dos primeiros passos com PHP','curso-de-php','2017-04-14 14:45:26','2017-04-14 15:56:56'),
	(8,2,'Curso PHP OO',49.90,10,'Aprenda todos os conceitos de orientação a objetos no PHP de forma prática e dinâmica.','curso-php-oo-1','2017-06-23 19:11:14','2017-06-24 14:04:31'),
	(9,2,'Curso PHP Iniciante',99.90,10,'Aprenda todos os conceitos de orientação a objetos no PHP de forma prática e dinâmica.','curso-php-oo-1','2017-06-23 19:11:14','2017-06-24 14:04:31'),
	(10,2,'Curso PHP Design Patterns',99.90,10,'Aprenda todos os conceitos de orientação a objetos no PHP de forma prática e dinâmica.','curso-php-oo-1','2017-06-23 19:11:14','2017-06-24 14:04:31'),
	(11,2,'Curso PHP Teste',99.90,10,'Aprenda todos os conceitos de orientação a objetos no PHP de forma prática e dinâmica.','curso-php-oo-1','2017-06-23 19:11:14','2017-06-24 14:04:31');

/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
