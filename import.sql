-- Adminer 4.8.1 MySQL 8.0.27 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `food`;
CREATE TABLE `food` (
  `id` int NOT NULL AUTO_INCREMENT,
  `food` varchar(255) COLLATE utf8_bin NOT NULL,
  `kcal` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `food_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin;

INSERT INTO `food` (`id`, `food`, `kcal`, `user_id`) VALUES
(61,	'Knedlík (celej)',	500,	26),
(62,	'Houska x200',	8000,	25),
(63,	'Hamburger',	200,	23),
(64,	'Sushi',	200,	23),
(65,	'Burgir',	120,	27);

DROP TABLE IF EXISTS `sport`;
CREATE TABLE `sport` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sport` varchar(255) COLLATE utf8_bin NOT NULL,
  `kcal` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `sport_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin;

INSERT INTO `sport` (`id`, `sport`, `kcal`, `user_id`) VALUES
(43,	'Klasika',	101,	22),
(47,	'Jedu Bomby',	5030,	25),
(49,	'Běh 2km',	100,	23),
(50,	'Běh',	120,	23);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sub` text CHARACTER SET utf8 COLLATE utf8_bin,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `given_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `family_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `role` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'user',
  `weight` int NOT NULL DEFAULT '0',
  `height` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_bin;

INSERT INTO `users` (`id`, `sub`, `username`, `given_name`, `family_name`, `picture`, `email`, `password`, `role`, `weight`, `height`) VALUES
(22,	NULL,	'test',	NULL,	NULL,	'picture/ground.png',	'test@test.cz',	'$2y$10$QA8grbdn4/AdsznkgKaRp.H94STjXbsO7vu6Ym6.irETXA01lFKHG',	'user',	93,	176),
(23,	'110468603765906169683',	'Jakub Vávrů',	'Jakub',	'Vávrů',	'picture/Jakub.png',	'oltaj.556@gmail.com',	'$2y$10$h1bKKe5NQFs0QtAV.yjtAuA0KdSGhAj1e5/6b9RZdxdjVsjNmVw4a',	'admin',	90,	178),
(25,	NULL,	'Karel N',	NULL,	NULL,	'picture/kajove.png',	'karel@karel.com',	'$2y$10$6D4G2E3X54JbQ9PUldQbD.FL6IuWqGnx//lRoRKPHqKkCaYxc8JjK',	'user',	107,	160),
(26,	NULL,	'Mihal',	NULL,	NULL,	'picture/Campercat3-Walter-White-in-Vietnam-War-c157a29e-673e-468b-9d0a-1655741702f1.png',	'michal@michal.xom',	'$2y$10$BmqEp67Ur9ncwgcUaxhTDe7ttX3QuIWSlng5wlaG.lgc/M.zqoeSC',	'user',	78,	162),
(27,	NULL,	'kosik',	NULL,	NULL,	NULL,	'pretaktovalsitrojliterdiesel@gmail.com',	'$2y$10$MwmkZ5YcflxQWFKuRTGAb.ye9RrIiUr5cXQfGholMjMC2uwWbLKXS',	'user',	78,	185);

-- 2023-05-04 17:30:19