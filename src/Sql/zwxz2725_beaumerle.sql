-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 28 nov. 2022 à 20:14
-- Version du serveur :  10.5.18-MariaDB
-- Version de PHP : 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : zwxz2725_beaumerle
--
CREATE DATABASE IF NOT EXISTS zwxz2725_beaumerle DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE zwxz2725_beaumerle;

-- --------------------------------------------------------

--
-- Structure de la table accessories
--

DROP TABLE IF EXISTS accessories;
CREATE TABLE accessories (
  id int(11) NOT NULL,
  name varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table accessories
--

INSERT INTO accessories (id, `name`) VALUES
(2, 'Tire bouchon'),
(3, 'Pointeau');

-- --------------------------------------------------------

--
-- Structure de la table category
--

DROP TABLE IF EXISTS category;
CREATE TABLE category (
  id int(11) NOT NULL,
  name varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table category
--

INSERT INTO category (id, `name`) VALUES
(1, 'Cuisine'),
(2, 'Chasse'),
(3, 'Table'),
(4, 'Collection spéciale Noel 2022'),
(6, 'Collection Blitz Day 2022');

-- --------------------------------------------------------

--
-- Structure de la table doctrine_migration_versions
--

DROP TABLE IF EXISTS doctrine_migration_versions;
CREATE TABLE doctrine_migration_versions (
  version varchar(191) NOT NULL,
  executed_at datetime DEFAULT NULL,
  execution_time int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table doctrine_migration_versions
--

INSERT INTO doctrine_migration_versions (`version`, executed_at, execution_time) VALUES
('DoctrineMigrations\\Version20221026171332', '2022-10-30 10:45:03', 40),
('DoctrineMigrations\\Version20221028165546', '2022-11-01 11:24:24', 17),
('DoctrineMigrations\\Version20221104182626', '2022-11-20 17:20:06', 22),
('DoctrineMigrations\\Version20221105170315', '2022-11-20 17:20:06', 6),
('DoctrineMigrations\\Version20221105181025', '2022-11-20 17:20:06', 5),
('DoctrineMigrations\\Version20221105210720', '2022-11-20 17:20:06', 5),
('DoctrineMigrations\\Version20221105214239', '2022-11-20 17:20:06', 6),
('DoctrineMigrations\\Version20221105220558', '2022-11-20 17:20:06', 5),
('DoctrineMigrations\\Version20221106155035', '2022-11-20 17:20:06', 5),
('DoctrineMigrations\\Version20221106161020', '2022-11-20 17:20:06', 21),
('DoctrineMigrations\\Version20221107170142', '2022-11-20 17:20:06', 26),
('DoctrineMigrations\\Version20221107170845', '2022-11-20 17:20:06', 44),
('DoctrineMigrations\\Version20221107172105', '2022-11-20 17:20:06', 43),
('DoctrineMigrations\\Version20221107173508', '2022-11-20 17:20:06', 41),
('DoctrineMigrations\\Version20221108145316', '2022-11-20 17:20:06', 4),
('DoctrineMigrations\\Version20221109174431', '2022-11-20 17:20:06', 22),
('DoctrineMigrations\\Version20221119173205', '2022-11-27 15:34:44', 69),
('DoctrineMigrations\\Version20221119173334', '2022-11-27 15:34:44', 2);

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

DROP TABLE IF EXISTS events;
CREATE TABLE `events` (
  id int(11) NOT NULL,
  name varchar(255) NOT NULL,
  address varchar(255) NOT NULL,
  date date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (id, `name`, address, `date`) VALUES
(1, 'Pontault Knife World', '222 Ave CH.ROUXEL', '2022-11-26');

-- --------------------------------------------------------

--
-- Structure de la table handle
--

DROP TABLE IF EXISTS handle;
CREATE TABLE handle (
  id int(11) NOT NULL,
  name varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table handle
--

INSERT INTO handle (id, `name`) VALUES
(1, 'Corne de belzébuth'),
(2, 'Ivoire');

-- --------------------------------------------------------

--
-- Structure de la table images
--

DROP TABLE IF EXISTS images;
CREATE TABLE images (
  id int(11) NOT NULL,
  filename varchar(255) NOT NULL,
  mainpicture tinyint(1) NOT NULL,
  knifes_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table images
--

INSERT INTO images (id, filename, mainpicture, knifes_id) VALUES
(1, '20220309-173236-637d5a4897d25.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table knifes
--

DROP TABLE IF EXISTS knifes;
CREATE TABLE knifes (
  id int(11) NOT NULL,
  category_id int(11) NOT NULL,
  name varchar(255) NOT NULL,
  description longtext NOT NULL,
  stock int(11) NOT NULL,
  weight int(11) NOT NULL,
  lenght int(11) NOT NULL,
  close_lenght int(11) DEFAULT NULL,
  cuttingedge_lenght int(11) NOT NULL,
  price decimal(10,2) DEFAULT NULL,
  mechanism_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table knifes
--

INSERT INTO knifes (id, category_id, `name`, description, stock, weight, lenght, close_lenght, cuttingedge_lenght, price, mechanism_id) VALUES
(1, 6, 'RAMBO2', 'Aucune', 1, 390, 160, 85, 70, '565.00', 2);

-- --------------------------------------------------------

--
-- Structure de la table knifes_accessories
--

DROP TABLE IF EXISTS knifes_accessories;
CREATE TABLE knifes_accessories (
  knifes_id int(11) NOT NULL,
  accessories_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table knifes_accessories
--

INSERT INTO knifes_accessories (knifes_id, accessories_id) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table knifes_handle
--

DROP TABLE IF EXISTS knifes_handle;
CREATE TABLE knifes_handle (
  knifes_id int(11) NOT NULL,
  handle_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table knifes_handle
--

INSERT INTO knifes_handle (knifes_id, handle_id) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table knifes_metals
--

DROP TABLE IF EXISTS knifes_metals;
CREATE TABLE knifes_metals (
  knifes_id int(11) NOT NULL,
  metals_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table knifes_metals
--

INSERT INTO knifes_metals (knifes_id, metals_id) VALUES
(1, 2),
(1, 4);

-- --------------------------------------------------------

--
-- Structure de la table mechanism
--

DROP TABLE IF EXISTS mechanism;
CREATE TABLE mechanism (
  id int(11) NOT NULL,
  name varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table mechanism
--

INSERT INTO mechanism (id, `name`) VALUES
(1, 'Fixe'),
(2, 'Pliant');

-- --------------------------------------------------------

--
-- Structure de la table messenger_messages
--

DROP TABLE IF EXISTS messenger_messages;
CREATE TABLE messenger_messages (
  id bigint(20) NOT NULL,
  body longtext NOT NULL,
  headers longtext NOT NULL,
  queue_name varchar(190) NOT NULL,
  created_at datetime NOT NULL,
  available_at datetime NOT NULL,
  delivered_at datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table metals
--

DROP TABLE IF EXISTS metals;
CREATE TABLE metals (
  id int(11) NOT NULL,
  name varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table metals
--

INSERT INTO metals (id, `name`) VALUES
(1, 'Zyrconium'),
(2, 'Acier'),
(3, 'Titane'),
(4, 'Uranium');

-- --------------------------------------------------------

--
-- Structure de la table newsletter
--

DROP TABLE IF EXISTS newsletter;
CREATE TABLE newsletter (
  id int(11) NOT NULL,
  forknife tinyint(1) NOT NULL,
  forevents tinyint(1) NOT NULL,
  email varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table newsletter
--

INSERT INTO newsletter (id, forknife, forevents, email) VALUES
(1, 1, 1, 'yves77340@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table users
--

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id int(11) NOT NULL,
  firstname varchar(45) NOT NULL,
  lastname varchar(45) NOT NULL,
  email varchar(255) NOT NULL,
  address varchar(255) NOT NULL,
  password varchar(128) NOT NULL,
  created datetime NOT NULL,
  role longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`role`)),
  lastlogin datetime DEFAULT NULL,
  confirmpassword varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table users
--

INSERT INTO users (id, firstname, lastname, email, address, `password`, created, role, lastlogin, confirmpassword) VALUES
(4, 'Yves', 'Toubhans', 'yves77340@gmail.com', '21 rue des moines épicuriens, 77200 Pontolt', '$2y$13$huLlIjVc6ZjiqROrNx2xU.2qryrMZ8J1qXjrX5IRFBRERWNYS0zp6', '2022-11-20 18:21:00', '[]', NULL, '$2y$13$huLlIjVc6ZjiqROrNx2xU.2qryrMZ8J1qXjrX5IRFBRERWNYS0zp6'),
(6, 'Yves', 'Toubhans', 'yves.toubhans@free.fr', '21 rue des moines épicuriens, 77200 Pontolt', '$2y$13$t.EeL6nfcRpIOL9VYEkSsOQpAhfywHWPbQxPl2vjVzPQHfUByYVpW', '2022-11-21 12:51:00', '[]', NULL, '$2y$13$t.EeL6nfcRpIOL9VYEkSsOQpAhfywHWPbQxPl2vjVzPQHfUByYVpW'),
(7, 'Benjamin', 'Toubhans', 'benjamin.toubhans@orange.fr', '21 rue des moines épicuriens, 77200 Pontolt', '$2y$13$ouR5YS1dz1HMPGGUerdjkuHYvUuhllDF/6kcty9VXMwOOMpDhnUoa', '2022-11-21 19:03:31', '[]', NULL, '$2y$13$ouR5YS1dz1HMPGGUerdjkuHYvUuhllDF/6kcty9VXMwOOMpDhnUoa');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table accessories
--
ALTER TABLE accessories
  ADD PRIMARY KEY (id);

--
-- Index pour la table category
--
ALTER TABLE category
  ADD PRIMARY KEY (id);

--
-- Index pour la table doctrine_migration_versions
--
ALTER TABLE doctrine_migration_versions
  ADD PRIMARY KEY (version);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (id);

--
-- Index pour la table handle
--
ALTER TABLE handle
  ADD PRIMARY KEY (id);

--
-- Index pour la table images
--
ALTER TABLE images
  ADD PRIMARY KEY (id),
  ADD KEY IDX_E01FBE6A8C85B742 (knifes_id);

--
-- Index pour la table knifes
--
ALTER TABLE knifes
  ADD PRIMARY KEY (id),
  ADD KEY IDX_2E01276912469DE2 (category_id),
  ADD KEY IDX_2E01276937CD6DD0 (mechanism_id);

--
-- Index pour la table knifes_accessories
--
ALTER TABLE knifes_accessories
  ADD PRIMARY KEY (knifes_id,accessories_id),
  ADD KEY IDX_C01A6A7C8C85B742 (knifes_id),
  ADD KEY IDX_C01A6A7C35D022EB (accessories_id);

--
-- Index pour la table knifes_handle
--
ALTER TABLE knifes_handle
  ADD PRIMARY KEY (knifes_id,handle_id),
  ADD KEY IDX_11B90A5F8C85B742 (knifes_id),
  ADD KEY IDX_11B90A5F9C256C9C (handle_id);

--
-- Index pour la table knifes_metals
--
ALTER TABLE knifes_metals
  ADD PRIMARY KEY (knifes_id,metals_id),
  ADD KEY IDX_794FFD6D8C85B742 (knifes_id),
  ADD KEY IDX_794FFD6DB4E97D59 (metals_id);

--
-- Index pour la table mechanism
--
ALTER TABLE mechanism
  ADD PRIMARY KEY (id);

--
-- Index pour la table messenger_messages
--
ALTER TABLE messenger_messages
  ADD PRIMARY KEY (id),
  ADD KEY IDX_75EA56E0FB7336F0 (queue_name),
  ADD KEY IDX_75EA56E0E3BD61CE (available_at),
  ADD KEY IDX_75EA56E016BA31DB (delivered_at);

--
-- Index pour la table metals
--
ALTER TABLE metals
  ADD PRIMARY KEY (id);

--
-- Index pour la table newsletter
--
ALTER TABLE newsletter
  ADD PRIMARY KEY (id);

--
-- Index pour la table users
--
ALTER TABLE users
  ADD PRIMARY KEY (id);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table accessories
--
ALTER TABLE accessories
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table category
--
ALTER TABLE category
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table handle
--
ALTER TABLE handle
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table images
--
ALTER TABLE images
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table knifes
--
ALTER TABLE knifes
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table mechanism
--
ALTER TABLE mechanism
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table messenger_messages
--
ALTER TABLE messenger_messages
  MODIFY id bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table metals
--
ALTER TABLE metals
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table newsletter
--
ALTER TABLE newsletter
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table users
--
ALTER TABLE users
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
