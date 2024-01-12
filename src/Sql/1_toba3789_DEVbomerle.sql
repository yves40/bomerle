-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 11 jan. 2024 à 21:24
-- Version du serveur : 10.6.16-MariaDB
-- Version de PHP : 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `toba3789_DEVbomerle`
--

-- --------------------------------------------------------

--
-- Structure de la table `accessories`
--

CREATE TABLE `accessories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `accessories`
--

INSERT INTO `accessories` (`id`, `name`) VALUES
(1, 'Fourchette'),
(2, 'Tire bouchon');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rank` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `description`, `fullname`, `image`, `rank`) VALUES
(1, 'Créations', 'Dans cette section, je vous présente des créations originales réalisées selon mes humeurs du moment ou pour des commandes spéciales. Vous y trouverez des couteaux traditionnels ou très modernes dans leur forme, des mécanismes originaux et des couteaux fantaisie.', 'Créations', 'IMG-20230628-151218-01-01-659cf923aa0bc.webp', 1),
(2, 'Grands projets', 'Certains projets sont à mettre à part. Ce sont les grands projets, construits à plusieurs entre les commanditaires et tous les intervenants du projet. Le coutelier, le forgeron, les artistes graveurs ou autre corps de métier, pouvant intervenir dans la fabrication, peaufinent les détails jusqu\'à converger vers l\'objet final. Pour ces projets, j\'ai souvent plongé dans l\'inconnu. Mais en sollicitant l\'aide de mes pairs couteliers et de personnes qualifiées, j\'ai réussi à combler mes lacunes dans plusieurs domaines. C\'est avec ces grands projets que la coutellerie arrive au carrefour de plusieurs types d\'artisanat comme la métallerie, l’orfèvrerie, la gravure, le travail du cuir, la boissellerie et tant d\'autres.', 'Grands projets', 'IMG-20231102-100954-659cfad625096.webp', 3),
(3, 'La chasse', 'La chasse m\'évoque la connexion à la nature et me rappelle l\'origine du couteau; un véritable outil du quotidien. Le couteau de chasse doit être robuste et surtout fonctionnel. On trouve une grande variété de lames pouvant être utilisées à la chasse, du couteau de poche pour le casse croute à la grande dague de vénerie en passant par l\'épieu.\r\nN\'étant pas chasseur, je n\'ai en tête qu\'une image caricaturale du couteau de chasse. Rustique, efficace,souvent en bois de cerf. En partant de cette vision assez grossière, j\'essaie d\'apporter du raffinement au couteau de chasse en conservant son essence. J\'affectionne particulièrement ce registre car il me permet de travailler le bois de cerf, ma matière animale préférée.', 'La chasse', 'IMG-20230713-200107-01-659cf94c21d0a.webp', 9),
(4, 'Les régionaux', 'Les couteaux français de région ont marqué leur époque et percé quelques poches. Ils ont proliféré à partir du XIXème siècle avec le développement de l\'industrie et il s\'en créé encore aujourd\'hui. Chacun des modèles anciens est chargé d\'histoire. Couteaux du quotidien ou couteaux de travail, il devaient être adaptés aux coutumes locales. Ainsi, ces couteaux font partie du patrimoine culturel local. Je m\'efforce de produire des répliques de luxe de ces couteaux traditionnels en respectant autant que possible leur conception d\'origine.', 'Les régionaux', 'IMG-20230409-161223-01-1-659cf9ed95431.webp', 8),
(5, 'Orfèvrerie', 'Quand la coutellerie se retrouve au carrefour de la bijouterie, la joaillerie et l’orfèvrerie, cela me donne envie de tenter quelques expériences! Tout a commencé avec les couteaux de poche de luxe du XVIII ème siècle. Certains présentent des embellissements raffinés autour des métaux précieux. D\'autres sont construits avec des méthodes radicalement différentes des méthodes traditionnelles. Par exemple, les couteaux dit \"Gigogne\" dont le corps est formé de tôles pliées et brasées. Où les plaquettes sont serties à la manière des bijoux.\r\nJe vous présente ici mes expériences en orfèvrerie sur couteau.', 'Orfèvrerie', 'IMG-20230524-195742-01-659cf99854683.webp', 6),
(6, 'Restauration', 'Il m\'arrive de restaurer des pièces anciennes d\'exception. La plupart des couteaux anciens qui ont réussi à traverser les époques ne l\'ont pas fait sans souffrir les affres du temps. C\'est un exercice formateur pour tout coutelier que de démonter un couteau ancien, comprendre sa mécanique, la restaurer et remonter le couteau. Dans cet exercice, je m\'efforce de restaurer la mécanique dans son état de fonctionnement optimal tout en conservant les patines, c\'est à dire les marques d\'usure et de vieillissement du couteau.', 'Restauration', 'IMG-20231205-193148-659cfa618410f.webp', 11),
(7, 'Sheffield', 'Sheffield est une grande ville coutelière anglaise. Située dans un bassin sidérurgique, c\'est ici que l\'acier inoxydable fût inventé. Au XIXème siècle, à l\'occasion des expositions universelles, le savoir faire coutelier des artisans de la ville de Sheffield est mis en avant dans de grandes salles d\'expositions. Ces événements sont l\'occasion de produire des pièces sublimes qu\'on appelle communément couteaux d\'exhibition.', 'Sheffield', 'IMG-20230503-192645-659cfcb148c0b.webp', 5),
(8, 'Slipjoint', 'Je range dans cette catégorie mes créations inspirées de la coutellerie américaine, traditionnelle et moderne. L\'accent est mis sur la perfection des ajustages de la partie mécanique et du manche.', 'Slipjoint', 'IMG-20231123-105027-659cfbab34806.webp', 12),
(9, 'XVIIIème siècle', 'La coutellerie de luxe française a atteint son apogée au XVIIIème siècle. Époque faste où la créativité et le raffinement des artisans semble ne plus avoir de limite. Au delà de sa fonction d\'outil, le couteau devenait alors un objet de curiosité, une manière de surprendre ou de marquer son statut social.', 'XVIIIème siècle', 'IMG-20230707-143505-01-659cfc861682e.webp', 4),
(10, 'Les Italiens', 'Je trouve aux couteaux régionaux italiens une excentricité rafraichissante qui les distingue fortement des couteaux régionaux français. Je vous propose ici des chimères créées autour des couteaux italiens en les mélangeant à des couteaux traditionnels français.', 'Les Italiens', 'IMG-20230518-185200-659cf9ba33aa6.webp', 7),
(11, 'Mes salons', 'Les salons sont une bonne occasion pour moi d\'innover et de réaliser des créations libres. Vous trouverez ici la production présentées dans différents salons aux quatre coins de la France.', 'Mes salons', 'IMG-20231010-154630-659cfb6038d99.webp', 2),
(12, 'Châtellerault', 'La coutellerie de Châtellerault a connu une période prolifique à la fin du XVIIIème siècle et pendant tout le XIXeme. Les couteaux pliants produits à cette époque sont souvent de très bonne facture. Le style \"Châtellerault\" est très reconnaissable. Avec des mitres fantaisie, très ouvragées, rendues possible par une technique d\'emboutissage de tôle. Par ses formes allongées et ondulées comme sur le \"Serpent\" ou encore le \"Solognot\".  Ou encore par la robustesse de ses mécaniques avec des pièces forgées de forte épaisseur. On trouve dans la coutellerie de Châtellerault des couteaux utilitaires et de chasse mais aussi des couteaux de luxe de par leur matière (nacre, écaille et ivoire principalement) et le travail des mitres.', 'Châtellerault', 'IMG-20230421-171401-01-659cf69d9958a.webp', 10);

-- --------------------------------------------------------

--
-- Structure de la table `category_category`
--

CREATE TABLE `category_category` (
  `category_source` int(11) NOT NULL,
  `category_target` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category_category`
--

INSERT INTO `category_category` (`category_source`, `category_target`) VALUES
(1, 5),
(1, 6),
(1, 7),
(2, 1),
(2, 6),
(2, 11),
(3, 4),
(3, 6),
(3, 7),
(4, 3),
(4, 7),
(4, 10),
(5, 2),
(5, 6),
(5, 9),
(6, 1),
(6, 2),
(6, 9),
(7, 3),
(7, 9),
(8, 3),
(8, 5),
(8, 10),
(9, 1),
(9, 6),
(9, 7),
(10, 1),
(10, 4),
(10, 7),
(11, 1),
(11, 2),
(11, 4),
(12, 3),
(12, 4),
(12, 7);

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `object` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `date_range`
--

CREATE TABLE `date_range` (
  `id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dblog`
--

CREATE TABLE `dblog` (
  `id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `severity` int(11) NOT NULL,
  `logtime` datetime NOT NULL,
  `module` varchar(255) NOT NULL,
  `utctime` datetime NOT NULL,
  `action` varchar(64) DEFAULT NULL,
  `useremail` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dblog`
--

INSERT INTO `dblog` (`id`, `message`, `severity`, `logtime`, `module`, `utctime`, `action`, `useremail`) VALUES
(1, 'yves77340@gmail.com', 1, '2023-12-27 11:32:03', 'BootLoginFormAuthenticator', '2023-12-27 10:32:03', 'Login success', 'yves77340@gmail.com'),
(2, 'yves77340@gmail.com', 1, '2023-12-27 11:32:08', 'LogoutHandler', '2023-12-27 10:32:08', 'Logout success', 'yves77340@gmail.com'),
(3, 'yves77340@gmail.com', 1, '2023-12-27 15:03:17', 'BootLoginFormAuthenticator', '2023-12-27 14:03:17', 'Login success', 'yves77340@gmail.com'),
(4, 'yves77340@gmail.com', 1, '2023-12-27 15:08:48', 'LogoutHandler', '2023-12-27 14:08:48', 'Logout success', 'yves77340@gmail.com'),
(5, 'bastien.toubhans@free.fr', 1, '2023-12-27 15:09:02', 'BootLoginFormAuthenticator', '2023-12-27 14:09:02', 'Login success', 'bastien.toubhans@free.fr'),
(6, 'yves77340@gmail.com', 1, '2023-12-27 15:11:30', 'BootLoginFormAuthenticator', '2023-12-27 14:11:30', 'Login success', 'yves77340@gmail.com'),
(7, 'yves77340@gmail.com', 1, '2023-12-27 15:12:05', 'LogoutHandler', '2023-12-27 14:12:05', 'Logout success', 'yves77340@gmail.com'),
(8, 'bastien.toubhans@free.fr', 1, '2023-12-27 15:12:18', 'BootLoginFormAuthenticator', '2023-12-27 14:12:18', 'Login success', 'bastien.toubhans@free.fr'),
(9, 'bastien.toubhans@free.fr', 1, '2023-12-27 15:12:36', 'LogoutHandler', '2023-12-27 14:12:36', 'Logout success', 'bastien.toubhans@free.fr'),
(10, 'bastien.toubhans@free.fr', 1, '2023-12-27 15:16:22', 'BootLoginFormAuthenticator', '2023-12-27 14:16:22', 'Login success', 'bastien.toubhans@free.fr'),
(11, 'bastien.toubhans@free.fr', 3, '2023-12-27 18:04:16', 'BootLoginFormAuthenticator', '2023-12-27 17:04:16', 'Login failure', 'bastien.toubhans@free.fr'),
(12, 'bastien.toubhans@free.fr', 1, '2023-12-27 18:04:22', 'LogoutHandler', '2023-12-27 17:04:22', 'Logout success', 'bastien.toubhans@free.fr'),
(13, 'bastien.toubhans@free.fr', 3, '2023-12-28 11:47:13', 'BootLoginFormAuthenticator', '2023-12-28 10:47:13', 'Login failure', 'bastien.toubhans@free.fr'),
(14, 'bastien.toubhans@free.fr', 1, '2023-12-28 11:47:24', 'BootLoginFormAuthenticator', '2023-12-28 10:47:24', 'Login success', 'bastien.toubhans@free.fr'),
(15, 'bastien.toubhans@free.fr', 1, '2023-12-28 11:52:44', 'LogoutHandler', '2023-12-28 10:52:44', 'Logout success', 'bastien.toubhans@free.fr'),
(16, 'bastien.toubhans@free.fr', 1, '2023-12-29 20:54:05', 'BootLoginFormAuthenticator', '2023-12-29 19:54:05', 'Login success', 'bastien.toubhans@free.fr'),
(17, 'bastien.toubhans@free.fr', 1, '2023-12-29 20:54:46', 'LogoutHandler', '2023-12-29 19:54:46', 'Logout success', 'bastien.toubhans@free.fr'),
(18, 'bastien.toubhans@free.fr', 3, '2023-12-30 16:00:28', 'BootLoginFormAuthenticator', '2023-12-30 15:00:28', 'Login failure', 'bastien.toubhans@free.fr'),
(19, 'bastien.toubhans@free.fr', 3, '2023-12-30 16:00:53', 'BootLoginFormAuthenticator', '2023-12-30 15:00:53', 'Login failure', 'bastien.toubhans@free.fr'),
(20, 'bastien.toubhans@free.fr', 1, '2023-12-30 16:01:49', 'BootLoginFormAuthenticator', '2023-12-30 15:01:49', 'Login success', 'bastien.toubhans@free.fr'),
(21, 'bastien.toubhans@free.fr', 1, '2023-12-30 17:00:31', 'BootLoginFormAuthenticator', '2023-12-30 16:00:31', 'Login success', 'bastien.toubhans@free.fr'),
(22, 'bastien.toubhans@free.fr', 1, '2023-12-30 17:01:07', 'LogoutHandler', '2023-12-30 16:01:07', 'Logout success', 'bastien.toubhans@free.fr'),
(23, 'yves77340@gmail.com', 1, '2023-12-30 17:01:21', 'BootLoginFormAuthenticator', '2023-12-30 16:01:21', 'Login success', 'yves77340@gmail.com'),
(24, 'bastien.toubhans@free.fr', 3, '2024-01-01 21:34:10', 'BootLoginFormAuthenticator', '2024-01-01 20:34:10', 'Login failure', 'bastien.toubhans@free.fr'),
(25, 'bastien.toubhans@free.fr', 1, '2024-01-01 21:34:16', 'BootLoginFormAuthenticator', '2024-01-01 20:34:16', 'Login success', 'bastien.toubhans@free.fr'),
(26, 'yves77340@gmail.com', 1, '2024-01-06 22:20:22', 'BootLoginFormAuthenticator', '2024-01-06 21:20:22', 'Login success', 'yves77340@gmail.com'),
(27, 'yves77340@gmail.com', 1, '2024-01-06 22:22:22', 'LogoutHandler', '2024-01-06 21:22:22', 'Logout success', 'yves77340@gmail.com'),
(28, 'yves77340@gmail.com', 1, '2024-01-07 10:28:30', 'BootLoginFormAuthenticator', '2024-01-07 09:28:30', 'Login success', 'yves77340@gmail.com'),
(29, 'yves77340@gmail.com', 1, '2024-01-07 10:31:04', 'LogoutHandler', '2024-01-07 09:31:04', 'Logout success', 'yves77340@gmail.com'),
(30, 'bastien.toubhans@free.fr', 1, '2024-01-07 18:11:48', 'BootLoginFormAuthenticator', '2024-01-07 17:11:48', 'Login success', 'bastien.toubhans@free.fr'),
(31, 'yves77340@gmail.com', 1, '2024-01-08 18:32:00', 'BootLoginFormAuthenticator', '2024-01-08 17:32:00', 'Login success', 'yves77340@gmail.com'),
(32, 'yves77340@gmail.com', 1, '2024-01-08 19:02:03', 'LogoutHandler', '2024-01-08 18:02:03', 'Logout success', 'yves77340@gmail.com'),
(33, 'yves77340@gmail.com', 1, '2024-01-08 19:04:21', 'BootLoginFormAuthenticator', '2024-01-08 18:04:21', 'Login success', 'yves77340@gmail.com'),
(34, 'yves77340@gmail.com', 1, '2024-01-08 19:05:43', 'LogoutHandler', '2024-01-08 18:05:43', 'Logout success', 'yves77340@gmail.com'),
(35, 'i.toubhans@free.fr', 1, '2024-01-08 19:05:58', 'BootLoginFormAuthenticator', '2024-01-08 18:05:58', 'Login success', 'i.toubhans@free.fr'),
(36, 'i.toubhans@free.fr', 1, '2024-01-08 19:06:31', 'LogoutHandler', '2024-01-08 18:06:31', 'Logout success', 'i.toubhans@free.fr'),
(37, 'bastien.toubhans@free.fr', 1, '2024-01-09 08:32:14', 'BootLoginFormAuthenticator', '2024-01-09 07:32:14', 'Login success', 'bastien.toubhans@free.fr'),
(38, 'i.toubhans@free.fr', 1, '2024-01-09 15:12:25', 'BootLoginFormAuthenticator', '2024-01-09 14:12:25', 'Login success', 'i.toubhans@free.fr'),
(39, 'i.toubhans@free.fr', 1, '2024-01-09 15:34:48', 'LogoutHandler', '2024-01-09 14:34:48', 'Logout success', 'i.toubhans@free.fr'),
(40, 'i.toubhans@free.fr', 1, '2024-01-10 09:23:33', 'BootLoginFormAuthenticator', '2024-01-10 08:23:33', 'Login success', 'i.toubhans@free.fr'),
(41, 'i.toubhans@free.fr', 1, '2024-01-10 21:07:35', 'BootLoginFormAuthenticator', '2024-01-10 20:07:35', 'Login success', 'i.toubhans@free.fr'),
(42, 'i.toubhans@free.fr', 1, '2024-01-10 21:08:07', 'LogoutHandler', '2024-01-10 20:08:07', 'Logout success', 'i.toubhans@free.fr'),
(43, 'bastien.toubhans@free.fr', 1, '2024-01-11 19:44:18', 'BootLoginFormAuthenticator', '2024-01-11 18:44:18', 'Login success', 'bastien.toubhans@free.fr');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `handle`
--

CREATE TABLE `handle` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `handle`
--

INSERT INTO `handle` (`id`, `name`) VALUES
(1, 'Chêne'),
(2, 'Ebène'),
(3, 'Corne de buffle'),
(4, 'Ivoire de phacochère');

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `mainpicture` tinyint(1) NOT NULL,
  `knifes_id` int(11) NOT NULL,
  `rank` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `filename`, `mainpicture`, `knifes_id`, `rank`) VALUES
(3, 'IMG-20230409-160549-659047634cdda.webp', 1, 2, 2),
(4, 'IMG-20230409-160646-659047669fd5e.webp', 0, 2, 3),
(5, 'IMG-20230409-161034-659047690bfc5.webp', 0, 2, 4),
(6, 'IMG-20230409-161223-01-1-6590476c80897.webp', 0, 2, 1),
(7, 'IMG-20230503-192359-65904bfae0898.webp', 1, 3, 3),
(8, 'IMG-20230503-192405-65904bfd8ef52.webp', 0, 3, 5),
(9, 'IMG-20230503-192434-65904bffdf75b.webp', 0, 3, 4),
(10, 'IMG-20230503-192452-2-65904c027efb6.webp', 0, 3, 1),
(11, 'IMG-20230503-192535-65904c058bc47.webp', 0, 3, 6),
(12, 'IMG-20230503-192556-65904c0838e9c.webp', 0, 3, 7),
(13, 'IMG-20230503-192645-65904c0ba28d3.webp', 0, 3, 2),
(14, 'IMG-20230628-145815-01-65904f9231104.webp', 1, 4, 1),
(15, 'IMG-20230628-150226-01-65904f947cb54.webp', 0, 4, 2),
(16, 'IMG-20230628-150614-01-65904f975a3be.webp', 0, 4, 3),
(17, 'IMG-20230628-151218-01-01-65904f99528ea.webp', 0, 4, 4),
(18, 'IMG-20230628-151513-65904f9ac0ea6.webp', 0, 4, 5),
(19, 'IMG-20230121-192321-659051151b52a.webp', 1, 5, 2),
(20, 'IMG-20230327-110345-65905117bc16b.webp', 0, 5, 3),
(21, 'IMG-20230623-110848-01-6590511b93ca9.webp', 0, 5, 4),
(22, 'IMG-20230824-192325-01-6590511de70a1.webp', 0, 5, 1),
(23, 'IMG-20230824-192428-01-6590512023a8c.webp', 0, 5, 6),
(24, 'IMG-20230825-100030-65905122a8dfb.webp', 0, 5, 7),
(25, 'IMG-20230913-213750-659051256005c.webp', 0, 5, 5),
(26, 'IMG-20230828-200238-01-6590540685459.webp', 1, 6, 1),
(27, 'IMG-20230828-200323-01-6590540911ed7.webp', 0, 6, 2),
(28, 'IMG-20230828-200340-01-6590540cb5262.webp', 0, 6, 3),
(29, 'IMG-20230828-200518-01-659054103b98f.webp', 0, 6, 4),
(30, 'IMG-20230524-195742-01-659055625d469.webp', 1, 7, 1),
(31, 'IMG-20230524-195910-01-6590556570c40.webp', 0, 7, 2),
(32, 'IMG-20230707-143349-01-6590556780c55.webp', 0, 7, 3),
(33, 'IMG-20230707-143505-01-659055693dd9b.webp', 0, 7, 4),
(34, 'IMG-20230421-171401-01-659058cc08306.webp', 1, 8, 3),
(35, 'IMG-20230421-171518-659058cd9e8b8.webp', 0, 8, 5),
(36, 'IMG-20230421-171918-659058d151bfe.webp', 0, 8, 6),
(37, 'IMG-20230421-171936-01-659058d5002d7.webp', 0, 8, 1),
(38, 'IMG-20230421-171958-659058d73a205.webp', 0, 8, 7),
(39, 'IMG-20230421-172007-01-659058dae60bc.webp', 0, 8, 4),
(40, 'IMG-20230421-172040-659058de34594.webp', 0, 8, 2),
(41, 'IMG-20230713-200107-01-65905acfaab03.webp', 1, 9, 1),
(42, 'IMG-20230713-200318-01-65905ad3187a6.webp', 0, 9, 2),
(43, 'IMG-20230715-143910-01-65905ad6839f3.webp', 0, 9, 3),
(44, 'IMG-20230715-144050-65905ad938101.webp', 0, 9, 4),
(45, 'IMG-20230818-123352-01-65905adc030a6.webp', 0, 9, 5),
(46, 'IMG-20230818-123440-01-65905add2616e.webp', 0, 9, 6),
(47, 'IMG-20230725-122252-65905c06a5e7a.webp', 1, 10, 1),
(48, 'IMG-20230725-122302-65905c0a4ea49.webp', 0, 10, 2),
(49, 'IMG-20230725-122329-01-65905c0e1b739.webp', 0, 10, 3),
(50, 'IMG-20230725-122337-01-65905c1068dd4.webp', 0, 10, 4),
(51, '410727384-3535860716626649-5911508449369512947-n-65905f9624106.webp', 1, 11, 1),
(52, 'IMG-20231007-204822-65905f971588a.webp', 0, 11, 2),
(53, 'IMG-20231007-204835-65905f9a4137f.webp', 0, 11, 3),
(54, 'IMG-20231007-214711-65905f9d8043f.webp', 0, 11, 4),
(55, 'IMG-20231008-110937-65905fa06af55.webp', 0, 11, 5),
(56, 'IMG-20231008-120806-65905fa2c3da2.webp', 0, 11, 6),
(57, 'IMG-20231008-153248-65905fa54fa58.webp', 0, 11, 7),
(58, 'IMG-20230919-081611-01-659061861664c.webp', 1, 12, 1),
(59, 'IMG-20230919-081657-659061892b2b1.webp', 0, 12, 2),
(60, 'IMG-20231123-105000-65906271b855e.webp', 1, 13, 1),
(61, 'IMG-20231123-105027-65906274c733b.webp', 0, 13, 2),
(62, 'IMG-20231010-154301-01-65932b5ca5502.webp', 1, 14, 2),
(63, 'IMG-20231010-154630-65932b5f10efa.webp', 0, 14, 1),
(64, 'IMG-20231010-154745-01-65932b62a8ba6.webp', 0, 14, 3),
(65, 'IMG-20231010-154904-65932b664bf7c.webp', 0, 14, 4),
(66, '20230905-184001-65932c203876c.webp', 1, 15, 4),
(67, '20230905-184128-65932c23aedbe.webp', 0, 15, 3),
(68, '20230905-190047-65932c2739a11.webp', 0, 15, 5),
(69, '20230905-190104-65932c2ab83ca.webp', 0, 15, 1),
(70, '20230905-190118-65932c2e72753.webp', 0, 15, 6),
(71, '20230905-190342-65932c319547a.webp', 0, 15, 2),
(72, '20230905-190353-65932c34d95d8.webp', 0, 15, 7),
(73, 'IMG-20230421-171401-01-65932cc1840ad.webp', 1, 16, 1);

-- --------------------------------------------------------

--
-- Structure de la table `knifes`
--

CREATE TABLE `knifes` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `lenght` int(11) DEFAULT NULL,
  `close_lenght` int(11) DEFAULT NULL,
  `cuttingedge_lenght` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `mechanism_id` int(11) NOT NULL,
  `published` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `knifes`
--

INSERT INTO `knifes` (`id`, `category_id`, `name`, `description`, `stock`, `weight`, `lenght`, `close_lenght`, `cuttingedge_lenght`, `price`, `mechanism_id`, `published`) VALUES
(2, 4, 'Le London', 'Ce couteau d\'origine anglaise a trouvé son public en Bretagne. Sa lame large dépourvue de pointe est particulièrement indiquée pour le travail en mer où il faut éviter d\'écorcher son voisin dans le chahut des vagues. Ce modèle régional est interprété ici dans une version plus dodue. Ici en pointe de corne de zébu avec une lame sandwich en vieux fer, nickel pur et un tranchant en acier riche en carbone (C130).', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(3, 7, 'Le Canif', 'Ce petit canif est inspiré de la coutellerie d\'exhibition du XIXème siècle à Sheffield. On retrouve le style dans les décorations du ressort et la sculpture des mitres. Il est réalisé avec une lame en acier inoxydable et un manche en pointe de corne de zébu et des platines mitres en maillechort. Quelques éléments trahissent cependant son origine chimérique! La présence d\'un onglet de style Nogentais et l\'utilisation de la corne noire ne crient pas Sheffield.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(4, 1, 'Le couteau de Pique Nique', 'Ce couteau est inspiré des couteaux de voyage du XVIIIèeme et XIXème siècle. Je souhaitais faire le couteau de survie du bon vivant. Pour ne jamais être pris de court, cet ensemble est équipé d\'une bonne lame et d\'une fourchette pliante rétractable dans le manche. Question de survie, il est aussi équipé d\'un tire bouchon fraisé à la main. Le système de blocage de la lame est un cran d\'arrêt à palanquille. La palanquille est le petit levier situé sur le dos du couteau qui permet de lever le ressort et débloquer la fermeture du couteau. Ce couteau connait beaucoup de succès et je l\'ai décliné dans de nombreuse version. Ici, il est dans l\'une de mes configurations préférées en corne de buffle. Avec des mitres multiples en bronze et en inox.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(5, 1, 'Le couteau de Pique Nique', 'Je vous présente ici quelques variations sur le thème du couteau de pique nique. On le retrouve ici en bois de cerf sambar, en bois de fer d\'Arizona, en ivoire de mammouth ou encore en dent de phacochère. Outre les variations de matière, j\'aime modifier la forme des mitres, leur position, le travail de façonnage, le style de la fourchette afin de créer un couteau d’exception unique en son genre.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(6, 5, 'Le diderot moderne', 'Les couteaux à plaquettes serties ont un charme fou à mes yeux dans la mesure où ils laissent la matière s\'exprimer pleinement. Ici, la coutellerie rencontre la bijouterie et l’orfèvrerie. En effet, les plaquettes sont maintenus en repliant les platines dessus. Cela créé un fin liseret très élégant qui dessine le contour du couteau. J\'ai passé de nombreuses semaines à développer un protocole de travail permettant de réaliser ce genre de couteau à partir de photos  de couteaux anciens et parfois de planches d\'époque. J\'utilise des techniques de pliage de tôle et de brasure proche de celles utilisées au XVIIIème siècle. La difficulté technique de ces couteaux, outre leur extrême finesse, est de réaliser un sertissage correct tout en conservant la planéité de l\'ensemble. J\'ai commencé mes expériences de sertissage sur le modèle Diderot qui a une forme simple, très longiligne. J\'ai modernisé la ligne en la rendant encore plus géométrique. Les premières versions sont en corne de buffle, un matériau très tolérant avec le sertissage qui peut mettre à rude épreuve les matières fragiles.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(7, 5, 'Expériences de sertissage', 'Je présente ici plusieurs essais réussis de Diderot serti avec des variations sur les mitres. A noter que sur les couteaux sans mitres centrale ou sans aucune mitre, le sertissage des plaquette doit être réalisé après le montage du couteau ce qui représente une gymnastique terrible.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(8, 3, 'L\'âme du Cerf', 'Ce couteau de chasse bi-lame est fabriqué autour du bois de cerf sambar. Une portion de bois de cerf est fendue en deux, les surfaces sciées sont nettoyées et cela sert de point de départ à la conception du couteau. Toutes les pièces sont sculptées pour épouser les textures naturelles du bois sans les altérer. Le bois de cerf sambar est mon matériau préféré. C\'est une espèce de cerf originaire d\'Asie qui a la particularité d\'avoir des bois bien plus dense que les cerfs de nos régions. Il peut avoir des textures et des couleurs très variées en fonction de la région où il a été récolté.\r\nTraditionnelement, les couteaux de chasse bi-lame sont équipés d\'une lame de casse-croûte et d\'une lame à éviscérer, sans pointe. Sur cette version de luxe, j\'ai préféré faire deux lame de casse-croûte. La première avec un profil de lame dit Yatagan et l\'autre, plus large avec une lame de type Bourbonaise ou lancette Sheffield.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(9, 3, 'Variations autour du bois de cerf sambar', 'On constate ici la variété de couleur et de texture du majestueux bois de cerf sambar.\r\nJe réalise ces couteaux en cran forcé mais aussi en cran d\'arrêt à pompe arrière.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(10, 9, 'Galatée', 'Long couteau de table effilé avec une mitre sculptée en dés à coudre et un ressort amincit. Les plaquettes sont en ivoire de mammouth couleur crême.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(11, 6, 'Dague de vertu', 'Les dagues de vertu étaient, selon la légende, portées à la jarretière par les prostituées pour se défendre en cas de clients facheux. Je doute qu\'elles aient pu se procurer une pièce d\'exception comme cette replique d\'un couteau de Victor Feyolle. Coutelier Nogentais du XIXème siècle. Cette réplique de très bonne facture est vraissemblablement issue du bassin nogentais ou de Châtellerault. \r\nElle m\'est arrivée en piteux état avec une plaquette fendue et la mécanique complétement sortie de ses gonds. Après démontage complet du couteau, j\'ai redressé les platines et repercé les trous de la mécanique qui avait été déformés. J\'ai ensuite pu remonter le couteau en assurant le bon fonctionnement de la pompe et l\'alignement de la lame dans l\'axe du couteau. Une nouvelle plaquette identique a celle d\'origine a aussi été sculptée.\r\nJ\'ai été frappé par la qualité des pièces d\'origine qui sont travaillées avec une finesse et une précision à faire palir les couteliers de notre époque. Lorsqu\'on sait que tout a été réalisé à la lime et autres outils manuels, cela fait réfléchir sur l\'importance de maitriser les gestes de base avant de se lancer dans l\'achat de machines. Les mitres en tôle embouties et remplies d\'étain sont intégrées au couteau avec une précision remarquable.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(12, 2, 'Le canif fruitier', 'Ce couteau est une interprétation d\'un canif fruitier du XVIIIème siècle qui est passé entre mes mains pendant un salon de coutellerie. Ce petit canif en écaille décoré de fins filets d\'argent m\'a séduit par la forme original de ses lames sans pointe. Il est équipé d\'une lame en acier et d\'une lame en argent. L\'argent était utilisé pour l\'usage alimentaire afin d\'éviter de tacher les aliments et d\'y laisser le mauvais gout d\'oxydation des lames carbones. J\'ai redessiné ce couteau de mémoire et remplacé les filets d\'argent par un cloutage fantaisie en or et en argent. La lame en argent est montée sur un talon en acier grâce à un système de queue d’aronde brasée. Cela permet à la partie mécanique de la lame en contact avec le ressort de ne pas s\'user trop vite. Le manche est en corne de buffle et les mitres en maillechort.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(13, 8, 'Canif d\'automne', 'C\'est un slipjoint en os cerfé teinté de rouge. Avec une mitre en inox incrustée de deux bandes de laiton brasée.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(14, 11, 'La monnerie 2023', 'Salon de la Durolle aux couteaux organisé par Les Vieilles Lames à la Monnerie le Montel', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(15, 10, 'L\'Alpin Sarde', 'Encore une chimère, mélange du couteau régional l\'alpin et du Pattadese sarde. Ici en bois de cerf sambar poli, mitre en laiton et lame en acier carbone. Un couteau arlequin avec uniquement des matières qui patinent. Le temps le fera changer de couleur au gré des saisons.', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1),
(16, 12, 'Rien', 'Pas encore de Châtellerault!', NULL, NULL, NULL, NULL, NULL, 0.00, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `knifes_accessories`
--

CREATE TABLE `knifes_accessories` (
  `knifes_id` int(11) NOT NULL,
  `accessories_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `knifes_handle`
--

CREATE TABLE `knifes_handle` (
  `knifes_id` int(11) NOT NULL,
  `handle_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `knifes_metals`
--

CREATE TABLE `knifes_metals` (
  `knifes_id` int(11) NOT NULL,
  `metals_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mechanism`
--

CREATE TABLE `mechanism` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mechanism`
--

INSERT INTO `mechanism` (`id`, `name`) VALUES
(1, 'Fixe'),
(2, 'Pliant'),
(3, 'Palanquille');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `metals`
--

CREATE TABLE `metals` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `metals`
--

INSERT INTO `metals` (`id`, `name`) VALUES
(1, 'Acier'),
(2, 'Aluminium'),
(3, 'Bronze'),
(4, 'Damasteel');

-- --------------------------------------------------------

--
-- Structure de la table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `forknife` tinyint(1) NOT NULL,
  `forevents` tinyint(1) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `requests_tracker`
--

CREATE TABLE `requests_tracker` (
  `id` int(11) NOT NULL,
  `requestactiontype` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `selector` longtext NOT NULL,
  `token` longtext NOT NULL,
  `expires` int(11) NOT NULL,
  `status` smallint(6) NOT NULL,
  `created` datetime NOT NULL,
  `processed` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `level` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `level`, `description`) VALUES
(1, 'ROLE_ADMINISTRATOR', 100, 'Agregates all admin roles, look at security.yaml'),
(2, 'ROLE_ADMIN', 90, 'Top level admin role, full privileges'),
(3, 'ROLE_ADMIN_SITE', 90, 'Top level admin role, full privileges'),
(4, 'ROLE_ADMIN_USERS', 80, 'Full privileges on user administration'),
(5, 'ROLE_ANONYMOUS', 10, 'Any user can access public features ');

-- --------------------------------------------------------

--
-- Structure de la table `slide_images`
--

CREATE TABLE `slide_images` (
  `id` int(11) NOT NULL,
  `slideshow_id` int(11) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `rank` smallint(6) NOT NULL,
  `legend` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `slide_show`
--

CREATE TABLE `slide_show` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `datein` date DEFAULT NULL,
  `dateout` date DEFAULT NULL,
  `description` varchar(8192) NOT NULL,
  `daterange` tinyint(1) NOT NULL,
  `slider` tinyint(1) DEFAULT NULL,
  `monday` tinyint(1) DEFAULT NULL,
  `tuesday` tinyint(1) DEFAULT NULL,
  `wednesday` tinyint(1) DEFAULT NULL,
  `thursday` tinyint(1) DEFAULT NULL,
  `friday` tinyint(1) DEFAULT NULL,
  `saturday` tinyint(1) DEFAULT NULL,
  `sunday` tinyint(1) DEFAULT NULL,
  `gallery` tinyint(1) DEFAULT NULL,
  `timing` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(128) NOT NULL,
  `created` datetime NOT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `confirmpassword` varchar(128) NOT NULL,
  `confirmed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `address`, `password`, `created`, `lastlogin`, `confirmpassword`, `confirmed`) VALUES
(1, 'Yves', 'Toubhans', 'yves77340@gmail.com', 'La Lune 3ème étage', '$2y$13$RfzSlQAuYMnE.y8UQmDGd.npVrhWyfHU79.kroq3VPzE08y7tdcN6', '2023-12-27 11:31:14', NULL, '$2y$13$mOiY9hiGGAXZQyD5rEReZutZXmPUZ1f/zojrPiLzvXN3TCVUBih0i', '2023-12-27 11:31:14'),
(2, 'Benjamin', 'Toubhans', 'benjamin.toubhans@orange.fr', 'Mars with Elon', '$2y$13$facWuwrJqbWlSx5AuEwzPuOT9CHOZx2cNdELS.RV4q59RQD1mWTOC', '2023-12-27 11:31:16', NULL, '$2y$13$GpxSSHwqYh7FqGr/wwdK5uYz5PA.25t41ebVphZq1EBxb5YszOL5q', '2023-12-27 11:31:16'),
(3, 'Isabelle', 'Toubhans', 'i.toubhans@free.fr', 'Pluton with Jeff', '$2y$13$2SHpYFVbciL2XwmSa5Ew6.C11JQhUMe.kPzAhlGeAvNQVQudep1Qe', '2023-12-27 11:31:17', NULL, '$2y$13$2SHpYFVbciL2XwmSa5Ew6.C11JQhUMe.kPzAhlGeAvNQVQudep1Qe', '2023-12-27 11:31:17'),
(9, 'Bastien', 'Toubhans', 'bastien.toubhans@free.fr', '15 rue de Lyon 63250 Chabreloche', '$2y$13$bykthGOyYqdpEvNrRxqukeLyizTUt6Ud6.Vl4VB1VLORycmqD4/HC', '2023-12-27 15:08:23', NULL, '$2y$13$bykthGOyYqdpEvNrRxqukeLyizTUt6Ud6.Vl4VB1VLORycmqD4/HC', '2023-12-27 15:08:23');

-- --------------------------------------------------------

--
-- Structure de la table `users_roles`
--

CREATE TABLE `users_roles` (
  `users_id` int(11) NOT NULL,
  `roles_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users_roles`
--

INSERT INTO `users_roles` (`users_id`, `roles_id`) VALUES
(1, 1),
(1, 5),
(2, 4),
(2, 5),
(3, 3),
(3, 5),
(9, 1),
(9, 5);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accessories`
--
ALTER TABLE `accessories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category_category`
--
ALTER TABLE `category_category`
  ADD PRIMARY KEY (`category_source`,`category_target`),
  ADD KEY `IDX_B1369DBA5062B508` (`category_source`),
  ADD KEY `IDX_B1369DBA4987E587` (`category_target`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4C62E638B83297E7` (`reservation_id`);

--
-- Index pour la table `date_range`
--
ALTER TABLE `date_range`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `dblog`
--
ALTER TABLE `dblog`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `handle`
--
ALTER TABLE `handle`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E01FBE6A8C85B742` (`knifes_id`);

--
-- Index pour la table `knifes`
--
ALTER TABLE `knifes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2E01276912469DE2` (`category_id`),
  ADD KEY `IDX_2E01276937CD6DD0` (`mechanism_id`);

--
-- Index pour la table `knifes_accessories`
--
ALTER TABLE `knifes_accessories`
  ADD PRIMARY KEY (`knifes_id`,`accessories_id`),
  ADD KEY `IDX_C01A6A7C8C85B742` (`knifes_id`),
  ADD KEY `IDX_C01A6A7C35D022EB` (`accessories_id`);

--
-- Index pour la table `knifes_handle`
--
ALTER TABLE `knifes_handle`
  ADD PRIMARY KEY (`knifes_id`,`handle_id`),
  ADD KEY `IDX_11B90A5F8C85B742` (`knifes_id`),
  ADD KEY `IDX_11B90A5F9C256C9C` (`handle_id`);

--
-- Index pour la table `knifes_metals`
--
ALTER TABLE `knifes_metals`
  ADD PRIMARY KEY (`knifes_id`,`metals_id`),
  ADD KEY `IDX_794FFD6D8C85B742` (`knifes_id`),
  ADD KEY `IDX_794FFD6DB4E97D59` (`metals_id`);

--
-- Index pour la table `mechanism`
--
ALTER TABLE `mechanism`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `metals`
--
ALTER TABLE `metals`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `requests_tracker`
--
ALTER TABLE `requests_tracker`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `slide_images`
--
ALTER TABLE `slide_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_42F946CE8B14E343` (`slideshow_id`);

--
-- Index pour la table `slide_show`
--
ALTER TABLE `slide_show`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users_roles`
--
ALTER TABLE `users_roles`
  ADD PRIMARY KEY (`users_id`,`roles_id`),
  ADD KEY `IDX_51498A8E67B3B43D` (`users_id`),
  ADD KEY `IDX_51498A8E38C751C4` (`roles_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `accessories`
--
ALTER TABLE `accessories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `date_range`
--
ALTER TABLE `date_range`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dblog`
--
ALTER TABLE `dblog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `handle`
--
ALTER TABLE `handle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT pour la table `knifes`
--
ALTER TABLE `knifes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `mechanism`
--
ALTER TABLE `mechanism`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `metals`
--
ALTER TABLE `metals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `requests_tracker`
--
ALTER TABLE `requests_tracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `slide_images`
--
ALTER TABLE `slide_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `slide_show`
--
ALTER TABLE `slide_show`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `category_category`
--
ALTER TABLE `category_category`
  ADD CONSTRAINT `FK_B1369DBA4987E587` FOREIGN KEY (`category_target`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B1369DBA5062B508` FOREIGN KEY (`category_source`) REFERENCES `category` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `FK_4C62E638B83297E7` FOREIGN KEY (`reservation_id`) REFERENCES `knifes` (`id`);

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

--
-- Contraintes pour la table `slide_images`
--
ALTER TABLE `slide_images`
  ADD CONSTRAINT `FK_42F946CE1D87D6D` FOREIGN KEY (`slideshow_id`) REFERENCES `slide_show` (`id`);

--
-- Contraintes pour la table `users_roles`
--
ALTER TABLE `users_roles`
  ADD CONSTRAINT `FK_51498A8E38C751C4` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_51498A8E67B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
