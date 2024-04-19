<?php

/**
 * Class permet de manipuler les ventes
 */
class Vente
{

    /**
     * @param int $id_client id client
     * @param int $id_article id article
     * @param int $quantite quantite
     * @param float $prix_uni prix de vente
     * @param int $mode_livraison mode de livraison
     * @return bool L'état d'ajout
     */
    public static function nouvelVente(int $id_client, int $id_article, int $quantite, float $prix_uni, int $mode_livraison): bool
    {
        try {
            $conn = connection();
            //Appelant la procedure de vente
            $stmt = $conn->prepare('CALL vente(:id_client,:id_article, :prix_uni, :mode_livraison, :quantite)');
            $stmt->bindValue(":id_article", $id_article);
            $stmt->bindValue(":prix_uni", $prix_uni);
            $stmt->bindValue(":mode_livraison", $mode_livraison);
            $stmt->bindValue(":quantite", $quantite);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            global $error;
            if ($e->errorInfo[1] == 1452) {
                $error["achat"] = "aucun article ou client avec ce id n'est trouvé";
            }
            return false;
        }
    }

    /**
     * Suppression d'une vente
     * @param int $id_vente id de la vente
     * @return bool L'état de suppression
     */
    public static function deleteVente(int $id_vente): bool
    {
        $conn = connection();
        //Appelant la procedure de suppression
        $stmt = $conn->prepare('CALL delete_vente(:id_vente)');
        $stmt->bindValue(":id_vente", $id_vente);
        $conn = null;
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            global $error;
            $error["delete_ve,te"] = "aucune vente avec ce id n'est trouvé";
            return false;
        }
        return true;
    }
}