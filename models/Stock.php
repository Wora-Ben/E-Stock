<?php

/**
 * Class permet de récupérer les informations concernant le stock
 */
class Stock
{

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
            return $stmt->fetchAll(PDO::FETCH_OBJ);
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
            return $stmt->fetchAll(PDO::FETCH_OBJ);
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
}