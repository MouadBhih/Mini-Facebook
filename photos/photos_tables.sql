-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Dimanche 01 Avril 2007 à 19:11
-- Version du serveur: 5.0.19
-- Version de PHP: 5.1.4
-- 
-- Base de données: `photos`
-- 

-- --------------------------------------------------------
USE photos;
-- 
-- Structure de la table `commentaire`
-- 

CREATE TABLE `commentaire` (
  `id` int(11) NOT NULL auto_increment,
  `contenu` text collate latin1_general_cs NOT NULL,
  `depot` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `id_photo` int(11) NOT NULL,
  `auteur` varchar(128) collate latin1_general_cs NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_comm_photo` (`id_photo`),
  KEY `fk_comm_util` (`auteur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Contient des informations sur les commentaires des photos' AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `commentaire`
-- 

INSERT INTO `commentaire` VALUES (1, 'Quel regard charmeur !!!', '2007-04-01 11:14:54', 2, 'Titi');
INSERT INTO `commentaire` VALUES (2, 'Elle me fait craquer :)', '2007-04-01 11:20:50', 2, 'Zorglub');
INSERT INTO `commentaire` VALUES (3, 'C\\''est cool', '2007-04-01 13:18:01', 4, 'Titi');

-- --------------------------------------------------------

-- 
-- Structure de la table `photo`
-- 

CREATE TABLE `photo` (
  `id` int(11) NOT NULL auto_increment,
  `fichier` varchar(255) collate latin1_general_cs NOT NULL,
  `date_photo` date NOT NULL,
  `description` text collate latin1_general_cs NOT NULL,
  `proprietaire` varchar(128) collate latin1_general_cs NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_photo_utilisateur` (`proprietaire`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Contient les informations sur les photos' AUTO_INCREMENT=13 ;

-- 
-- Contenu de la table `photo`
-- 

INSERT INTO `photo` VALUES (2, 'photos/Zorglub/IMG_2014.JPG', '2006-07-11', 'Ma biquette préférée\r\n	', 'Zorglub');
INSERT INTO `photo` VALUES (4, 'photos/Titi/IMG_TITI0.JPG', '2006-06-17', 'C\\''est mon copain !', 'Titi');
INSERT INTO `photo` VALUES (5, 'photos/Titi/miam.jpg', '2006-08-04', 'Miam !!!\r\n	', 'Titi');

-- --------------------------------------------------------

-- 
-- Structure de la table `utilisateur`
-- 

CREATE TABLE `utilisateur` (
  `login` varchar(128) collate latin1_general_cs NOT NULL,
  `password` varchar(32) collate latin1_general_cs NOT NULL,
  PRIMARY KEY  (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Contient les informations de base sur les utilisateurs';

-- 
-- Contenu de la table `utilisateur`
-- 

INSERT INTO `utilisateur` VALUES ('Titi', 'canari88');
INSERT INTO `utilisateur` VALUES ('Zorglub', 'bulgroz');

-- 
-- Contraintes pour les tables exportées
-- 

-- 
-- Contraintes pour la table `commentaire`
-- 
ALTER TABLE `commentaire`
  ADD CONSTRAINT `fk_comm_util` FOREIGN KEY (`auteur`) REFERENCES `utilisateur` (`login`),
  ADD CONSTRAINT `fk_comm_photo` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id`);

-- 
-- Contraintes pour la table `photo`
-- 
ALTER TABLE `photo`
  ADD CONSTRAINT `fk_photo_utilisateur` FOREIGN KEY (`proprietaire`) REFERENCES `utilisateur` (`login`);
