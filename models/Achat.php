<?php

/**
 * Class permet de manipuler les achats
 */
class Achat
{

    /**
     * Nouvel achat
     * @param int $id_article id article
     * @param int $quantite quantite
     * @param float $prix_uni prix d'achat
     * @return bool L'état d'ajout
     */
    public static function nouvelAchat(int $id_article, int $quantite, float $prix_uni): bool
    {
        try {
            $conn = connection();
            //Appelant la procedure achat
            $stmt = $conn->prepare('CALL achat(:id_article, :quantite, :prix_uni)');
            $stmt->bindValue(":id_article", $id_article);
            $stmt->bindValue(":quantite", $quantite);
            $stmt->bindValue(":prix_uni", $prix_uni);
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