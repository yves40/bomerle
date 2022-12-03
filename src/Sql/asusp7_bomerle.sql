-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 03 déc. 2022 à 08:48
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bomerledb`
--

-- --------------------------------------------------------

--
-- Structure de la table `accessories`
--

DROP TABLE IF EXISTS `accessories`;
CREATE TABLE IF NOT EXISTS `accessories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `accessories`
--

INSERT INTO `accessories` (`id`, `name`) VALUES
(2, 'Tire bouchon'),
(3, 'Pointeau');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Cuisine'),
(2, 'Chasse'),
(3, 'Table'),
(4, 'Collection spéciale Noel 2022'),
(6, 'Collection Blitz Day 2022');

-- --------------------------------------------------------

--
-- Structure de la table `dblog`
--

DROP TABLE IF EXISTS `dblog`;
CREATE TABLE IF NOT EXISTS `dblog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `severity` int(11) NOT NULL,
  `logtime` datetime NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `utctime` datetime NOT NULL,
  `action` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `name`, `address`, `date`) VALUES
(1, 'Pontault Knife World', '222 Ave CH.ROUXEL', '2022-11-26');

-- --------------------------------------------------------

--
-- Structure de la table `handle`
--

DROP TABLE IF EXISTS `handle`;
CREATE TABLE IF NOT EXISTS `handle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `handle`
--

INSERT INTO `handle` (`id`, `name`) VALUES
(1, 'Corne de belzébuth'),
(2, 'Ivoire');

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mainpicture` tinyint(1) NOT NULL,
  `knifes_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E01FBE6A8C85B742` (`knifes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `filename`, `mainpicture`, `knifes_id`) VALUES
(2, '20220812-141405-6388df4521b9b.jpg', 1, 2),
(3, '20220812-141412-6388df4522764.jpg', 0, 2),
(4, '20220812-141421-6388df45230e9.jpg', 0, 2);

-- --------------------------------------------------------

--
-- Structure de la table `knifes`
--

DROP TABLE IF EXISTS `knifes`;
CREATE TABLE IF NOT EXISTS `knifes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `lenght` int(11) NOT NULL,
  `close_lenght` int(11) DEFAULT NULL,
  `cuttingedge_lenght` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `mechanism_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2E01276912469DE2` (`category_id`),
  KEY `IDX_2E01276937CD6DD0` (`mechanism_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `knifes`
--

INSERT INTO `knifes` (`id`, `category_id`, `name`, `description`, `stock`, `weight`, `lenght`, `close_lenght`, `cuttingedge_lenght`, `price`, `mechanism_id`) VALUES
(2, 2, 'RAMBO2', 'Le couteau mythique de John', 1, 550, 500, NULL, 260, '800.00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `knifes_accessories`
--

DROP TABLE IF EXISTS `knifes_accessories`;
CREATE TABLE IF NOT EXISTS `knifes_accessories` (
  `knifes_id` int(11) NOT NULL,
  `accessories_id` int(11) NOT NULL,
  PRIMARY KEY (`knifes_id`,`accessories_id`),
  KEY `IDX_C01A6A7C8C85B742` (`knifes_id`),
  KEY `IDX_C01A6A7C35D022EB` (`accessories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `knifes_handle`
--

DROP TABLE IF EXISTS `knifes_handle`;
CREATE TABLE IF NOT EXISTS `knifes_handle` (
  `knifes_id` int(11) NOT NULL,
  `handle_id` int(11) NOT NULL,
  PRIMARY KEY (`knifes_id`,`handle_id`),
  KEY `IDX_11B90A5F8C85B742` (`knifes_id`),
  KEY `IDX_11B90A5F9C256C9C` (`handle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `knifes_handle`
--

INSERT INTO `knifes_handle` (`knifes_id`, `handle_id`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `knifes_metals`
--

DROP TABLE IF EXISTS `knifes_metals`;
CREATE TABLE IF NOT EXISTS `knifes_metals` (
  `knifes_id` int(11) NOT NULL,
  `metals_id` int(11) NOT NULL,
  PRIMARY KEY (`knifes_id`,`metals_id`),
  KEY `IDX_794FFD6D8C85B742` (`knifes_id`),
  KEY `IDX_794FFD6DB4E97D59` (`metals_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `knifes_metals`
--

INSERT INTO `knifes_metals` (`knifes_id`, `metals_id`) VALUES
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `mechanism`
--

DROP TABLE IF EXISTS `mechanism`;
CREATE TABLE IF NOT EXISTS `mechanism` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mechanism`
--

INSERT INTO `mechanism` (`id`, `name`) VALUES
(1, 'Fixe'),
(2, 'Pliant');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `metals`
--

DROP TABLE IF EXISTS `metals`;
CREATE TABLE IF NOT EXISTS `metals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `metals`
--

INSERT INTO `metals` (`id`, `name`) VALUES
(1, 'Zyrconium'),
(2, 'Acier'),
(3, 'Titane'),
(4, 'Uranium');

-- --------------------------------------------------------

--
-- Structure de la table `newsletter`
--

DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE IF NOT EXISTS `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forknife` tinyint(1) NOT NULL,
  `forevents` tinyint(1) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `newsletter`
--

INSERT INTO `newsletter` (`id`, `forknife`, `forevents`, `email`) VALUES
(25, 1, 1, 'yves77340@gmail.com'),
(26, 1, 1, 'toto@free.fr');

-- --------------------------------------------------------

--
-- Structure de la table `requests_tracker`
--

DROP TABLE IF EXISTS `requests_tracker`;
CREATE TABLE IF NOT EXISTS `requests_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `requestactiontype` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `selector` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires` int(11) NOT NULL,
  `status` smallint(6) NOT NULL,
  `created` datetime NOT NULL,
  `processed` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `requests_tracker`
--

INSERT INTO `requests_tracker` (`id`, `requestactiontype`, `email`, `selector`, `token`, `expires`, `status`, `created`, `processed`) VALUES
(2, 'Register', 'zeb@free.fr', '128a2ec576e9fcb4', '1aa4b61ec4bb2db135e8c2bdd78a20546553829fe85bb8fa2d410d6ae0e85798', 1670058970, 0, '2022-12-03 09:46:10', '2022-12-03 09:46:10');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `role` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `confirmpassword` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `confirmed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `address`, `password`, `created`, `role`, `lastlogin`, `confirmpassword`, `confirmed`) VALUES
(4, 'Yves', 'Toubhans', 'yves77340@gmail.com', '21 rue des moines épicuriens, 77200 Pontolt', '$2y$13$huLlIjVc6ZjiqROrNx2xU.2qryrMZ8J1qXjrX5IRFBRERWNYS0zp6', '2022-11-20 18:21:00', '[]', NULL, '$2y$13$huLlIjVc6ZjiqROrNx2xU.2qryrMZ8J1qXjrX5IRFBRERWNYS0zp6', NULL),
(6, 'Yves', 'Toubhans', 'yves.toubhans@free.fr', '21 rue des moines épicuriens, 77200 Pontolt', '$2y$13$t.EeL6nfcRpIOL9VYEkSsOQpAhfywHWPbQxPl2vjVzPQHfUByYVpW', '2022-11-21 12:51:00', '[]', NULL, '$2y$13$t.EeL6nfcRpIOL9VYEkSsOQpAhfywHWPbQxPl2vjVzPQHfUByYVpW', NULL),
(7, 'Benjamin', 'Toubhans', 'benjamin.toubhans@orange.fr', '21 rue des moines épicuriens, 77200 Pontolt', '$2y$13$ouR5YS1dz1HMPGGUerdjkuHYvUuhllDF/6kcty9VXMwOOMpDhnUoa', '2022-11-21 19:03:31', '[]', NULL, '$2y$13$ouR5YS1dz1HMPGGUerdjkuHYvUuhllDF/6kcty9VXMwOOMpDhnUoa', NULL),
(8, 'zebulon', 'tournesol', 'zeb@free.fr', '21 rue des bugs', '$2y$13$h3RYgdI8vJqMOC6zphK0yOy10XQrQAlbI.kswLyJI.cE1KPBX61X6', '2022-12-03 09:46:09', '[]', NULL, '$2y$13$h3RYgdI8vJqMOC6zphK0yOy10XQrQAlbI.kswLyJI.cE1KPBX61X6', NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `FK_E01FBE6A8C85B742` FOREIGN KEY (`knifes_id`) REFERENCES `knifes` (`id`);

--
-- Contraintes pour la table `knifes`
--
ALTER TABLE `knifes`
  ADD CONSTRAINT `FK_2E01276912469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_2E01276937CD6DD0` FOREIGN KEY (`mechanism_id`) REFERENCES `mechanism` (`id`);

--
-- Contraintes pour la table `knifes_accessories`
--
ALTER TABLE `knifes_accessories`
  ADD CONSTRAINT `FK_C01A6A7C35D022EB` FOREIGN KEY (`accessories_id`) REFERENCES `accessories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_C01A6A7C8C85B742` FOREIGN KEY (`knifes_id`) REFERENCES `knifes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `knifes_handle`
--
ALTER TABLE `knifes_handle`
  ADD CONSTRAINT `FK_11B90A5F8C85B742` FOREIGN KEY (`knifes_id`) REFERENCES `knifes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_11B90A5F9C256C9C` FOREIGN KEY (`handle_id`) REFERENCES `handle` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `knifes_metals`
--
ALTER TABLE `knifes_metals`
  ADD CONSTRAINT `FK_794FFD6D8C85B742` FOREIGN KEY (`knifes_id`) REFERENCES `knifes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_794FFD6DB4E97D59` FOREIGN KEY (`metals_id`) REFERENCES `metals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
