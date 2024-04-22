<?php

/**
 * Class pour manipuler le client
 */
class Client
{
    /**
     * clientsInfos renvoie des informations concernant les clients
     * @return bool|array renvoie un tableau des informations, sinon false en cas d'échec
     */
    public static function clientsInfos(): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM client');
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["clientsInfos"] = "Erreur";
            return false;
        }
    }

    /**
     * searchClient chercher un client dans la liste des clients
     * @param string $search mots de recherche
     * @return false|mixed renvoie un tableau d'objet de résultat de recherche, sinon False en cas d'échec
     */
    public static function searchClient(string $search): mixed
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM client WHERE raison_sociale_client LIKE :rcClient OR n_siren = :siren');
            $stmt->bindValue(":rcClient", $search . '%');
            $stmt->bindValue(":siren", $search );
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            global $error;
            $error["searchClient"] = "aucun client avec cette raison sociale ou n°siren n'est trouvé";
            return false;
        }
    }

    /**
     * Chercher un client par son id
     * @param int $id_client id de client
     * @return false|mixed Renvoie un objet trouvé avec ce id, sinon False en cas d'échec
     */
    public static function getClientById(int $id_client): bool|array
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM client WHERE id_client=:idClient');
            $stmt->bindValue(":idClient", $id_client);
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS,ClientModel::class);
        } catch (PDOException $e) {
            echo $e->getMessage();
            global $error;
            $error["searchClient"] = "aucun client avec ce id n'est trouvé";
            return false;
        }
    }

    /**
     * Fonction créer un nouveau client
     * @param string $raison_sociale raison sociale du client
     * @param string $adresse adresse client
     * @param string $email email client
     * @param string $telephone telephone client
     * @param int $siren siren du client
     * @param int $mode_paiement mode de paiement client
     * @param int $delai_paiement delai du paiement pour client
     * @param int $mode_livraison mode de livraison du client
     * @return bool
     */
    public static function addClient(string $raison_sociale, string $adresse, string $email, string $telephone, int $siren, int $mode_paiement, int $delai_paiement, int $mode_livraison): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('INSERT INTO client(raison_sociale_client,adresse_client,email_client,telephone_client,n_siren,mode_paiement,delai_paiement,mode_livraison) VALUES(:rc,:adresse,:email,:telephone,:siren,:mode_paiement,:delai_paiement,:mode_livraison)');
            $stmt->bindValue(":rc", $raison_sociale);
            $stmt->bindValue(":adresse", $adresse);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":telephone", $telephone);
            $stmt->bindValue(":siren", $siren, PDO::PARAM_INT);
            $stmt->bindValue(":mode_paiement", $mode_paiement, PDO::PARAM_INT);
            $stmt->bindValue(":delai_paiement", $delai_paiement, PDO::PARAM_INT);
            $stmt->bindValue(":mode_livraison", $mode_livraison, PDO::PARAM_INT);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                global $error;
                $error["addClient"] = "numero de siren ou raison social d'entreprise existe déja";
            }
            return false;
        }
    }

    /**
     * @param int $id_client identifiant du client
     * @param string $raison_sociale nouvelle raison sociale du client
     * @param string $adresse nouvelle adresse du client
     * @param string $email nouveau email client
     * @param string $telephone nouveau numero de telephone client
     * @param int $siren nouveau numero siren client
     * @param int $mode_paiement mode de paiement client
     * @param int $delai_paiement delai de paiement client
     * @param int $mode_livraison mode de livraison client
     * @return bool L'etat de modification
     */
    public static function modifyClient(int $id_client, string $raison_sociale, string $adresse, string $email, string $telephone, int $siren, int $mode_paiement, int $delai_paiement, int $mode_livraison): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('UPDATE client 
            SET raison_sociale_client=:rc, adresse_client=:adresse, email_client=:email,telephone_client=:telephone, n_siren=:siren, mode_paiement=:mode_paiement, delai_paiement=:delai_paiement, mode_livraison=:mode_livraison WHERE id_client=:id_client');
            $stmt->bindValue(":rc", $raison_sociale);
            $stmt->bindValue(":adresse", $adresse);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":telephone", $telephone);
            $stmt->bindValue(":siren", $siren, PDO::PARAM_INT);
            $stmt->bindValue(":mode_paiement", $mode_paiement, PDO::PARAM_INT);
            $stmt->bindValue(":delai_paiement", $delai_paiement, PDO::PARAM_INT);
            $stmt->bindValue(":mode_livraison", $mode_livraison, PDO::PARAM_INT);
            $stmt->bindValue(":id_client", $id_client, PDO::PARAM_INT);
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
     * Supprime un client
     * @param int $id_client id client
     * @return bool etat de suppression
     */
    public static function deleteClient(int $id_client): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('DELETE FROM client WHERE id_client=:id_client');
            $stmt->bindValue(":id_client", $id_client, PDO::PARAM_INT);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            //Exist FOREIGN_KEY relatif a ce client
            if ($e->errorInfo[1] === 1451) {
                global $error;
                $error["deleteClient"] = "Impossible de supprimer le client, car il existe des etats relative a ce client";
            }
            return false;
        }
    }
}

?>