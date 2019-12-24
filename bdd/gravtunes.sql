-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 24 déc. 2019 à 12:21
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
-- Structure de la table `collab`
--

CREATE TABLE `collab` (
  `idUser` int(4) NOT NULL,
  `idVinyle` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `collab`
--

INSERT INTO `collab` (`idUser`, `idVinyle`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `idUser` int(4) NOT NULL,
  `idVinyle` int(4) NOT NULL,
  `montant` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`idUser`, `idVinyle`, `montant`) VALUES
(1, 1, '12.00');

-- --------------------------------------------------------

--
-- Structure de la table `fichier`
--

CREATE TABLE `fichier` (
  `idFichier` int(4) NOT NULL,
  `nomFichier` varchar(70) COLLATE utf8_bin NOT NULL,
  `link` text COLLATE utf8_bin NOT NULL,
  `idUser` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `fichier`
--

INSERT INTO `fichier` (`idFichier`, `nomFichier`, `link`, `idUser`) VALUES
(1, 'Intro', 'public/sound/intro.mp3', 1),
(2, 'CASCADE REMIX ', 'public/sound/cascaderemix.mp3', 1);

-- --------------------------------------------------------

--
-- Structure de la table `playlist`
--

CREATE TABLE `playlist` (
  `idPlaylist` int(4) NOT NULL,
  `nomPlaylist` varchar(70) COLLATE utf8_bin NOT NULL,
  `description` varchar(200) COLLATE utf8_bin NOT NULL,
  `datecreation` date NOT NULL,
  `idUser` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `playlist`
--

INSERT INTO `playlist` (`idPlaylist`, `nomPlaylist`, `description`, `datecreation`, `idUser`) VALUES
(1, 'playlist - FC PBB', 'tous les morceaux de l\'album Projet Blue Beam', '2018-11-13', 1),
(2, 'playlist - UMLA Alpha Wann', 'album UMLA', '2018-09-21', 1);

-- --------------------------------------------------------

--
-- Structure de la table `possedefp`
--

CREATE TABLE `possedefp` (
  `idPlaylist` int(4) NOT NULL,
  `idFichier` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='table pour les relations entre fichiers et playlists';

--
-- Déchargement des données de la table `possedefp`
--

INSERT INTO `possedefp` (`idPlaylist`, `idFichier`) VALUES
(1, 1),
(2, 2),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `ticket`
--

CREATE TABLE `ticket` (
  `numTicket` int(4) NOT NULL,
  `objet` varchar(120) COLLATE utf8_bin NOT NULL,
  `message` varchar(5000) COLLATE utf8_bin NOT NULL,
  `avis` varchar(5000) COLLATE utf8_bin NOT NULL,
  `idUser` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `ticket`
--

INSERT INTO `ticket` (`numTicket`, `objet`, `message`, `avis`, `idUser`) VALUES
(1, 'test', 'test', 'test', 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int(4) NOT NULL,
  `nom` varchar(40) COLLATE utf8_bin NOT NULL,
  `prenom` varchar(40) COLLATE utf8_bin NOT NULL,
  `sexe` varchar(1) COLLATE utf8_bin NOT NULL,
  `datenaiss` date NOT NULL,
  `adresse` varchar(100) COLLATE utf8_bin NOT NULL,
  `statut` varchar(40) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `nom`, `prenom`, `sexe`, `datenaiss`, `adresse`, `statut`) VALUES
(1, 'PERNOT', 'Anthony', 'M', '2000-03-28', '8 rue Lothaire II 54000 Nancy - France', 'administrateur'),
(2, 'GRANDGIRARD', 'Yvan', 'M', '1998-12-04', '23 rue du gros chêne 54210 Saint Nicolas de Port - France', 'client');

-- --------------------------------------------------------

--
-- Structure de la table `vinyle`
--

CREATE TABLE `vinyle` (
  `idVinyle` int(4) NOT NULL,
  `nomVinyle` varchar(120) COLLATE utf8_bin NOT NULL,
  `description` varchar(500) COLLATE utf8_bin NOT NULL,
  `typeVinyle` varchar(120) COLLATE utf8_bin NOT NULL,
  `datecreation` date NOT NULL,
  `prix` decimal(4,2) NOT NULL,
  `idPlaylist` int(4) NOT NULL,
  `idUser` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `vinyle`
--

INSERT INTO `vinyle` (`idVinyle`, `nomVinyle`, `description`, `typeVinyle`, `datecreation`, `prix`, `idPlaylist`, `idUser`) VALUES
(1, 'Freeze Corleone - PBB', 'Projet Blue Beam est le premier album de Freeze Corleone. Il est précédé de deux mixtapes : THC en 2017 et F.D.T en 2016.\r\n\r\nUne fois de plus , il regorge de références dont la principale, qui est celle au sujet du Projet Blue Beam (Rayon Bleu), qui est un projet de la NASA à travers lequel ces-derniers voudraient manipuler les gens afin d\'imposer une nouvelle “religion mondiale”. Elle serait la seule à “apaiser, réconcilier et mettre tout le monde d\'accord” .', 'solo', '2018-11-13', '12.00', 1, 1),
(2, 'Alpha Wann - UMLA', 'Une main lave l\'autre, également abrégé par le nom U.M.L.A, est le premier album studio du rappeur parisien Alpha Wann, sorti le 21 septembre 2018, près de six mois après la sortie de l\'EP Alph Lauren 3.', 'solo', '2018-09-21', '13.00', 2, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `collab`
--
ALTER TABLE `collab`
  ADD KEY `FK_collab_iduser` (`idUser`),
  ADD KEY `FK_collab_idvinyle` (`idVinyle`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD KEY `FK_commande_iduser` (`idUser`),
  ADD KEY `FK_commande_idvinyle` (`idVinyle`);

--
-- Index pour la table `fichier`
--
ALTER TABLE `fichier`
  ADD PRIMARY KEY (`idFichier`),
  ADD KEY `FK_fichier_iduser` (`idUser`);

--
-- Index pour la table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`idPlaylist`),
  ADD KEY `FK_playlist_iduser` (`idUser`);

--
-- Index pour la table `possedefp`
--
ALTER TABLE `possedefp`
  ADD KEY `FK_possedefp_idplaylist` (`idPlaylist`),
  ADD KEY `FK_possedefp_idfichier` (`idFichier`);

--
-- Index pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`numTicket`),
  ADD KEY `FK_ticket_iduser` (`idUser`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- Index pour la table `vinyle`
--
ALTER TABLE `vinyle`
  ADD PRIMARY KEY (`idVinyle`),
  ADD KEY `FK_collaboration_iduser` (`idUser`),
  ADD KEY `FK_vinyle_idplaylist` (`idPlaylist`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `fichier`
--
ALTER TABLE `fichier`
  MODIFY `idFichier` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `idPlaylist` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `numTicket` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `vinyle`
--
ALTER TABLE `vinyle`
  MODIFY `idVinyle` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `collab`
--
ALTER TABLE `collab`
  ADD CONSTRAINT `FK_collab_iduser` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`),
  ADD CONSTRAINT `FK_collab_idvinyle` FOREIGN KEY (`idVinyle`) REFERENCES `vinyle` (`idVinyle`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_commande_iduser` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`),
  ADD CONSTRAINT `FK_commande_idvinyle` FOREIGN KEY (`idVinyle`) REFERENCES `vinyle` (`idVinyle`);

--
-- Contraintes pour la table `fichier`
--
ALTER TABLE `fichier`
  ADD CONSTRAINT `FK_fichier_iduser` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `playlist`
--
ALTER TABLE `playlist`
  ADD CONSTRAINT `FK_playlist_iduser` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `possedefp`
--
ALTER TABLE `possedefp`
  ADD CONSTRAINT `FK_possedefp_idfichier` FOREIGN KEY (`idFichier`) REFERENCES `fichier` (`idFichier`),
  ADD CONSTRAINT `FK_possedefp_idplaylist` FOREIGN KEY (`idPlaylist`) REFERENCES `playlist` (`idPlaylist`);

--
-- Contraintes pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `FK_ticket_iduser` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `vinyle`
--
ALTER TABLE `vinyle`
  ADD CONSTRAINT `FK_collaboration_iduser` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`),
  ADD CONSTRAINT `FK_vinyle_idplaylist` FOREIGN KEY (`idPlaylist`) REFERENCES `playlist` (`idPlaylist`),
  ADD CONSTRAINT `FK_vinyle_iduser` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
