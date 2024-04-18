-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2024 at 07:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_project`
--
CREATE DATABASE IF NOT EXISTS `db_project` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_project`;

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `achat` (`id_article_param` INT(11), `quantite_param` INT(11), `prix` FLOAT)   BEGIN
INSERT INTO achats(`id_article`, `quantite`, `prix_unitaire_ht`) VALUES(id_article_param, quantite_param, prix);

UPDATE `article` SET `quantite` = quantite_param, `prix_achat_unitaire_HT` = prix WHERE `id_article` = id_article_param;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `achats`
--

CREATE TABLE IF NOT EXISTS `achats` (
  `id_achat` int(11) NOT NULL AUTO_INCREMENT,
  `id_article` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire_ht` float NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_bons_reception` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_achat`),
  KEY `FK_achats_id_article` (`id_article`),
  KEY `FK_achats_id_bon_reception` (`id_bons_reception`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int(11) NOT NULL AUTO_INCREMENT,
  `reference_article` varchar(255) NOT NULL,
  `designation_article` varchar(255) NOT NULL,
  `prix_achat_unitaire_HT` float DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `derniere_modification` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `id_fournisseur` int(11) NOT NULL,
  PRIMARY KEY (`id_article`),
  UNIQUE KEY `designation_article` (`designation_article`),
  UNIQUE KEY `reference_article` (`reference_article`),
  KEY `FK_id_fournisseur` (`id_fournisseur`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bons_reception`
--

CREATE TABLE IF NOT EXISTS `bons_reception` (
  `id_bon` int(11) NOT NULL AUTO_INCREMENT,
  `id_achat` int(11) NOT NULL,
  PRIMARY KEY (`id_bon`),
  KEY `FK_bons_reception_id_achats` (`id_achat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int(11) NOT NULL AUTO_INCREMENT,
  `raison_sociale_client` varchar(255) NOT NULL,
  `adresse_client` varchar(255) NOT NULL,
  `email_client` varchar(255) DEFAULT NULL,
  `telephone_client` varchar(10) NOT NULL,
  `n_siren` int(9) NOT NULL,
  `mode_paiement` enum('Cheque','Espece','Virement','Carte') DEFAULT NULL,
  `delai_paiement` int(11) DEFAULT NULL,
  `mode_livraison` enum('Charge_Client','Notre_Charge') NOT NULL,
  PRIMARY KEY (`id_client`),
  UNIQUE KEY `raison_sociale_client` (`raison_sociale_client`) USING BTREE,
  UNIQUE KEY `n_siren` (`n_siren`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- --------------------------------------------------------

--
-- Table structure for table `fournisseur`
--

CREATE TABLE IF NOT EXISTS `fournisseur` (
  `id_fournisseur` int(11) NOT NULL AUTO_INCREMENT,
  `raison_sociale_fournisseur` varchar(255) NOT NULL,
  `adresse_fournisseur` varchar(255) NOT NULL,
  `email_fournisseur` varchar(255) NOT NULL,
  `telephone_fournisseur` varchar(10) NOT NULL,
  `n_siren` int(9) NOT NULL,
  `nom_interlocuteur` varchar(30) NOT NULL,
  `mode_paiement` enum('Cheque','Espece','Virement','Carte') NOT NULL,
  `delai_paiement` int(11) NOT NULL,
  PRIMARY KEY (`id_fournisseur`),
  UNIQUE KEY `raison_sociale_fournisseur` (`raison_sociale_fournisseur`),
  UNIQUE KEY `n_siren` (`n_siren`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `passwordHash` char(64) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vente`
--

CREATE TABLE IF NOT EXISTS `vente` (
  `id_vente` int(11) NOT NULL AUTO_INCREMENT,
  `id_article` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire_ht` int(11) NOT NULL,
  `mode_livraison` enum('Charge_Client','Notre_Charge','','') NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_client` int(11) NOT NULL,
  PRIMARY KEY (`id_vente`),
  KEY `FK_vente_id_article` (`id_article`),
  KEY `FK_vente_id_client` (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achats`
--
ALTER TABLE `achats`
  ADD CONSTRAINT `FK_achats_id_article` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`),
  ADD CONSTRAINT `FK_achats_id_bon_reception` FOREIGN KEY (`id_bons_reception`) REFERENCES `bons_reception` (`id_bon`);

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_id_fournisseur` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`);

--
-- Constraints for table `bons_reception`
--
ALTER TABLE `bons_reception`
  ADD CONSTRAINT `FK_bons_reception_id_achats` FOREIGN KEY (`id_achat`) REFERENCES `achats` (`id_achat`);

--
-- Constraints for table `vente`
--
ALTER TABLE `vente`
  ADD CONSTRAINT `FK_vente_id_article` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`),
  ADD CONSTRAINT `FK_vente_id_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
