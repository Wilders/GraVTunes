-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mer. 08 jan. 2020 à 16:46
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
  `statut` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `user_id`, `total`, `statut`, `creationDate`) VALUES
(1, 1, 20, 'fini', '2020-01-08 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `commandes_contient`
--

CREATE TABLE `commandes_contient` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `vinyle_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `commandes_contient`
--

INSERT INTO `commandes_contient` (`id`, `commande_id`, `vinyle_id`) VALUES
(1, 1, 1);

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
(7, 'AW_pistoletrose2.mp3', 'ad0fc4983e2d76283d8dae8fb1e6e2eb', 98);

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
-- Structure de la table `playlists_contient`
--

CREATE TABLE `playlists_contient` (
  `id` int(11) NOT NULL,
  `playlist_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `playlists_contient`
--

INSERT INTO `playlists_contient` (`id`, `playlist_id`, `track_id`) VALUES
(1, 1, 5),
(2, 1, 6),
(3, 1, 7),
(4, 2, 3),
(5, 2, 4);

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `objet` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `statut` tinyint(1) NOT NULL,
  `creationDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `tracks` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `tracks`
--

INSERT INTO `tracks` (`id`, `nom`, `description`, `file_id`) VALUES
(1, 'Damso FaisMoiUnVie', 'Single 2018', '1'),
(2, 'Larry - Block', 'Single 2019', '2'),
(3, 'Alkpote - Cicatrices', 'Album Monument', '3'),
(4, 'Alkpote feat Kalash Criminel - Patek', 'Album Monument', '4'),
(5, 'Alpha Wann - Pistolet Rose', 'EP PPP', '5'),
(6, 'Alpha Wann - Pistolet Rose 2', 'EP PPP', '6'),
(7, 'Alpha Wann - PLAYOFFS', 'EP PPP', '7');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(320) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `nom`, `prenom`, `adresse`, `role`) VALUES
(1, 'totoprnt', 'anthony.pernot@hotmail.fr', 'PERNOT', 'Anthony', '8 rue Lothaire II', 2),
(2, 'lapins54', 'maxoulegendre@hotmail.fr', 'LEGENDRE', 'Maxens', '10 rue Villers 54000 Nancy', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users_collabore`
--

CREATE TABLE `users_collabore` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vinyle_id` int(11) NOT NULL,
  `role` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `users_collabore`
--

INSERT INTO `users_collabore` (`id`, `user_id`, `vinyle_id`, `role`) VALUES
(1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `users_invitations`
--

CREATE TABLE `users_invitations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vinyle_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `users_invitations`
--

INSERT INTO `users_invitations` (`id`, `user_id`, `vinyle_id`) VALUES
(1, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `users_possede`
--

CREATE TABLE `users_possede` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `users_possede`
--

INSERT INTO `users_possede` (`id`, `user_id`, `track_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7);

-- --------------------------------------------------------

--
-- Structure de la table `vinyles`
--

CREATE TABLE `vinyles` (
  `id` int(11) NOT NULL,
  `nom` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prix` decimal(4,2) NOT NULL,
  `creationDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `vinyles`
--

INSERT INTO `vinyles` (`id`, `nom`, `description`, `prix`, `creationDate`) VALUES
(1, 'PPP', 'EP Alpha Wann', '20.00', '2019-09-26');

-- --------------------------------------------------------

--
-- Structure de la table `vinyles_contient`
--

CREATE TABLE `vinyles_contient` (
  `id` int(11) NOT NULL,
  `vinyle_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `vinyles_contient`
--

INSERT INTO `vinyles_contient` (`id`, `vinyle_id`, `track_id`) VALUES
(1, 1, 5),
(2, 1, 6),
(3, 1, 7);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commandes_contient`
--
ALTER TABLE `commandes_contient`
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
-- Index pour la table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `playlists_contient`
--
ALTER TABLE `playlists_contient`
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
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users_collabore`
--
ALTER TABLE `users_collabore`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users_invitations`
--
ALTER TABLE `users_invitations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users_possede`
--
ALTER TABLE `users_possede`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `vinyles`
--
ALTER TABLE `vinyles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `vinyles_contient`
--
ALTER TABLE `vinyles_contient`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commandes_contient`
--
ALTER TABLE `commandes_contient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `playlists_contient`
--
ALTER TABLE `playlists_contient`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users_collabore`
--
ALTER TABLE `users_collabore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users_invitations`
--
ALTER TABLE `users_invitations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users_possede`
--
ALTER TABLE `users_possede`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `vinyles`
--
ALTER TABLE `vinyles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `vinyles_contient`
--
ALTER TABLE `vinyles_contient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
