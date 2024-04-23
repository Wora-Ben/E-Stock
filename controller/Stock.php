<?php

use model\StockModel;

require(dirname(__DIR__, 1) . '/model/StockModel.php');

/**
 * Class permet de récupérer les informations concernant le stock
 */
class Stock
{
    /**
     * Function renvoie des informations concernant le stock
     * @return bool|array renvoie un tableau des informations, sinon false en cas d'échec
     */
    public static function stockInfos(): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM infos_stock');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_NUM)[0];
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["listeStock"] = "Erreur";
            return false;
        }
    }

    /**
     * searchVente cherche dans le stock par la référence d'un article ou par sa désignation
     * @param string $search mots de recherche
     * @return false|mixed renvoie un tableau d'objet de résultat de recherche, sinon False en cas d'échec
     */
    public static function searchStock(string $search): mixed
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM liste_stock WHERE reference_article LIKE :refArticle OR designation_article LIKE :desAr');
            $stmt->bindValue(":desAr", $search . '%');
            $stmt->bindValue(":refArticle", $search . '%');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, StockModel::class);
        } catch (PDOException $e) {
            global $error;
            $error["searchVente"] = "Aucune vente n'est trouvé";
            return false;
        }
    }

    /**
     * Function renvoie la liste du stock
     * @return bool|array renvoie un tableau des articles du stock, sinon false en cas d'échec
     */
    public static function listeStock(): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM liste_stock');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, StockModel::class);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["listeStock"] = "Erreur";
            return false;
        }
    }

    /**
     * Function renvoie l'état du stock général
     * @return bool|array renvoie un tableau des articles, sinon false en cas d'échec
     */
    public static function etatDeStockGlobal(): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM etat_stock_global');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["etatStockGlobal"] = "Erreur";
            return false;
        }
    }

    /**
     * Function renvoie l'état du stock par article
     * @param int $id_article id de l'article
     * @return bool|array renvoie un tableau des mouvements du stock de cet article, sinon false en cas d'échec
     */
    public static function etatDeStockArticle(int $id_article): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('CALL etat_stock_article(:id_article)');
            $stmt->bindParam(":id_article", $id_article);
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["listeStockArticle"] = "Erreur";
            return false;
        }
    }

    /**
     * Function la valeur du stock actuel
     * @return bool|array renvoie un tableau de résultat, sinon false en cas d'échec
     */
    public static function valeurStock(): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT valeur_stock()');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_NUM)[0];
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["valeurStock"] = "Erreur";
            return false;
        }
    }
}