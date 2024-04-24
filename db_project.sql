-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2024 at 03:09 PM
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

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `achat`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `achat` (`id_article_param` INT(11), `quantite_param` INT(11), `prix` FLOAT, `id_fournisseur_param` INT)   BEGIN

INSERT INTO achats(`id_article`, `quantite`, `prix_unitaire_ht`,`id_fournisseur`) VALUES(id_article_param, quantite_param, prix,id_fournisseur_param);

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
FROM (SELECT "achat" as 'operation',achats.quantite,prix_unitaire_ht as prix, DATE_FORMAT(date,'%e-%m-%Y %H:%i') as `date_operation`,raison_sociale_fournisseur as `person` FROM achats,article,fournisseur where achats.id_article = id_article_param and article.id_article=achats.id_article and fournisseur.id_fournisseur=achats.id_fournisseur
UNION 
SELECT "vente" as 'operation',vente.quantite,prix_unitaire_ht as prix, DATE_FORMAT(date,'%e-%m-%Y %H:%i')as `date_operation`,raison_sociale_client as `person` FROM vente,article,client WHERE vente.id_article=id_article_param and article.id_article=vente.id_article and client.id_client=vente.id_client
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

DROP FUNCTION IF EXISTS `valeur_stock`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `valeur_stock` () RETURNS VARCHAR(10) CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
DECLARE valeur_stock_var double;
SET  `valeur_stock_var` := (SELECT SUM(quantite*calcul_cmup(id_article)) FROM article);

RETURN  FORMAT(`valeur_stock_var`,2,'de_DE');
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `achats`
--

DROP TABLE IF EXISTS `achats`;
CREATE TABLE `achats` (
  `id_achat` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire_ht` float NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_bons_reception` int(11) DEFAULT NULL,
  `id_fournisseur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id_article` int(11) NOT NULL,
  `reference_article` varchar(255) NOT NULL,
  `designation_article` varchar(255) NOT NULL,
  `prix_achat_unitaire_HT` float DEFAULT NULL,
  `quantite` int(11) DEFAULT 0,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `derniere_modification` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `bons_reception`
--

DROP TABLE IF EXISTS `bons_reception`;
CREATE TABLE `bons_reception` (
  `id_bon` int(11) NOT NULL,
  `id_achat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE `client` (
  `id_client` int(11) NOT NULL,
  `raison_sociale_client` varchar(255) NOT NULL,
  `adresse_client` varchar(255) NOT NULL,
  `email_client` varchar(255) DEFAULT NULL,
  `telephone_client` varchar(10) NOT NULL,
  `n_siren` int(9) NOT NULL,
  `mode_paiement` enum('Cheque','Espece','Virement','Carte') DEFAULT NULL,
  `delai_paiement` int(11) DEFAULT NULL,
  `mode_livraison` enum('Charge_Client','Notre_Charge') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Stand-in structure for view `etat_stock_global`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `etat_stock_global`;
CREATE TABLE `etat_stock_global` (
`reference_article` varchar(255)
,`designation_article` varchar(255)
,`quantite_actuel` int(11)
,`cmup` float
,`valeur_stock` varchar(417)
);

-- --------------------------------------------------------

--
-- Table structure for table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
CREATE TABLE `fournisseur` (
  `id_fournisseur` int(11) NOT NULL,
  `raison_sociale_fournisseur` varchar(255) NOT NULL,
  `adresse_fournisseur` varchar(255) NOT NULL,
  `email_fournisseur` varchar(255) NOT NULL,
  `telephone_fournisseur` varchar(10) NOT NULL,
  `n_siren` int(9) NOT NULL,
  `nom_interlocuteur` varchar(30) NOT NULL,
  `mode_paiement` enum('Cheque','Espece','Virement','Carte') NOT NULL,
  `delai_paiement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `infos_stock`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `infos_stock`;
CREATE TABLE `infos_stock` (
`nb_achats` bigint(21)
,`total_achats` double
,`nb_ventes` bigint(21)
,`total_ventes` decimal(42,0)
,`val_stock` double
,`quantite_stock` decimal(32,0)
,`nb_fournisseurs` bigint(21)
,`nb_clients` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `liste_achats`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `liste_achats`;
CREATE TABLE `liste_achats` (
`id_achat` int(11)
,`reference_article` varchar(255)
,`designation_article` varchar(255)
,`quantite` int(11)
,`prix_unitaire_ht` float
,`id_fournisseur` int(11)
,`raison_sociale_fournisseur` varchar(255)
,`date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `liste_article`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `liste_article`;
CREATE TABLE `liste_article` (
`id_article` int(11)
,`reference_article` varchar(255)
,`quantite` int(11)
,`designation_article` varchar(255)
,`prix_achat_unitaire_HT` float
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `liste_stock`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `liste_stock`;
CREATE TABLE `liste_stock` (
`reference_article` varchar(255)
,`designation_article` varchar(255)
,`prix_article` float
,`quantite_article` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `liste_ventes`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `liste_ventes`;
CREATE TABLE `liste_ventes` (
`id_vente` int(11)
,`id_article` int(11)
,`reference_article` varchar(255)
,`designation_article` varchar(255)
,`quantite` int(11)
,`prix_unitaire_ht` int(11)
,`mode_livraison` enum('Charge_Client','Notre_Charge')
,`date` timestamp
,`id_client` int(11)
,`raison_sociale_client` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
  `id_user` int(11) NOT NULL,
  `username` varchar(24) NOT NULL,
  `passwordHash` char(64) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `vente`
--

DROP TABLE IF EXISTS `vente`;
CREATE TABLE `vente` (
  `id_vente` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire_ht` int(11) NOT NULL,
  `mode_livraison` enum('Charge_Client','Notre_Charge') NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_client` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `etat_stock_global`
--
DROP TABLE IF EXISTS `etat_stock_global`;

DROP VIEW IF EXISTS `etat_stock_global`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `etat_stock_global`  AS SELECT `article`.`reference_article` AS `reference_article`, `article`.`designation_article` AS `designation_article`, `article`.`quantite` AS `quantite_actuel`, `CALCUL_CMUP`(`article`.`id_article`) AS `cmup`, format(`CALCUL_CMUP`(`article`.`id_article`) * `article`.`quantite`,2,'de_DE') AS `valeur_stock` FROM `article` GROUP BY `article`.`id_article` ;

-- --------------------------------------------------------

--
-- Structure for view `infos_stock`
--
DROP TABLE IF EXISTS `infos_stock`;

DROP VIEW IF EXISTS `infos_stock`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `infos_stock`  AS SELECT `infos_achats`.`nb_achats` AS `nb_achats`, `infos_achats`.`total_achats` AS `total_achats`, `infos_ventes`.`nb_ventes` AS `nb_ventes`, `infos_ventes`.`total_ventes` AS `total_ventes`, `infos_articles`.`val_stock` AS `val_stock`, `infos_articles`.`quantite_stock` AS `quantite_stock`, `fournisseurs_infos`.`nb_fournisseurs` AS `nb_fournisseurs`, `clients_infos`.`nb_clients` AS `nb_clients` FROM (((((select count(0) AS `nb_achats`,coalesce(sum(`achats`.`quantite` * `achats`.`prix_unitaire_ht`),0) AS `total_achats` from `achats`) `infos_achats` join (select count(0) AS `nb_ventes`,coalesce(sum(`vente`.`quantite` * `vente`.`prix_unitaire_ht`),0) AS `total_ventes` from `vente`) `infos_ventes`) join (select coalesce(sum(`article`.`quantite` * `article`.`prix_achat_unitaire_HT`),0) AS `val_stock`,coalesce(sum(`article`.`quantite`),0) AS `quantite_stock` from `article`) `infos_articles`) join (select count(0) AS `nb_fournisseurs` from `fournisseur`) `fournisseurs_infos`) join (select count(0) AS `nb_clients` from `client`) `clients_infos`) ;

-- --------------------------------------------------------

--
-- Structure for view `liste_achats`
--
DROP TABLE IF EXISTS `liste_achats`;

DROP VIEW IF EXISTS `liste_achats`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `liste_achats`  AS SELECT `achats`.`id_achat` AS `id_achat`, `article`.`reference_article` AS `reference_article`, `article`.`designation_article` AS `designation_article`, `achats`.`quantite` AS `quantite`, `achats`.`prix_unitaire_ht` AS `prix_unitaire_ht`, `achats`.`id_fournisseur` AS `id_fournisseur`, `fournisseur`.`raison_sociale_fournisseur` AS `raison_sociale_fournisseur`, `achats`.`date` AS `date` FROM ((`achats` join `article`) join `fournisseur`) WHERE `achats`.`id_article` = `article`.`id_article` AND `achats`.`id_fournisseur` = `fournisseur`.`id_fournisseur` ;

-- --------------------------------------------------------

--
-- Structure for view `liste_article`
--
DROP TABLE IF EXISTS `liste_article`;

DROP VIEW IF EXISTS `liste_article`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `liste_article`  AS SELECT `article`.`id_article` AS `id_article`, `article`.`reference_article` AS `reference_article`, `article`.`quantite` AS `quantite`, `article`.`designation_article` AS `designation_article`, `article`.`prix_achat_unitaire_HT` AS `prix_achat_unitaire_HT` FROM `article` ;

-- --------------------------------------------------------

--
-- Structure for view `liste_stock`
--
DROP TABLE IF EXISTS `liste_stock`;

DROP VIEW IF EXISTS `liste_stock`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `liste_stock`  AS SELECT `article`.`reference_article` AS `reference_article`, `article`.`designation_article` AS `designation_article`, `article`.`prix_achat_unitaire_HT` AS `prix_article`, `article`.`quantite` AS `quantite_article` FROM `article` ;

-- --------------------------------------------------------

--
-- Structure for view `liste_ventes`
--
DROP TABLE IF EXISTS `liste_ventes`;

DROP VIEW IF EXISTS `liste_ventes`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `liste_ventes`  AS SELECT `vente`.`id_vente` AS `id_vente`, `vente`.`id_article` AS `id_article`, `article`.`reference_article` AS `reference_article`, `article`.`designation_article` AS `designation_article`, `article`.`quantite` AS `quantite`, `vente`.`prix_unitaire_ht` AS `prix_unitaire_ht`, `vente`.`mode_livraison` AS `mode_livraison`, `vente`.`date` AS `date`, `vente`.`id_client` AS `id_client`, `client`.`raison_sociale_client` AS `raison_sociale_client` FROM ((`vente` join `article`) join `client`) WHERE `article`.`id_article` = `vente`.`id_article` AND `client`.`id_client` = `vente`.`id_client` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achats`
--
ALTER TABLE `achats`
  ADD PRIMARY KEY (`id_achat`),
  ADD KEY `FK_achats_id_article` (`id_article`),
  ADD KEY `FK_achats_id_bon_reception` (`id_bons_reception`),
  ADD KEY `FK_achats_id_fournisseur` (`id_fournisseur`);

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`),
  ADD UNIQUE KEY `designation_article` (`designation_article`),
  ADD UNIQUE KEY `reference_article` (`reference_article`);

--
-- Indexes for table `bons_reception`
--
ALTER TABLE `bons_reception`
  ADD PRIMARY KEY (`id_bon`),
  ADD KEY `FK_bons_reception_id_achats` (`id_achat`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `raison_sociale_client` (`raison_sociale_client`) USING BTREE,
  ADD UNIQUE KEY `n_siren` (`n_siren`);

--
-- Indexes for table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id_fournisseur`),
  ADD UNIQUE KEY `raison_sociale_fournisseur` (`raison_sociale_fournisseur`),
  ADD UNIQUE KEY `n_siren` (`n_siren`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vente`
--
ALTER TABLE `vente`
  ADD PRIMARY KEY (`id_vente`),
  ADD KEY `FK_vente_id_article` (`id_article`),
  ADD KEY `FK_vente_id_client` (`id_client`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achats`
--
ALTER TABLE `achats`
  MODIFY `id_achat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id_article` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `bons_reception`
--
ALTER TABLE `bons_reception`
  MODIFY `id_bon` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vente`
--
ALTER TABLE `vente`
  MODIFY `id_vente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achats`
--
ALTER TABLE `achats`
  ADD CONSTRAINT `FK_achats_id_article` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`),
  ADD CONSTRAINT `FK_achats_id_bon_reception` FOREIGN KEY (`id_bons_reception`) REFERENCES `bons_reception` (`id_bon`),
  ADD CONSTRAINT `FK_achats_id_fournisseur` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`);

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
