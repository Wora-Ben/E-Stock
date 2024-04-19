-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2024 at 05:50 PM
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
DROP PROCEDURE IF EXISTS `achat`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `achat` (`id_article_param` INT(11), `quantite_param` INT(11), `prix` FLOAT)   BEGIN

INSERT INTO achats(`id_article`, `quantite`, `prix_unitaire_ht`) VALUES(id_article_param, quantite_param, prix);

UPDATE `article` SET `quantite` = `quantite`+quantite_param, `prix_achat_unitaire_HT` = prix WHERE `id_article` = id_article_param;
END$$

DROP PROCEDURE IF EXISTS `delete_achat`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_achat` (`id_achat_param` INT)   BEGIN

DECLARE quantite_achat INT;
DECLARE id_article_achat INT;

SELECT @id_article_achat:=`id_article`,@quantite_achat:=`quantite` FROM achats WHERE `id_achat`=id_achat_param;

UPDATE article SET `quantite`=`quantite` - @quantite_achat WHERE `id_article` = @id_article_achat;

DELETE FROM achats WHERE `id_achat`=id_achat_param;
END$$

DROP PROCEDURE IF EXISTS `delete_vente`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_vente` (`id_vente_param` INT)   BEGIN

DECLARE quantite_vente INT;
DECLARE id_article_vente INT;

SELECT @id_article_vente:=`id_article`,@quantite_vente:=`quantite` FROM vente WHERE `id_vente`=id_vente_param;

UPDATE article SET `quantite`=`quantite` + @quantite_vente WHERE `id_article` = @id_article_vente;

DELETE FROM vente WHERE `id_vente`=id_vente_param;
END$$

DROP PROCEDURE IF EXISTS `etat_stock_article`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `etat_stock_article` (`id_article_param` INT)   BEGIN
SELECT * 
FROM (SELECT "achat" as 'operation',reference_article,designation_article,achats.quantite,prix_unitaire_ht as prix, DATE_FORMAT(date,'%e-%m-%Y %H:%i') as `date_operation`,raison_sociale_fournisseur as `Fournisseur / Client` FROM achats,article,fournisseur where achats.id_article = id_article_param and article.id_article=achats.id_article and fournisseur.id_fournisseur=article.id_fournisseur
UNION 
SELECT "vente" as 'operation',reference_article,designation_article,vente.quantite,prix_unitaire_ht as prix, DATE_FORMAT(date,'%e-%m-%Y %H:%i')as `date_operation`,raison_sociale_client as `Fournisseur / Client` FROM vente,article,client WHERE vente.id_article=id_article_param and article.id_article=vente.id_article and client.id_client=vente.id_client
) results order by `date_operation` asc;

END$$

DROP PROCEDURE IF EXISTS `vente`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `vente` (`id_client` INT, `id_article_param` INT(11), `prix` FLOAT, `mode_livraison_param` ENUM('Charge_Client','Notre_Charge'), `quantite_param` INT(11))   BEGIN

INSERT INTO vente(`id_article`, `quantite`, `prix_unitaire_ht`,`mode_livraison`,`id_client`) VALUES(id_article_param, quantite_param, prix, mode_livraison_param, id_client);

UPDATE `article` SET `quantite` = `quantite`-quantite_param WHERE `id_article` = id_article_param;
END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS `calcul_cmup`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `calcul_cmup` (`id_article_param` INT) RETURNS FLOAT  BEGIN
DECLARE cmup FLOAT;
SET cmup := (SELECT SUM(prix_unitaire_ht*quantite)/SUM(quantite) as CMUP FROM achats WHERE achats.id_article=id_article_param);
RETURN cmup;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `achats`
--

DROP TABLE IF EXISTS `achats`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int(11) NOT NULL AUTO_INCREMENT,
  `reference_article` varchar(255) NOT NULL,
  `designation_article` varchar(255) NOT NULL,
  `prix_achat_unitaire_HT` float DEFAULT NULL,
  `quantite` int(11) DEFAULT 0,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `derniere_modification` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `id_fournisseur` int(11) NOT NULL,
  PRIMARY KEY (`id_article`),
  UNIQUE KEY `designation_article` (`designation_article`),
  UNIQUE KEY `reference_article` (`reference_article`),
  KEY `FK_id_fournisseur` (`id_fournisseur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bons_reception`
--

DROP TABLE IF EXISTS `bons_reception`;
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

DROP TABLE IF EXISTS `client`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `etat_stock_global`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `etat_stock_global`;
CREATE TABLE IF NOT EXISTS `etat_stock_global` (
`reference_article` varchar(255)
,`designation_article` varchar(255)
,`quantite_actuel` int(11)
,`CMUP` float
,`valeur stock` double
);

-- --------------------------------------------------------

--
-- Table structure for table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `list_stock`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `list_stock`;
CREATE TABLE IF NOT EXISTS `list_stock` (
`reference_article` varchar(255)
,`designation_article` varchar(255)
,`prix_article` float
,`quantite_article` int(11)
,`raison_sociale_fournisseur` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `passwordHash` char(64) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vente`
--

DROP TABLE IF EXISTS `vente`;
CREATE TABLE IF NOT EXISTS `vente` (
  `id_vente` int(11) NOT NULL AUTO_INCREMENT,
  `id_article` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire_ht` int(11) NOT NULL,
  `mode_livraison` enum('Charge_Client','Notre_Charge') NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_client` int(11) NOT NULL,
  PRIMARY KEY (`id_vente`),
  KEY `FK_vente_id_article` (`id_article`),
  KEY `FK_vente_id_client` (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `etat_stock_global`
--
DROP TABLE IF EXISTS `etat_stock_global`;

DROP VIEW IF EXISTS `etat_stock_global`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `etat_stock_global`  AS SELECT `article`.`reference_article` AS `reference_article`, `article`.`designation_article` AS `designation_article`, `article`.`quantite` AS `quantite_actuel`, `calcul_cmup`(`article`.`id_article`) AS `CMUP`, `calcul_cmup`(`article`.`id_article`) * `article`.`quantite` AS `valeur stock` FROM `article` GROUP BY `article`.`id_article` ;

-- --------------------------------------------------------

--
-- Structure for view `list_stock`
--
DROP TABLE IF EXISTS `list_stock`;

DROP VIEW IF EXISTS `list_stock`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `list_stock`  AS SELECT `article`.`reference_article` AS `reference_article`, `article`.`designation_article` AS `designation_article`, `article`.`prix_achat_unitaire_HT` AS `prix_article`, `article`.`quantite` AS `quantite_article`, `fournisseur`.`raison_sociale_fournisseur` AS `raison_sociale_fournisseur` FROM (`article` join `fournisseur`) WHERE `fournisseur`.`id_fournisseur` = `article`.`id_fournisseur` ;

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
