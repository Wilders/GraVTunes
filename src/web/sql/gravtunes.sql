-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 03 fév. 2020 à 20:08
-- Version du serveur :  10.4.6-MariaDB
-- Version de PHP :  7.3.9

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

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` float NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `statut` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `commande_vinyle` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `vinyle_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duree` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
(7, 'AW_pistoletrose2.mp3', 'ad0fc4983e2d76283d8dae8fb1e6e2eb', 98),
(8, 'Trophée OLD.mp3', '769f2841dc7a1b982776b816e150ec50bf17b183b7a41c4e', 0),
(9, 'Jean Teh.mp3', '11f37b5d2378946e32ac21edbea85581eca6cd6563b6e204', 0),
(10, 'Baraccuda.mp3', 'cb5cc73e237755f6967927d92e4939a7ff20a8dfd3eefbb6', 0),
(13, 'Berflam.mp3', '6955c632c285c2e17f3bc3a1be9c0293f84371f9e4536d9b', 0);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(3000) COLLATE utf8_unicode_ci NOT NULL,
  `creationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `paiements` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `success` tinyint(1) NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `playlists`
--

CREATE TABLE `playlists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nom` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `playlist_track` (
  `id` int(11) NOT NULL,
  `playlist_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `objet` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `statut` tinyint(1) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id`, `objet`, `statut`, `updated_at`, `created_at`, `user_id`) VALUES
(1, 'test', 0, '2020-01-07 23:00:00', '2020-02-24 12:19:45', 14),
(2, 'test', 0, '2020-01-07 23:00:00', '2020-02-24 12:19:45', 14),
(3, 'new test', 0, '2020-02-24 11:20:59', '2020-02-24 11:20:59', 14),
(4, 'new test', 0, '2020-02-24 11:21:34', '2020-02-24 11:21:34', 14),
(5, 'AH OUI OUI OUI', 0, '2020-02-24 11:31:56', '2020-02-24 11:31:56', 14);

-- --------------------------------------------------------

--
-- Structure de la table `tracks`
--

CREATE TABLE `tracks` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `file_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `tracks`
--

INSERT INTO `tracks` (`id`, `nom`, `description`, `archived`, `file_id`, `user_id`) VALUES
(1, 'Damso FaisMoiUnVie', 'Single 2018', 0, '1', 0),
(2, 'Larry - Block', 'Single 2019', 0, '2', 0),
(3, 'Alkpote - Cicatrices', 'Album Monument', 0, '3', 0),
(4, 'Alkpote feat Kalash Criminel - Patek', 'Album Monument', 0, '4', 0),
(5, 'Alpha Wann - Pistolet Rose', 'EP PPP', 0, '5', 0),
(6, 'Alpha Wann - Pistolet Rose 2', 'EP PPP', 0, '6', 0),
(7, 'Alpha Wann - PLAYOFFS', 'EP PPP', 0, '7', 0),
(8, 'trophée', 'nqnt3', 0, '1', 0),
(9, 'xxwxw', 'xxxx', 0, '1', 0),
(10, 'dds', 'dfs', 0, '11', 0);

-- --------------------------------------------------------

--
-- Structure de la table `track_vinyle`
--

CREATE TABLE `track_vinyle` (
  `id` int(11) NOT NULL,
  `vinyle_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
CREATE TABLE IF NOT EXISTS `users`
(
    `id`          int(11)                                                 NOT NULL AUTO_INCREMENT,
    `pseudo`      varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL,
    `email`       varchar(320) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `password`    varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL,
    `nom`         varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL,
    `prenom`      varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL,
    `avatar`      varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default.jpg',
    `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci         DEFAULT NULL,
    `public`      tinyint(1)                                              NOT NULL DEFAULT '0',
    `address`     varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `role`        int(11)                                                 NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 14
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Structure de la table `vinyles`
--

CREATE TABLE `vinyles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nom` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `shareKey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cover` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prix` decimal(4,2) NOT NULL,
  `creationDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `vinyles`
--

INSERT INTO `vinyles` (`id`, `user_id`, `nom`, `shareKey`, `locked`, `description`, `cover`, `prix`, `creationDate`) VALUES
(1, 3, 'PPP', '', 0, 'EP Alpha Wann', '', '20.00', '2019-09-26'),
(2, 3, 'qsdfqsdf', '', 0, 'EP Alpha Wannqsqdf', '', '13.00', '2019-09-26');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commande_vinyle`
--
ALTER TABLE `commande_vinyle`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `playlist_track`
--
ALTER TABLE `playlist_track`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `track_vinyle`
--
ALTER TABLE `track_vinyle`
  ADD PRIMARY KEY (`id`);


--
-- Index pour la table `vinyles`
--
ALTER TABLE `vinyles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `commande_vinyle`
--
ALTER TABLE `commande_vinyle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `playlist_track`
--
ALTER TABLE `playlist_track`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `track_vinyle`
--
ALTER TABLE `track_vinyle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `vinyles`
--
ALTER TABLE `vinyles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
