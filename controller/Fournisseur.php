<?php
include (dirname(__DIR__).'/model/FournisseurModel.php');

/**
 * Class pour manipuler les fournisseurs
 */
class Fournisseur
{
    /**
     * fournisseursInfos renvoie des informations concernant les fournisseurs
     * @return bool|array renvoie un tableau des informations, sinon false en cas d'échec
     */
    public static function fournisseursInfos(): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM fournisseur');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS,FournisseurModel::class);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["fournisseurInfos"] = "Erreur";
            return false;
        }
    }

    /**
     * searchFournisseur chercher un fournisseur dans la liste des fournisseurs
     * @param string $search mots de recherche
     * @return false|mixed renvoie un tableau d'objet de résultat de recherche, sinon False en cas d'échec
     */
    public static function searchFournisseur(string $search): mixed
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM fournisseur WHERE raison_sociale_client LIKE :rcClient OR n_siren = :siren');
            $stmt->bindValue(":rcClient", $search . '%');
            $stmt->bindValue(":siren", $search );
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, FournisseurModel::class);
        } catch (PDOException $e) {
            global $error;
            $error["searchClient"] = "aucun fournisseur avec cette raison sociale ou n°siren n'est trouvé";
            return false;
        }
    }

    /**
     * Chercher un fournisseur par son id
     * @param int $id_fournisseur id de fournisseur
     * @return false|mixed Renvoie un objet trouvé avec ce id, sinon False en cas d'échec
     */
    public static function getFournisseurById(int $id_fournisseur): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM fournisseur WHERE id_fournisseur=:idFournisseur');
            $stmt->bindValue(":idFournisseur", $id_fournisseur);
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS,FournisseurModel::class);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["searchFournisseur"] = "aucun fournisseur avec ce id n'est trouvé";
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
     * @param string $mode_paiement mode de paiement fournisseur
     * @param int $delai_paiement delai du paiement pour fournisseur
     * @return bool etat de la creation
     */
    public
    static function addFournisseur(string $raison_sociale, string $adresse, string $email, string $telephone, int $siren, string $nom_interlocuteur, string $mode_paiement, int $delai_paiement): bool
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
            $stmt->bindValue(":mode_paiement", $mode_paiement);
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
     * @param string $mode_paiement mode de paiement fournisseur
     * @param int $delai_paiement delai de paiement fournisseur
     * @return bool L'etat de modification
     */
    public
    static function modifyFournisseur(int $id_fournisseur, string $raison_sociale, string $adresse, string $email, string $telephone, int $siren, string $nom_interlocuteur, string $mode_paiement, int $delai_paiement): bool
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
            $stmt->bindValue(":mode_paiement", $mode_paiement);
            $stmt->bindValue(":delai_paiement", $delai_paiement, PDO::PARAM_INT);
            $stmt->bindValue(":id_fournisseur", $id_fournisseur, PDO::PARAM_INT);
            echo "$mode_paiement";
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