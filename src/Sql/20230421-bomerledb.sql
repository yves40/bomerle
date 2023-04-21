-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 21 avr. 2023 à 14:15
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
-- Base de données : bomerledb
--
CREATE DATABASE IF NOT EXISTS bomerledb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE bomerledb;

-- --------------------------------------------------------

--
-- Structure de la table accessories
--

DROP TABLE IF EXISTS accessories;
CREATE TABLE IF NOT EXISTS accessories (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer accessories
--

TRUNCATE TABLE accessories;
--
-- Déchargement des données de la table accessories
--

INSERT INTO accessories (id, name) VALUES
(12, 'Tire bouchon'),
(13, 'Lime');

-- --------------------------------------------------------

--
-- Structure de la table category
--

DROP TABLE IF EXISTS category;
CREATE TABLE IF NOT EXISTS category (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer category
--

TRUNCATE TABLE category;
--
-- Déchargement des données de la table category
--

INSERT INTO category (id, name) VALUES
(14, 'Chasse'),
(15, 'Cuisine');

-- --------------------------------------------------------

--
-- Structure de la table contact
--

DROP TABLE IF EXISTS contact;
CREATE TABLE IF NOT EXISTS contact (
  id int(11) NOT NULL AUTO_INCREMENT,
  reservation_id int(11) DEFAULT NULL,
  email varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  object varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  text varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY UNIQ_4C62E638B83297E7 (reservation_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer contact
--

TRUNCATE TABLE contact;
-- --------------------------------------------------------

--
-- Structure de la table date_range
--

DROP TABLE IF EXISTS date_range;
CREATE TABLE IF NOT EXISTS date_range (
  id int(11) NOT NULL AUTO_INCREMENT,
  start_date date DEFAULT NULL,
  end_date date DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer date_range
--

TRUNCATE TABLE date_range;
-- --------------------------------------------------------

--
-- Structure de la table dblog
--

DROP TABLE IF EXISTS dblog;
CREATE TABLE IF NOT EXISTS dblog (
  id int(11) NOT NULL AUTO_INCREMENT,
  message varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  severity int(11) NOT NULL,
  logtime datetime NOT NULL,
  module varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  utctime datetime NOT NULL,
  action varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  useremail varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer dblog
--

TRUNCATE TABLE dblog;
--
-- Déchargement des données de la table dblog
--

INSERT INTO dblog (id, message, severity, logtime, module, utctime, action, useremail) VALUES
(9, 'User loggin success : yves77340@gmail.com', 1, '2023-03-07 11:43:48', 'GENERIC', '2023-03-07 10:43:48', 'GENERIC', NULL),
(10, 'yves77340@gmail.com', 1, '2023-03-08 17:31:29', 'LogoutHandler', '2023-03-08 16:31:29', 'Logout success', NULL),
(11, 'yves77340@gmail.com', 3, '2023-03-08 17:31:36', 'BootLoginFormAuthenticator', '2023-03-08 16:31:36', 'Login failure', NULL),
(12, 'yves77340@gmail.com', 1, '2023-03-08 17:31:40', 'BootLoginFormAuthenticator', '2023-03-08 16:31:40', 'Login success', NULL),
(13, 'yves77340@gmail.com', 1, '2023-03-09 18:57:52', 'LogoutHandler', '2023-03-09 17:57:52', 'Logout success', NULL),
(14, 'yves77340@gmail.com', 1, '2023-03-09 18:58:15', 'BootLoginFormAuthenticator', '2023-03-09 17:58:15', 'Login success', NULL),
(15, 'yves77340@gmail.com', 1, '2023-03-10 09:03:09', 'LogoutHandler', '2023-03-10 08:03:09', 'Logout success', ''),
(16, 'yves77340@gmail.com', 1, '2023-03-10 09:03:17', 'BootLoginFormAuthenticator', '2023-03-10 08:03:17', 'Login success', 'yves77340@gmail.com'),
(17, 'yves77340@gmail.com', 1, '2023-03-14 08:27:27', 'LogoutHandler', '2023-03-14 07:27:27', 'Logout success', ''),
(18, 'yves77340@gmail.com', 1, '2023-03-14 08:27:37', 'BootLoginFormAuthenticator', '2023-03-14 07:27:37', 'Login success', 'yves77340@gmail.com'),
(19, 'yves77340@gmail.com', 1, '2023-03-14 08:28:26', 'LogoutHandler', '2023-03-14 07:28:26', 'Logout success', ''),
(20, 'yves77340@gmail.com', 1, '2023-03-20 09:31:39', 'BootLoginFormAuthenticator', '2023-03-20 08:31:39', 'Login success', 'yves77340@gmail.com'),
(21, 'yves77340@gmail.com', 1, '2023-03-20 09:33:33', 'LogoutHandler', '2023-03-20 08:33:33', 'Logout success', ''),
(22, 'yves77340@gmail.com', 1, '2023-03-22 11:41:16', 'BootLoginFormAuthenticator', '2023-03-22 10:41:16', 'Login success', 'yves77340@gmail.com'),
(23, 'yves77340@gmail.com', 1, '2023-03-22 19:12:40', 'LogoutHandler', '2023-03-22 18:12:40', 'Logout success', 'yves77340@gmail.com'),
(24, 'yves77340@gmail.com', 1, '2023-03-22 19:12:46', 'BootLoginFormAuthenticator', '2023-03-22 18:12:46', 'Login success', 'yves77340@gmail.com'),
(25, 'TROYES updated', 1, '2023-03-22 19:13:04', 'AdminControllerEvents', '2023-03-22 18:13:04', 'Event updated', 'yves77340@gmail.com'),
(26, 'HOSSEGOR Casino created', 1, '2023-03-22 19:13:57', 'AdminControllerEvents', '2023-03-22 18:13:57', 'Event added', 'yves77340@gmail.com'),
(27, 'yves77340@gmail.com', 1, '2023-03-22 19:15:04', 'LogoutHandler', '2023-03-22 18:15:04', 'Logout success', 'yves77340@gmail.com'),
(28, 'yves77340@gmail.com', 1, '2023-03-23 19:13:45', 'BootLoginFormAuthenticator', '2023-03-23 18:13:45', 'Login success', 'yves77340@gmail.com'),
(29, 'yves77340@gmail.com', 1, '2023-03-27 09:41:40', 'BootLoginFormAuthenticator', '2023-03-27 07:41:40', 'Login success', 'yves77340@gmail.com'),
(30, 'yves77340@gmail.com', 1, '2023-04-01 19:58:52', 'BootLoginFormAuthenticator', '2023-04-01 17:58:52', 'Login success', 'yves77340@gmail.com'),
(31, 'promosummer created', 1, '2023-04-11 15:57:27', 'AdminControllerSlideShow', '2023-04-11 13:57:27', 'SlideShow CREATION', 'yves77340@gmail.com'),
(32, 'promosummer updated', 1, '2023-04-11 15:57:50', 'AdminControllerSlideShow', '2023-04-11 13:57:50', 'SlideShow UPDATE', 'yves77340@gmail.com'),
(33, 'promosummer updated', 1, '2023-04-13 17:40:02', 'AdminControllerSlideShow', '2023-04-13 15:40:02', 'SlideShow UPDATE', 'yves77340@gmail.com'),
(34, 'promosummer updated', 1, '2023-04-13 17:46:43', 'AdminControllerSlideShow', '2023-04-13 15:46:43', 'SlideShow UPDATE', 'yves77340@gmail.com'),
(35, 'home created', 1, '2023-04-15 19:34:55', 'AdminControllerSlideShow', '2023-04-15 17:34:55', 'SlideShow CREATION', 'yves77340@gmail.com'),
(36, 'home updated', 1, '2023-04-15 19:35:57', 'AdminControllerSlideShow', '2023-04-15 17:35:57', 'SlideShow UPDATE', 'yves77340@gmail.com'),
(37, 'home updated', 1, '2023-04-15 19:36:12', 'AdminControllerSlideShow', '2023-04-15 17:36:12', 'SlideShow UPDATE', 'yves77340@gmail.com'),
(38, 'home updated', 1, '2023-04-15 19:36:33', 'AdminControllerSlideShow', '2023-04-15 17:36:33', 'SlideShow UPDATE', 'yves77340@gmail.com'),
(39, 'home updated', 1, '2023-04-15 19:36:41', 'AdminControllerSlideShow', '2023-04-15 17:36:41', 'SlideShow UPDATE', 'yves77340@gmail.com'),
(40, 'home updated', 1, '2023-04-15 19:37:05', 'AdminControllerSlideShow', '2023-04-15 17:37:05', 'SlideShow UPDATE', 'yves77340@gmail.com'),
(41, 'yves77340@gmail.com', 1, '2023-04-18 18:15:35', 'BootLoginFormAuthenticator', '2023-04-18 16:15:35', 'Login success', 'yves77340@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table events
--

DROP TABLE IF EXISTS events;
CREATE TABLE IF NOT EXISTS `events` (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  address varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  date date NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer events
--

TRUNCATE TABLE events;
--
-- Déchargement des données de la table events
--

INSERT INTO events (id, name, address, date) VALUES
(5, 'TROYES Knife World Forum', 'TROYES JEANNIN', '2023-03-09'),
(6, 'Strasbourg', 'Strasbourg Hilton', '2023-03-04'),
(7, 'LYON', 'PART DIEU', '2023-05-22'),
(8, 'TROYES', '7 Allée des pins tranquilles, TROYES', '2023-08-22'),
(9, 'HOSSEGOR Casino', '32 Avenue des Mimosas, 40150 HOSSEGOR', '2023-09-22');

-- --------------------------------------------------------

--
-- Structure de la table handle
--

DROP TABLE IF EXISTS handle;
CREATE TABLE IF NOT EXISTS handle (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer handle
--

TRUNCATE TABLE handle;
--
-- Déchargement des données de la table handle
--

INSERT INTO handle (id, name) VALUES
(11, 'Bois de Belzébuth'),
(12, 'Corne de taureau landais');

-- --------------------------------------------------------

--
-- Structure de la table images
--

DROP TABLE IF EXISTS images;
CREATE TABLE IF NOT EXISTS images (
  id int(11) NOT NULL AUTO_INCREMENT,
  filename varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  mainpicture tinyint(1) NOT NULL,
  knifes_id int(11) NOT NULL,
  rank smallint(6) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY IDX_E01FBE6A8C85B742 (knifes_id)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer images
--

TRUNCATE TABLE images;
--
-- Déchargement des données de la table images
--

INSERT INTO images (id, filename, mainpicture, knifes_id, rank) VALUES
(34, '108-640a20e97588d.jpg', 1, 10, 1),
(35, '119-640a20f4530d8.jpg', 1, 10, 2),
(36, '106-640a20fb8f8d7.jpg', 1, 10, 3),
(37, '119-640adb09ed1c5.jpg', 1, 10, 4),
(38, 'anonymous-643ec9323f3b7.jpg', 1, 11, 3),
(39, 'IMG-20220505-094835-01-643ec9b76cf3c.jpg', 1, 11, 1),
(40, 'IMG-20220505-094841-01-01-643ec9b76db88.jpg', 0, 11, 2),
(41, 'IMG-20220505-094959-643ec9d3a8a35.jpg', 1, 11, 4),
(42, 'IMG-20220505-094959-643ec9ee300a3.jpg', 1, 11, 5);

-- --------------------------------------------------------

--
-- Structure de la table knifes
--

DROP TABLE IF EXISTS knifes;
CREATE TABLE IF NOT EXISTS knifes (
  id int(11) NOT NULL AUTO_INCREMENT,
  category_id int(11) NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  description longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  stock int(11) NOT NULL,
  weight int(11) NOT NULL,
  lenght int(11) NOT NULL,
  close_lenght int(11) DEFAULT NULL,
  cuttingedge_lenght int(11) NOT NULL,
  price decimal(10,2) DEFAULT NULL,
  mechanism_id int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY IDX_2E01276912469DE2 (category_id),
  KEY IDX_2E01276937CD6DD0 (mechanism_id)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer knifes
--

TRUNCATE TABLE knifes;
--
-- Déchargement des données de la table knifes
--

INSERT INTO knifes (id, category_id, name, description, stock, weight, lenght, close_lenght, cuttingedge_lenght, price, mechanism_id) VALUES
(10, 14, 'RAMBO 1', 'None', 2, 100, 100, 100, 100, '300.00', 13),
(11, 14, 'RAMBO 2', 'None', 1, 1, 1, 1, 1, '1000.00', 13);

-- --------------------------------------------------------

--
-- Structure de la table knifes_accessories
--

DROP TABLE IF EXISTS knifes_accessories;
CREATE TABLE IF NOT EXISTS knifes_accessories (
  knifes_id int(11) NOT NULL,
  accessories_id int(11) NOT NULL,
  PRIMARY KEY (knifes_id,accessories_id),
  KEY IDX_C01A6A7C8C85B742 (knifes_id),
  KEY IDX_C01A6A7C35D022EB (accessories_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer knifes_accessories
--

TRUNCATE TABLE knifes_accessories;
--
-- Déchargement des données de la table knifes_accessories
--

INSERT INTO knifes_accessories (knifes_id, accessories_id) VALUES
(10, 12),
(10, 13);

-- --------------------------------------------------------

--
-- Structure de la table knifes_handle
--

DROP TABLE IF EXISTS knifes_handle;
CREATE TABLE IF NOT EXISTS knifes_handle (
  knifes_id int(11) NOT NULL,
  handle_id int(11) NOT NULL,
  PRIMARY KEY (knifes_id,handle_id),
  KEY IDX_11B90A5F8C85B742 (knifes_id),
  KEY IDX_11B90A5F9C256C9C (handle_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer knifes_handle
--

TRUNCATE TABLE knifes_handle;
--
-- Déchargement des données de la table knifes_handle
--

INSERT INTO knifes_handle (knifes_id, handle_id) VALUES
(10, 11);

-- --------------------------------------------------------

--
-- Structure de la table knifes_metals
--

DROP TABLE IF EXISTS knifes_metals;
CREATE TABLE IF NOT EXISTS knifes_metals (
  knifes_id int(11) NOT NULL,
  metals_id int(11) NOT NULL,
  PRIMARY KEY (knifes_id,metals_id),
  KEY IDX_794FFD6D8C85B742 (knifes_id),
  KEY IDX_794FFD6DB4E97D59 (metals_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer knifes_metals
--

TRUNCATE TABLE knifes_metals;
--
-- Déchargement des données de la table knifes_metals
--

INSERT INTO knifes_metals (knifes_id, metals_id) VALUES
(10, 55);

-- --------------------------------------------------------

--
-- Structure de la table mechanism
--

DROP TABLE IF EXISTS mechanism;
CREATE TABLE IF NOT EXISTS mechanism (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer mechanism
--

TRUNCATE TABLE mechanism;
--
-- Déchargement des données de la table mechanism
--

INSERT INTO mechanism (id, name) VALUES
(13, 'Fixe'),
(14, 'Pliant');

-- --------------------------------------------------------

--
-- Structure de la table messenger_messages
--

DROP TABLE IF EXISTS messenger_messages;
CREATE TABLE IF NOT EXISTS messenger_messages (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  body longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  headers longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  queue_name varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at datetime NOT NULL,
  available_at datetime NOT NULL,
  delivered_at datetime DEFAULT NULL,
  PRIMARY KEY (id),
  KEY IDX_75EA56E0FB7336F0 (queue_name),
  KEY IDX_75EA56E0E3BD61CE (available_at),
  KEY IDX_75EA56E016BA31DB (delivered_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer messenger_messages
--

TRUNCATE TABLE messenger_messages;
-- --------------------------------------------------------

--
-- Structure de la table metals
--

DROP TABLE IF EXISTS metals;
CREATE TABLE IF NOT EXISTS metals (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer metals
--

TRUNCATE TABLE metals;
--
-- Déchargement des données de la table metals
--

INSERT INTO metals (id, name) VALUES
(55, 'Titane'),
(56, 'Polonium Potoche');

-- --------------------------------------------------------

--
-- Structure de la table newsletter
--

DROP TABLE IF EXISTS newsletter;
CREATE TABLE IF NOT EXISTS newsletter (
  id int(11) NOT NULL AUTO_INCREMENT,
  forknife tinyint(1) NOT NULL,
  forevents tinyint(1) NOT NULL,
  email varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer newsletter
--

TRUNCATE TABLE newsletter;
-- --------------------------------------------------------

--
-- Structure de la table requests_tracker
--

DROP TABLE IF EXISTS requests_tracker;
CREATE TABLE IF NOT EXISTS requests_tracker (
  id int(11) NOT NULL AUTO_INCREMENT,
  requestactiontype varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  email varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  selector longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  token longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  expires int(11) NOT NULL,
  status smallint(6) NOT NULL,
  created datetime NOT NULL,
  processed datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer requests_tracker
--

TRUNCATE TABLE requests_tracker;
-- --------------------------------------------------------

--
-- Structure de la table roles
--

DROP TABLE IF EXISTS roles;
CREATE TABLE IF NOT EXISTS roles (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  level int(11) NOT NULL,
  description varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer roles
--

TRUNCATE TABLE roles;
--
-- Déchargement des données de la table roles
--

INSERT INTO roles (id, name, level, description) VALUES
(1, 'ROLE_ADMINISTRATOR', 100, 'Agregates all admin roles, look at security.yaml'),
(2, 'ROLE_ADMIN', 90, 'Top level admin role, full privileges'),
(3, 'ROLE_ADMIN_SITE', 90, 'Top level admin role, full privileges'),
(4, 'ROLE_ADMIN_USERS', 80, 'Full privileges on user administration'),
(5, 'ROLE_ANONYMOUS', 10, 'Any user can access public features ');

-- --------------------------------------------------------

--
-- Structure de la table slide_images
--

DROP TABLE IF EXISTS slide_images;
CREATE TABLE IF NOT EXISTS slide_images (
  id int(11) NOT NULL AUTO_INCREMENT,
  slideshow_id int(11) NOT NULL,
  filename varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  rank smallint(6) NOT NULL,
  legend varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id),
  KEY IDX_42F946CE1D87D6D (slideshow_id)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer slide_images
--

TRUNCATE TABLE slide_images;
--
-- Déchargement des données de la table slide_images
--

INSERT INTO slide_images (id, slideshow_id, filename, rank, legend) VALUES
(1, 1, '101-6435674757fa5.jpg', 1, NULL),
(2, 1, '102-6435674758bba.jpg', 2, NULL),
(4, 1, '117-6435675e3e14f.jpg', 3, NULL),
(6, 1, '118-643822522bf1a.jpg', 4, NULL),
(8, 1, '105-643823e30aa8e.jpg', 5, NULL),
(9, 2, '106-643ae07d188a7.jpg', 1, NULL),
(10, 2, '120-643ae07d197da.jpg', 3, NULL),
(11, 2, '112-643ae0a10eea6.jpg', 2, NULL),
(12, 2, '120-643ae0c1656a3.jpg', 4, NULL);

-- --------------------------------------------------------

--
-- Structure de la table slide_show
--

DROP TABLE IF EXISTS slide_show;
CREATE TABLE IF NOT EXISTS slide_show (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  active tinyint(1) NOT NULL,
  datein date DEFAULT NULL,
  dateout date DEFAULT NULL,
  description varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  daterange tinyint(1) NOT NULL,
  slider tinyint(1) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer slide_show
--

TRUNCATE TABLE slide_show;
--
-- Déchargement des données de la table slide_show
--

INSERT INTO slide_show (id, name, active, datein, dateout, description, daterange, slider) VALUES
(1, 'promosummer', 0, '2023-08-01', '2023-12-31', 'None', 0, 1),
(2, 'home', 0, '2023-09-01', '2023-12-31', 'RAS', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table users
--

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
  id int(11) NOT NULL AUTO_INCREMENT,
  firstname varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  lastname varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  email varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  address varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  password varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  created datetime NOT NULL,
  lastlogin datetime DEFAULT NULL,
  confirmpassword varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  confirmed datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer users
--

TRUNCATE TABLE users;
--
-- Déchargement des données de la table users
--

INSERT INTO users (id, firstname, lastname, email, address, password, created, lastlogin, confirmpassword, confirmed) VALUES
(19, 'Yves', 'Toubhans', 'yves77340@gmail.com', 'La Lune 3ème étage', '$2y$13$E0p/xi4grnbmS8t9WACg1eP5nMePo/q5n8UuKePh6iTilVRqnEOfy', '2023-03-07 11:43:17', NULL, '$2y$13$F4Bpvhstg9EcyOr0m2s6OOSpBbiIEkC4gNvoylhMqBkhGg5D0na6O', '2023-03-07 11:43:17'),
(20, 'Benjamin', 'Toubhans', 'benjamin.toubhans@orange.fr', 'Mars with Elon', '$2y$13$wl0vyi0E6J5SWlUaJzVS0O7KfFFJoOXbyjaofZvuh3Z3r/UEIHpTi', '2023-03-07 11:43:19', NULL, '$2y$13$QZvjTBMjd7frxcq1OyleBuNSBkPeq1EznZOLqbEdWG06LVB6JE8ke', '2023-03-07 11:43:19'),
(21, 'Isabelle', 'Toubhans', 'i.toubhans@free.fr', 'Pluton with Jeff', '$2y$13$1vbAgC1c00f91UeSdRs4PuXSoKNPiIs/bP8vb/LJcEorb79Fx1xwG', '2023-03-07 11:43:20', NULL, '$2y$13$S323PW4JiCqdpiS0sF38iuUJ3WCGg71ebZzlTUPd3yVdMtenN4ni2', '2023-03-07 11:43:20'),
(27, 'Bastoche', 'TT', 'bastien.toubhans@free.fr', 'Chabreloche 63878', '$2y$13$PLaEefgQFkIjqQzkr44c1epAiBAMNTbavy5qisZzH.anP9EwgxBL6', '2023-04-13 17:51:21', NULL, '$2y$13$PLaEefgQFkIjqQzkr44c1epAiBAMNTbavy5qisZzH.anP9EwgxBL6', '2023-04-13 17:51:21');

-- --------------------------------------------------------

--
-- Structure de la table users_roles
--

DROP TABLE IF EXISTS users_roles;
CREATE TABLE IF NOT EXISTS users_roles (
  users_id int(11) NOT NULL,
  roles_id int(11) NOT NULL,
  PRIMARY KEY (users_id,roles_id),
  KEY IDX_51498A8E67B3B43D (users_id),
  KEY IDX_51498A8E38C751C4 (roles_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tronquer la table avant d'insérer users_roles
--

TRUNCATE TABLE users_roles;
--
-- Déchargement des données de la table users_roles
--

INSERT INTO users_roles (users_id, roles_id) VALUES
(19, 1),
(19, 5),
(20, 4),
(20, 5),
(21, 3),
(21, 5),
(27, 3),
(27, 5);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table contact
--
ALTER TABLE contact
  ADD CONSTRAINT FK_4C62E638B83297E7 FOREIGN KEY (reservation_id) REFERENCES knifes (id);

--
-- Contraintes pour la table images
--
ALTER TABLE images
  ADD CONSTRAINT FK_E01FBE6A8C85B742 FOREIGN KEY (knifes_id) REFERENCES knifes (id);

--
-- Contraintes pour la table knifes
--
ALTER TABLE knifes
  ADD CONSTRAINT FK_2E01276912469DE2 FOREIGN KEY (category_id) REFERENCES category (id),
  ADD CONSTRAINT FK_2E01276937CD6DD0 FOREIGN KEY (mechanism_id) REFERENCES mechanism (id);

--
-- Contraintes pour la table knifes_accessories
--
ALTER TABLE knifes_accessories
  ADD CONSTRAINT FK_C01A6A7C35D022EB FOREIGN KEY (accessories_id) REFERENCES accessories (id) ON DELETE CASCADE,
  ADD CONSTRAINT FK_C01A6A7C8C85B742 FOREIGN KEY (knifes_id) REFERENCES knifes (id) ON DELETE CASCADE;

--
-- Contraintes pour la table knifes_handle
--
ALTER TABLE knifes_handle
  ADD CONSTRAINT FK_11B90A5F8C85B742 FOREIGN KEY (knifes_id) REFERENCES knifes (id) ON DELETE CASCADE,
  ADD CONSTRAINT FK_11B90A5F9C256C9C FOREIGN KEY (handle_id) REFERENCES handle (id) ON DELETE CASCADE;

--
-- Contraintes pour la table knifes_metals
--
ALTER TABLE knifes_metals
  ADD CONSTRAINT FK_794FFD6D8C85B742 FOREIGN KEY (knifes_id) REFERENCES knifes (id) ON DELETE CASCADE,
  ADD CONSTRAINT FK_794FFD6DB4E97D59 FOREIGN KEY (metals_id) REFERENCES metals (id) ON DELETE CASCADE;

--
-- Contraintes pour la table slide_images
--
ALTER TABLE slide_images
  ADD CONSTRAINT FK_42F946CE1D87D6D FOREIGN KEY (slideshow_id) REFERENCES slide_show (id);

--
-- Contraintes pour la table users_roles
--
ALTER TABLE users_roles
  ADD CONSTRAINT FK_51498A8E38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE,
  ADD CONSTRAINT FK_51498A8E67B3B43D FOREIGN KEY (users_id) REFERENCES `users` (id) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
