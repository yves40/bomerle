-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 25 déc. 2023 à 17:35
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
-- Base de données : toba3789_DEVbomerle
--
CREATE DATABASE IF NOT EXISTS toba3789_DEVbomerle DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE toba3789_DEVbomerle;

-- --------------------------------------------------------

--
-- Structure de la table accessories
--

DROP TABLE IF EXISTS accessories;
CREATE TABLE IF NOT EXISTS accessories (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table category
--

DROP TABLE IF EXISTS category;
CREATE TABLE IF NOT EXISTS category (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  description varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  fullname varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table category_category
--

DROP TABLE IF EXISTS category_category;
CREATE TABLE IF NOT EXISTS category_category (
  category_source int(11) NOT NULL,
  category_target int(11) NOT NULL,
  PRIMARY KEY (category_source,category_target),
  KEY IDX_B1369DBA5062B508 (category_source),
  KEY IDX_B1369DBA4987E587 (category_target)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table contact
--

DROP TABLE IF EXISTS contact;
CREATE TABLE IF NOT EXISTS contact (
  id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  object varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  text varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  reservation_id int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY UNIQ_4C62E638B83297E7 (reservation_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table doctrine_migration_versions
--

DROP TABLE IF EXISTS doctrine_migration_versions;
CREATE TABLE IF NOT EXISTS doctrine_migration_versions (
  version varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  executed_at datetime DEFAULT NULL,
  execution_time int(11) DEFAULT NULL,
  PRIMARY KEY (version)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table events
--

DROP TABLE IF EXISTS events;
CREATE TABLE IF NOT EXISTS events (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  address varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  date date NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table handle
--

DROP TABLE IF EXISTS handle;
CREATE TABLE IF NOT EXISTS handle (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  stock int(11) DEFAULT NULL,
  weight int(11) DEFAULT NULL,
  lenght int(11) DEFAULT NULL,
  close_lenght int(11) DEFAULT NULL,
  cuttingedge_lenght int(11) DEFAULT NULL,
  price decimal(10,2) DEFAULT NULL,
  mechanism_id int(11) NOT NULL,
  published tinyint(1) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY IDX_2E01276912469DE2 (category_id),
  KEY IDX_2E01276937CD6DD0 (mechanism_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table mechanism
--

DROP TABLE IF EXISTS mechanism;
CREATE TABLE IF NOT EXISTS mechanism (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table metals
--

DROP TABLE IF EXISTS metals;
CREATE TABLE IF NOT EXISTS metals (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  KEY IDX_42F946CE8B14E343 (slideshow_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  description varchar(8192) COLLATE utf8mb4_unicode_ci NOT NULL,
  daterange tinyint(1) NOT NULL,
  slider tinyint(1) DEFAULT NULL,
  monday tinyint(1) DEFAULT NULL,
  tuesday tinyint(1) DEFAULT NULL,
  wednesday tinyint(1) DEFAULT NULL,
  thursday tinyint(1) DEFAULT NULL,
  friday tinyint(1) DEFAULT NULL,
  saturday tinyint(1) DEFAULT NULL,
  sunday tinyint(1) DEFAULT NULL,
  gallery tinyint(1) DEFAULT NULL,
  timing smallint(6) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table category_category
--
ALTER TABLE category_category
  ADD CONSTRAINT FK_B1369DBA4987E587 FOREIGN KEY (category_target) REFERENCES category (id) ON DELETE CASCADE,
  ADD CONSTRAINT FK_B1369DBA5062B508 FOREIGN KEY (category_source) REFERENCES category (id) ON DELETE CASCADE;

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
  ADD CONSTRAINT FK_51498A8E67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
