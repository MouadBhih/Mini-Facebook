-- phpMyAdmin SQL Dump
-- version 3.2.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 01 Décembre 2010 à 01:32
-- Version du serveur: 5.1.37
-- Version de PHP: 5.2.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `photos`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE IF NOT EXISTS `commentaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contenu` text COLLATE latin1_general_cs NOT NULL,
  `depot` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_photo` int(11) NOT NULL,
  `auteur` varchar(128) COLLATE latin1_general_cs NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comm_photo` (`id_photo`),
  KEY `fk_comm_util` (`auteur`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Contient des informations sur les commentaires des photos' AUTO_INCREMENT=11 ;

--
-- Contenu de la table `commentaire`
--

INSERT INTO `commentaire` (`id`, `contenu`, `depot`, `id_photo`, `auteur`) VALUES
(1, 'Quel regard charmeur !!!', '2010-11-29 11:14:54', 2, 'Titi'),
(2, 'Elle me fait craquer :)', '2007-04-01 11:20:50', 2, 'Zorglub'),
(3, 'C\\''est cool', '2007-04-01 13:18:01', 4, 'Titi'),
(4, 'trés belle toff', '2010-11-28 22:48:03', 13, 'Titi'),
(5, 'sanfour', '2010-11-29 00:03:07', 14, 'Titi'),
(6, 'na3ess', '2010-11-30 21:57:02', 14, 'Titi'),
(7, 'freedom', '2010-12-01 00:07:26', 15, 'wbouarifi'),
(8, 'superbe ', '2010-12-01 00:07:50', 2, 'wbouarifi'),
(9, 'schizophrène ', '2010-12-01 00:10:22', 14, 'wbouarifi'),
(10, 'Jolie coupe ', '2010-12-01 00:31:24', 2, 'wbouarifi');

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fichier` varchar(255) COLLATE latin1_general_cs NOT NULL,
  `date_photo` date NOT NULL,
  `description` text COLLATE latin1_general_cs NOT NULL,
  `proprietaire` varchar(128) COLLATE latin1_general_cs NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_photo_utilisateur` (`proprietaire`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Contient les informations sur les photos' AUTO_INCREMENT=18 ;

--
-- Contenu de la table `photo`
--

INSERT INTO `photo` (`id`, `fichier`, `date_photo`, `description`, `proprietaire`) VALUES
(2, 'photos/Zorglub/IMG_2014.JPG', '2010-11-30', 'Ma biquette préférée\r\n	', 'Zorglub'),
(4, 'photos/Titi/IMG_TITI0.JPG', '2006-06-17', 'C\\''est mon copain !', 'Titi'),
(5, 'photos/Titi/miam.jpg', '2010-11-29', 'Miam !!!\r\n	', 'Titi'),
(13, 'photos/Titi/2962.imgcache.jpg', '2010-11-28', 'super	', 'Titi'),
(14, 'photos/Titi/sanfour.gif', '2010-11-29', 'Entrez la description de la photo ici.\r\n	', 'Titi'),
(15, 'photos/wbouarifi/0,,10269~3002552,00.jpg', '2010-12-01', 'le retour ... !!!!!!!	', 'wbouarifi'),
(16, 'photos/wbouarifi/25333_107169065988943_106604682712048_54767_8290034_n.jpg', '2010-12-01', 'c''est facile les math	', 'wbouarifi'),
(17, 'photos/wbouarifi/Nadal Djoko.jpg', '2010-12-01', 'Le duel	', 'wbouarifi');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `login` varchar(128) COLLATE latin1_general_cs NOT NULL,
  `password` varchar(32) COLLATE latin1_general_cs NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Contient les informations de base sur les utilisateurs';

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`login`, `password`) VALUES
('Titi', 'canari88'),
('wbouarifi', '123456'),
('Zorglub', 'bulgroz');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `fk_comm_photo` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id`),
  ADD CONSTRAINT `fk_comm_util` FOREIGN KEY (`auteur`) REFERENCES `utilisateur` (`login`);

--
-- Contraintes pour la table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `fk_photo_utilisateur` FOREIGN KEY (`proprietaire`) REFERENCES `utilisateur` (`login`);
