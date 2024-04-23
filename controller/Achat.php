<?php

use model\AchatModel;

require (dirname(__DIR__).'/model/AchatModel.php');

/**
 * Class permet de manipuler les achats
 */
class Achat
{


    /**
     * achatsInfo renvoie des informations concernant les achats
     * @return bool|array renvoie un tableau des informations, sinon false en cas d'échec
     */
    public static function achatsInfos(): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM liste_achats');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS,AchatModel::class);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["achatsInfos"] = "Erreur";
            return false;
        }
    }

    /**
     * searchAchat cherche un achat par la référence d'un article ou par sa désignation dans la liste des achats
     * @param string $search mots de recherche
     * @return false|mixed renvoie un tableau d'objet de résultat de recherche, sinon False en cas d'échec
     */
    public static function searchAchat(string $search): mixed
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM liste_achats WHERE reference_article LIKE :refArticle OR designation_article LIKE :desAr');
            $stmt->bindValue(":desAr", $search . '%');
            $stmt->bindValue(":refArticle", $search .'%' );
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, AchatModel::class);
        } catch (PDOException $e) {
            global $error;
            $error["searchAchat"] = "aucune vente n'est trouvé";
            return false;
        }
    }

    /**
     * Chercher une achat par son id
     * @param int $id_achat id de l'achat
     * @return false|mixed Renvoie un objet trouvé avec ce id, sinon False en cas d'échec
     */
    public static function getAchatById(int $id_achat): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM liste_achats WHERE id_achat=:idAchat');
            $stmt->bindValue(":idAchat", $id_achat, PDO::PARAM_INT);
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS,AchatModel::class);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["searchAchat"] = "aucun achat avec ce id n'est trouvé";
            return false;
        }
    }

    /**
     * Nouvel achat
     * @param int $id_article id article
     * @param int $quantite quantite
     * @param float $prix_uni prix d'achat
     * @return bool L'état d'ajout
     */
    public static function nouvelAchat(int $id_article, int $quantite, float $prix_uni,int $id_fournisseur): bool
    {
        try {
            $conn = connection();
            //Appelant la procedure achat
            $stmt = $conn->prepare('CALL achat(:id_article, :quantite, :prix_uni, :id_fournisseur)');
            $stmt->bindValue(":id_article", $id_article);
            $stmt->bindValue(":quantite", $quantite);
            $stmt->bindValue(":prix_uni", $prix_uni);
            $stmt->bindValue(":id_fournisseur", $id_fournisseur);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            global $error;
            if ($e->errorInfo[1] == 1452) {
                $error["achat"] = "aucun article avec ce id n'est trouvé";
            }
            return false;
        }
    }

    /**
     * Suppression d'un achat
     * @param int $id_achat id de l'achat
     * @return bool L'état de suppression
     */
    public static function deleteAchat(int $id_achat): bool
    {
        $conn = connection();
        //Appelant la procedure de suppression
        $stmt = $conn->prepare('CALL delete_achat(:id_achat)');
        $stmt->bindValue(":id_achat", $id_achat);
        $conn = null;
        $stmt->execute();
        if ($stmt->rowCount()===0) {
            global $error;
            $error["delete_achat"] = "aucun achat avec ce id n'est trouvé";
            return false;
        }
        return true;
    }
}