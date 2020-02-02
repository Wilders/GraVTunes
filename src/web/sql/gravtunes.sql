-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  Dim 02 fév. 2020 à 15:56
-- Version du serveur :  8.0.18
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `gravtunes`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total` float NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `statut` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creationDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `user_id`, `total`, `paid`, `statut`, `creationDate`) VALUES
(17, 3, 38, 0, 'créé', '2020-02-02 15:36:46'),
(18, 3, 38, 1, 'créé', '2020-02-02 15:40:32'),
(19, 3, 118, 1, 'créé', '2020-02-02 15:43:48'),
(20, 3, 77, 1, 'créé', '2020-02-02 15:45:26'),
(21, 3, 25, 0, 'créé', '2020-02-02 15:46:19'),
(22, 3, 25, 0, 'créé', '2020-02-02 15:46:42'),
(23, 3, 25, 1, 'créé', '2020-02-02 15:47:23'),
(24, 3, 204, 1, 'créé', '2020-02-02 15:52:36');

-- --------------------------------------------------------

--
-- Structure de la table `commande_vinyle`
--

DROP TABLE IF EXISTS `commande_vinyle`;
CREATE TABLE IF NOT EXISTS `commande_vinyle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commande_id` int(11) NOT NULL,
  `vinyle_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `duree` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `files`
--

INSERT INTO `files` (`id`, `path`, `hash`, `duree`) VALUES
(1, 'Damso_FaisMoiUnVie.mp3', '9af1ea6bb0b065839e52424b4a679080', 167),
(2, 'Larry_Block.mp3', 'd1c1c11d97f404b8060121cc31f4f574', 157),
(3, 'Alkpote_Cicatrices.mp3', '0805a5ee66a3d69da9ddd2339ad2d77f', 217),
(4, 'Alkpote.ft.KalashCriminel_Patek.mp3', '5ed5f4f95e8459029a9f66aed64804eb', 187),
(5, 'AW_playoff.mp3', '63a601ec737cafbd86b57103201fd631', 183),
(6, 'AW_pistoletrose.mp3', 'de1748ad61434bddbdb40b7b072af4de', 127),
(7, 'AW_pistoletrose2.mp3', 'ad0fc4983e2d76283d8dae8fb1e6e2eb', 98);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creationDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `ticket_id`, `user_id`, `message`, `creationDate`) VALUES
(1, 1, 1, 'test', '2020-01-08 00:00:00'),
(2, 2, 1, 'test', '2020-01-08 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commande_id` int(11) NOT NULL,
  `success` tinyint(1) NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `playlists`
--

DROP TABLE IF EXISTS `playlists`;
CREATE TABLE IF NOT EXISTS `playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nom` varchar(75) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creationDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `playlists`
--

INSERT INTO `playlists` (`id`, `user_id`, `nom`, `description`, `creationDate`) VALUES
(1, 1, 'PPP', 'EP Alpha Wann', '2020-01-08'),
(2, 1, 'Playlist Alkpote', 'Compilation Album Monument', '2020-01-08');

-- --------------------------------------------------------

--
-- Structure de la table `playlist_track`
--

DROP TABLE IF EXISTS `playlist_track`;
CREATE TABLE IF NOT EXISTS `playlist_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `playlist_track`
--

INSERT INTO `playlist_track` (`id`, `playlist_id`, `track_id`) VALUES
(1, 1, 5),
(2, 1, 6),
(3, 1, 7),
(4, 2, 3),
(5, 2, 4);

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `objet` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `statut` tinyint(1) NOT NULL,
  `creationDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id`, `objet`, `statut`, `creationDate`) VALUES
(1, 'test', 0, '2020-01-08'),
(2, 'test', 0, '2020-01-08');

-- --------------------------------------------------------

--
-- Structure de la table `tracks`
--

DROP TABLE IF EXISTS `tracks`;
CREATE TABLE IF NOT EXISTS `tracks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `file_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `tracks`
--

INSERT INTO `tracks` (`id`, `nom`, `description`, `archived`, `file_id`) VALUES
(1, 'Damso FaisMoiUnVie', 'Single 2018', 0, '1'),
(2, 'Larry - Block', 'Single 2019', 0, '2'),
(3, 'Alkpote - Cicatrices', 'Album Monument', 0, '3'),
(4, 'Alkpote feat Kalash Criminel - Patek', 'Album Monument', 0, '4'),
(5, 'Alpha Wann - Pistolet Rose', 'EP PPP', 0, '5'),
(6, 'Alpha Wann - Pistolet Rose 2', 'EP PPP', 0, '6'),
(7, 'Alpha Wann - PLAYOFFS', 'EP PPP', 0, '7');

-- --------------------------------------------------------

--
-- Structure de la table `track_vinyle`
--

DROP TABLE IF EXISTS `track_vinyle`;
CREATE TABLE IF NOT EXISTS `track_vinyle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vinyle_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `track_vinyle`
--

INSERT INTO `track_vinyle` (`id`, `vinyle_id`, `track_id`) VALUES
(1, 1, 5),
(2, 1, 6),
(3, 1, 7);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(320) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(320) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vinyles`
--

DROP TABLE IF EXISTS `vinyles`;
CREATE TABLE IF NOT EXISTS `vinyles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nom` varchar(75) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shareKey` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prix` decimal(4,2) NOT NULL,
  `creationDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `vinyles`
--

INSERT INTO `vinyles` (`id`, `user_id`, `nom`, `shareKey`, `locked`, `description`, `cover`, `prix`, `creationDate`) VALUES
(1, 3, 'PPP', '', 0, 'EP Alpha Wann', '', '20.00', '2019-09-26'),
(2, 3, 'qsdfqsdf', '', 0, 'EP Alpha Wannqsqdf', '', '13.00', '2019-09-26');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
