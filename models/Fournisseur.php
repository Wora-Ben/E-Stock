<?php
// require dirname(__DIR__,1) . 'services';

/**
 * Class pour manipuler les fournisseurs
 */
class Fournisseur
{
    /**
     * Chercher un fournisseur par sa raison social
     * @param string $raison_sociale raison sociale du fournisseur
     * @return false|mixed renvoie un tableau d'objet des fournisseurs trouvé avec cette raison sociale, sinon False en cas d'échec
     */
    public static function getFournisseurByRaisonSociale(string $raison_sociale)
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM fournisseur WHERE raison_sociale_fournisseur=:rc');
            $stmt->bindValue(":rc", $raison_sociale);
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["searchFournisseur"] = "aucun fournisseur avec cette raison social n'est trouvé";
            return false;
        }

    }

    /**
     * Chercher un fournisseur par son numéro de siren
     * @param int $siren numéro de siren du fournisseur
     * @return false|mixed renvoie un objet de résultat, sinon False en cas d'échec
     */
    public static function getFournisseurBySiren(int $siren)
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM fournisseur WHERE n_siren=:siren');
            $stmt->bindValue(":siren", $siren, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $conn = null;
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            global $error;
            $error["searchFournisseur"] = "aucun fournisseur avec cette raison social n'est trouvé";
            return false;
        }
    }


    /**
     * Fonction créer un nouveau fournisseur
     * @param string $raison_sociale raison sociale du fournisseur
     * @param string $adresse adresse fournisseur
     * @param string $email email fournisseur
     * @param string $telephone telephone fournisseur
     * @param int $siren siren du fournisseur
     * @param int $mode_paiement mode de paiement fournisseur
     * @param int $delai_paiement delai du paiement pour fournisseur
     * @return bool etat de la creation
     */
    public
    static function addFournisseur(string $raison_sociale, string $adresse, string $email, string $telephone, int $siren, string $nom_interlocuteur, int $mode_paiement, int $delai_paiement): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('INSERT INTO fournisseur(raison_sociale_fournisseur,adresse_fournisseur,email_fournisseur,telephone_fournisseur,n_siren,nom_interlocuteur,mode_paiement,delai_paiement) VALUES(:rc,:adresse,:email,:telephone,:siren,:nom_interlocuteur,:mode_paiement,:delai_paiement)');
            $stmt->bindValue(":rc", $raison_sociale);
            $stmt->bindValue(":adresse", $adresse);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":telephone", $telephone);
            $stmt->bindValue(":siren", $siren, PDO::PARAM_INT);
            $stmt->bindValue(":nom_interlocuteur", $nom_interlocuteur);
            $stmt->bindValue(":mode_paiement", $mode_paiement, PDO::PARAM_INT);
            $stmt->bindValue(":delai_paiement", $delai_paiement, PDO::PARAM_INT);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            print_r($e->errorInfo);
            if ($e->errorInfo[1] === 1062) {
                global $error;
                $error["addClient"] = "numero de siren ou raison social d'entreprise existe déja";
            }
            return false;
        }
    }

    /**
     * @param int $id_fournisseur identifiant du fournisseur
     * @param string $raison_sociale nouvelle raison sociale du fournisseur
     * @param string $adresse nouvelle adresse du fournisseur
     * @param string $email nouvel email fournisseur
     * @param string $telephone nouveau numero de telephone fournisseur
     * @param int $siren nouveau numero siren fournisseur
     * @param int $mode_paiement mode de paiement fournisseur
     * @param int $delai_paiement delai de paiement fournisseur
     * @return bool L'etat de modification
     */
    public
    static function modifyFournisseur(int $id_fournisseur, string $raison_sociale, string $adresse, string $email, string $telephone, int $siren, string $nom_interlocuteur, int $mode_paiement, int $delai_paiement): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('UPDATE fournisseur 
            SET raison_sociale_fournisseur=:rc, adresse_fournisseur=:adresse, email_fournisseur=:email,telephone_fournisseur=:telephone, n_siren=:siren, nom_interlocuteur=:nom_interlocuteur,mode_paiement=:mode_paiement, delai_paiement=:delai_paiement WHERE id_fournisseur=:id_fournisseur');
            $stmt->bindValue(":rc", $raison_sociale);
            $stmt->bindValue(":adresse", $adresse);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":telephone", $telephone);
            $stmt->bindValue(":siren", $siren, PDO::PARAM_INT);
            $stmt->bindValue(":nom_interlocuteur", $nom_interlocuteur);
            $stmt->bindValue(":mode_paiement", $mode_paiement, PDO::PARAM_INT);
            $stmt->bindValue(":delai_paiement", $delai_paiement, PDO::PARAM_INT);
            $stmt->bindValue(":id_fournisseur", $id_fournisseur, PDO::PARAM_INT);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                global $error;
                $error["modifyClient"] = "numero de siren ou raison social d'entreprise existe déja";
            }
            return false;
        }
    }

    /**
     * Supprime un fournisseur
     * @param int $id_fournisseur id fournisseur
     * @return bool etat de suppression
     */
    public
    static function deleteFournisseur(int $id_fournisseur): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('DELETE FROM fournisseur WHERE id_fournisseur=:id_fournisseur');
            $stmt->bindValue(":id_fournisseur", $id_fournisseur, PDO::PARAM_INT);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            //Exist FOREIGN_KEY relatif a ce fournisseur
            if ($e->errorInfo[1] === 1451) {
                global $error;
                $error["deleteClient"] = "Impossible de supprimer le fournisseur, car il existe des etats relative a ce fournisseur";
            }
            return false;
        }
    }
}

?>