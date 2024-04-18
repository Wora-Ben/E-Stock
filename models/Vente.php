<?php

class Vente
{

    /**
     * @param int $id_article id article
     * @param int $quantite quantite
     * @param float $prix_uni prix de vente
     * @param int $mode_livraison mode de livraison
     * @param int $id_client id client
     * @return bool L'état d'ajout
     */
    public static function nouvelVente(int $id_article, int $quantite, float $prix_uni,int $mode_livraison,int $id_client): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('INSERT INTO vente(id_article, quantite, prix_unitaire_ht, mode_livraison,id_client) VALUES(:id_article, :quantite, :prix_uni, :mode_livraison, :id_client)');
            $stmt->bindValue(":id_article", $id_article);
            $stmt->bindValue(":quantite", $quantite);
            $stmt->bindValue(":prix_uni", $prix_uni);
            $stmt->bindValue(":mode_livraison", $mode_livraison);
            $stmt->bindValue(":id_client", $id_client);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            global $error;
            if($e->errorInfo[1]==1452){
                $error["achat"] = "aucun article ou client avec ce id n'est trouvé";
            }
            return false;
        }

    }

    /**
     * Suppression d'une vente
     * @param int $id_achat id de la vente
     * @return bool L'état de suppression
     */
    public static function deleteAchat(int $id_vente): bool
    {
        $conn = connection();
        $stmt = $conn->prepare('DELETE FROM vente WHERE id_vente = :id_vente');
        $stmt->bindValue(":id_vente", $id_vente);
        $conn = null;
        $stmt->execute();
        if ($stmt->rowCount()  == 0) {
            $error["achat"] = "aucun article ou client avec ce id n'est trouvé";
            return false;
        }
        return true;
    }

}