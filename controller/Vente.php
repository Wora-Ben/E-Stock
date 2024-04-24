<?php

/**
 * Class permet de manipuler les ventes
 */
class Vente
{
    /**
     * ventesInfo renvoie des informations concernant les ventes
     * @return bool|array renvoie un tableau des informations, sinon false en cas d'échec
     */
    public static function ventesInfos(): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM liste_ventes');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, VenteModel::class);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["ventesInfos"] = "Erreur";
            return false;
        }
    }

    /**
     * searchVente cherche une vente par la référence d'un article ou par sa désignation dans la liste des ventes
     * @param string $search mots de recherche
     * @return array|bool renvoie un tableau d'objet de résultat de recherche, sinon False en cas d'échec
     */
    public static function searchVente(string $search): array|bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM liste_ventes WHERE reference_article LIKE :refArticle OR designation_article LIKE :desAr');
            $stmt->bindValue(":desAr", $search . '%');
            $stmt->bindValue(":refArticle", $search . '%');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, VenteModel::class);
        } catch (PDOException $e) {
            global $error;
            $error["searchVente"] = "aucune vente n'est trouvé";
            return false;
        }
    }

    /**
     * Chercher une vente par son id
     * @param int $id_vente id de la vente
     * @return false|mixed Renvoie un objet trouvé avec ce id, sinon False en cas d'échec
     */
    public static function getVenteById(int $id_vente): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM liste_ventes WHERE id_vente=:idVente');
            $stmt->bindValue(":idVente", $id_vente, PDO::PARAM_INT);
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, VenteModel::class);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["searchVente"] = "aucune vente avec ce id n'est trouvé";
            return false;
        }
    }

    /**
     * @param int $id_client id client
     * @param int $id_article id article
     * @param int $quantite quantite
     * @param float $prix_uni prix de vente
     * @param string $mode_livraison mode de livraison
     * @return bool L'état d'ajout
     */
    public static function nouvelVente(int $id_client, int $id_article, int $quantite, float $prix_uni, string $mode_livraison): bool
    {
        try {
            $conn = connection();
            //Appelant la procedure de vente
            $stmt = $conn->prepare('CALL vente(:id_client,:id_article, :prix_uni, :mode_livraison, :quantite)');
            $stmt->bindValue(":id_client", $id_client, PDO::PARAM_INT);
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
            $error["deleteVente"] = "aucune vente avec ce id n'est trouvé";
            return false;
        }
        return true;
    }
}