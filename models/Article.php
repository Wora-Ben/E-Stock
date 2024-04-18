<?php

/**
 * @author BEN DAOU
 * @package models
 */
class Article
{
    /**
     * Chercher un article par sa designation
     * @param string $raison_sociale raison sociale du client
     * @return false|mixed renvoie un tableau d'objet des clients trouvé avec cette raison sociale, sinon False en cas d'échec
     */
    public static function getArticleByDesignation(string $designation_article): mixed
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM article WHERE designation_article LIKE :designation_article');
            $stmt->bindValue(":designation_article", '%' . $designation_article . '%');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["searchFournisseur"] = "aucun article avec cette designation n'est trouvé";
            return false;
        }

    }

    /**
     * Chercher un article par sa référence
     * @param string $reference_article référence article
     * @return false|mixed renvoie un tableau d'objet des articles trouvé avec cette référence, sinon False en cas d'échec
     */
    public static function getArticleByReference(string $reference_article): mixed
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM article WHERE reference_article LIKE :ref');
            $stmt->bindValue(":ref", $reference_article . "%");
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            global $error;
            $error["searchFournisseur"] = "aucun article avec cette référence n'est trouvé";
            return false;
        }
    }

    /**
     * Fonction créer un nouvel article
     * @param string $reference_article référence article
     * @param string $designation_article designation article
     * @param int $id_fournisseur id fournisseur
     * @param float $prix_un_ht prix unitaire hors taxe
     * @param int $quantite quantite
     * @return bool état de la creation
     */
    public static function addArticle(string $reference_article, string $designation_article, int $id_fournisseur, float $prix_un_ht, int $quantite): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('INSERT INTO article(reference_article, designation_article, prix_achat_unitaire_HT, quantite, id_fournisseur) VALUES(:ref, :design, :prix, :quantite, :id_fournisseur)');
            $stmt->bindValue(":ref", $reference_article);
            $stmt->bindValue(":design", $designation_article);
            $stmt->bindValue(":prix", $prix_un_ht);
            $stmt->bindValue(":quantite", $quantite, PDO::PARAM_INT);
            $stmt->bindValue(":id_fournisseur", $id_fournisseur, PDO::PARAM_INT);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                global $error;
                $error["addArticle"] = "La designation ou la référence déja existante.";
            }
            return false;
        }
    }

    /**
     * Fonction modifier un article
     * @param int $id_article id article
     * @param string $reference_article reference client
     * @param string $designation_article designation client
     * @param int $id_fournisseur id fournisseur
     * @param float $prix_un_ht prix unitaire hors taxe
     * @param int $quantite quantite
     * @return bool état de la modification
     */
    public static function modifyArticle(int $id_article, string $reference_article, string $designation_article, int $id_fournisseur, float $prix_un_ht, int $quantite): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('UPDATE article SET reference_article=:ref, designation_article=:des, id_fournisseur=:id_fournisseur,prix_achat_unitaire_HT=:prix, quantite=:quantite WHERE id_article=:id_article');
            $stmt->bindValue(":id_article", $id_article, PDO::PARAM_INT);
            $stmt->bindValue(":ref", $reference_article);
            $stmt->bindValue(":des", $designation_article);
            $stmt->bindValue(":id_fournisseur", $id_fournisseur, PDO::PARAM_INT);
            $stmt->bindValue(":prix", $prix_un_ht);
            $stmt->bindValue(":quantite", $quantite, PDO::PARAM_INT);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                global $error;
                $error["modifyArticle"] = "La designation ou la référence déja existante.";
            }
            return false;
        }
    }

    /**
     * Suppression d'un article
     * @param int $id_article id article
     * @return bool état de la suppression
     */
    public static function deleteArticle(int $id_article): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('DELETE FROM article WHERE id_article=:id_article');
            $stmt->bindValue(":id_article", $id_article, PDO::PARAM_INT);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1451) {
                global $error;
                $error["deleteArticle"] = "Impossible de supprimer l'article, car il existe des etats relative a cette article";
            }
            return false;
        }
    }
}

?>