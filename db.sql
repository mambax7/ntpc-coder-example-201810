-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `default` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `default`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ntpc` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `ntpc`) VALUES
(1,	'admin',	'管理員',	'admin@local.dev',	'$2y$10$2VIRZNZMBwnNcOYfp0gMs.YA9u7KirfgNUELJKbsgXPV/UtNKgRKW',	'0');

-- 2018-10-23 10:12:40
