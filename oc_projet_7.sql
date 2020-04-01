-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 01 avr. 2020 à 10:51
-- Version du serveur :  8.0.18
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `oc_projet_7`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `email` varchar(255) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL,
  `Date_register` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(255) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL,
  `roles` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf32;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`ID`, `Name`, `email`, `Date_register`, `password`, `roles`) VALUES
(1, 'PhoneWall.fr', 'test2@test.fr', '2020-03-29 14:31:37', '$2y$13$o1x6atWYWJHI9zb4MQ4Bg.QiP2AH\\/Vw4ur5VwBAHhxdTrhPgHyJIi', '[\"ROLE_USER\"]'),
(2, 'PhoneAvenue.fr', 'test@test.fr', '2020-03-29 14:31:37', '$2y$13$O.aGXNgQculBMbpdH6rble0w0RwoD.IXfLkWDxZJJWTL/rbfZZiMS', '[\"ROLE_USER\"]'),
(3, 'PhoneGallery.fr', 'test3@test.fr', '2020-03-29 14:31:37', '$2y$13$F8aciGFM0.JTYJNtK2tWjef5md0XZwVlTDIjDK75A9I74WlEchrOm', '[\"ROLE_USER\"]'),
(5, 'SmartPhones.com', 'test4@test.fr', '2020-03-29 14:31:37', '$2y$13$1kkheECYPkheVAhrUyAageKkeM/oLdJ8Arvqtg1Va1xLsqOOZh4fm', '[\"ROLE_USER\"]');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL,
  `Brand` varchar(255) NOT NULL,
  `Model` varchar(255) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL,
  `Price` double NOT NULL,
  `Stock` int(11) NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf32;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`ID`, `Name`, `Brand`, `Model`, `Price`, `Stock`, `Description`) VALUES
(1, 'Iphone', 'Apple', 'X', 1099.99, 5, 'Écran Super Retina (OLED) de 5,8 pouces avec HDR\r\nIndice IP67 de résistance à la poussière et à l’eau (profondeur maximale de 1 mètre pendant 30 minutes maximum)\r\nDouble appareil photo 12 Mpx avec double stabilisation optique de l’image et caméra avant TrueDepth 7 Mpx — Mode Portrait et Éclairage de portrait\r\nFace ID pour l’authentification sécurisée et Apple Pay\r\nPuce A11 Bionic avec Neural Engine\r\nRecharge sans fil (avec chargeurs Qi)'),
(2, 'Galaxy', 'Samsung', 'S10', 572.5, 3, 'Smartphone portable débloqué 4G (Ecran : 6,1 pouces - Dual SIM - 128GO - Android - Autre Version Européenne'),
(3, 'Galaxy', 'Samsung', 'A10', 145.5, 10, 'Samsung Galaxy A10 A105 2Go/32Go Noir Double SIM'),
(4, 'P30', 'Huawei', 'P30 Lite', 216, 8, 'Smartphone débloqué 4G (6,15 pouces - 128Go - Double Nano SIM - Android 9.0) Peacock Blue [Version Française]'),
(5, 'Redmi', 'Xiaomi', 'Redmi Note 8', 154, 15, '4GB 64GB Blue'),
(6, 'Iphone', 'Apple', 'Iphone 11 Pro', 1159, 3, '(64 Go) - Vert Nuit'),
(7, '3310', 'Nokia', '3310', 10, 150, 'Téléphone portable débloqué 3G (Ecran 2,4 pouces, ROM 32Go, Double SIM Appareil photo 2MP) Rouge'),
(8, 'Liquid', 'Acer', 'Z6', 90, 15, 'Téléphone débloqué 4G (Ecran: 5 Pouces - 8 Go - Double Micro-SIM - Android 6 Marshmallow) Gris Foncé'),
(9, 'One', 'Motorola', 'Vision', 203.99, 20, '(6.3 Pouces, 4Go RAM, 128Go ROM, Double Nano SIM, Android 9.0) Bleu Saphir');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Firstname` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Date_register` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Number` int(50) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `Postal_code` int(50) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Tel` varchar(50) NOT NULL,
  `Client_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Client_ID` (`Client_ID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf32;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`ID`, `Name`, `Firstname`, `Email`, `Date_register`, `Number`, `Street`, `Postal_code`, `City`, `Tel`, `Client_ID`) VALUES
(1, 'Coupart', 'Pierrette', 'PierretteCoupart@dayrep.com', '0000-00-00 00:00:00', 44, 'rue de Lille', 59280, 'ARMENTIÈRES', '03.99.78.05.23', 2),
(2, 'Pouchard', 'Esperanza', 'EsperanzaPouchard@jourrapide.com', '0000-00-00 00:00:00', 66, 'rue des Chaligny', 6200, 'NICE', '04.26.69.01.25', 2),
(71, 'Beaudry', 'Émile', 'EmileBeaudry@dayrep.com', '2020-03-29 14:35:43', 59, 'rue du Gue Jacquet', 92320, 'CHÂTILLON', '01.32.41.81.54', 2),
(72, 'Melanson', 'Baptiste', 'BaptisteMelanson@jourrapide.com', '2020-03-29 14:35:43', 2, 'Rue Marie De Médicis', 69300, 'CALUIRE-ET-CUIRE', '04.38.74.61.45', 2),
(73, 'Blanchard', 'Maurelle', 'MaurelleBlanchard@armyspy.com', '2020-03-29 14:37:05', 59, 'rue Goya', 77350, 'LE MÉE-SUR-SEINE', '01.22.93.01.91', 2),
(75, 'Duffet', 'Nathalie', 'NathalieDuffet@armyspy.com', '2020-03-29 14:38:36', 14, 'Rue Frédéric Chopin', 27200, 'VERNON', '02.91.59.35.61', 2),
(76, 'Mercure', 'Brigitte', 'BrigitteMercure@dayrep.com', '2020-03-29 14:38:36', 26, 'rue Lenotre', 35200, 'RENNES', '02.99.67.01.99', 2),
(77, 'Caisse', 'Isaac', 'IsaacCaisse@armyspy.com', '2020-03-29 14:39:24', 91, 'Cours Marechal-Joffre', 69150, 'DÉCINES-CHARPIEU', '04.59.46.78.09', 2),
(78, 'test', 'test', 'test@est.fr', '2020-03-29 14:40:14', 55, 'test', 55555, 'test', '0123456789', 5);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `Client_ID` FOREIGN KEY (`Client_ID`) REFERENCES `clients` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
