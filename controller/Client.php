<?php

use model\ClientModel;

include(dirname(__DIR__) . '/model/ClientModel.php');

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
            return $stmt->fetchAll(PDO::FETCH_CLASS, ClientModel::class);
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
     * @return array|bool renvoie un tableau d'objet de résultat de recherche, sinon False en cas d'échec
     */
    public static function searchClient(string $search): array|bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('SELECT * FROM client WHERE raison_sociale_client LIKE :rcClient OR n_siren = :siren');
            $stmt->bindValue(":rcClient", $search . '%');
            $stmt->bindValue(":siren", $search);
            $conn = null;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, ClientModel::class);
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
            return $stmt->fetchAll(PDO::FETCH_CLASS, ClientModel::class);
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
     * @param string $mode_paiement mode de paiement client
     * @param int $delai_paiement delai du paiement pour client
     * @param string $mode_livraison mode de livraison du client
     * @return bool
     */
    public static function addClient(string $raison_sociale, string $adresse, string $email, string $telephone, int $siren, string $mode_paiement, int $delai_paiement, string $mode_livraison): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare('INSERT INTO client(raison_sociale_client,adresse_client,email_client,telephone_client,n_siren,mode_paiement,delai_paiement,mode_livraison) VALUES(:rc,:adresse,:email,:telephone,:siren,:mode_paiement,:delai_paiement,:mode_livraison)');
            $stmt->bindValue(":rc", $raison_sociale);
            $stmt->bindValue(":adresse", $adresse);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":telephone", $telephone);
            $stmt->bindValue(":siren", $siren, PDO::PARAM_INT);
            $stmt->bindValue(":mode_paiement", $mode_paiement);
            $stmt->bindValue(":delai_paiement", $delai_paiement, PDO::PARAM_INT);
            $stmt->bindValue(":mode_livraison", $mode_livraison);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                global $error;
                $error["addClient"] = "Numéro de siren ou raison social d'entreprise existe déja";
            }
            return false;
        }
    }

    /**
     * @param int $id_client identifiant du client
     * @param string $raison_sociale nouvelle raison sociale du client
     * @param string $adresse nouvelle adresse du client
     * @param string $email nouvel email client
     * @param string $telephone nouveau numéro de telephone client
     * @param int $siren nouveau numéro siren client
     * @param string $mode_paiement mode de paiement client
     * @param int $delai_paiement delai de paiement client
     * @param string $mode_livraison mode de livraison client
     * @return bool L'état de modification
     */
    public static function modifyClient(int $id_client, string $raison_sociale, string $adresse, string $email, string $telephone, int $siren, string $mode_paiement, int $delai_paiement, string $mode_livraison): bool
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
            $stmt->bindValue(":mode_paiement", $mode_paiement);
            $stmt->bindValue(":delai_paiement", $delai_paiement, PDO::PARAM_INT);
            $stmt->bindValue(":mode_livraison", $mode_livraison);
            $stmt->bindValue(":id_client", $id_client, PDO::PARAM_INT);
            $conn = null;
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                global $error;
                $error["modifyClient"] = "Numéro de siren ou raison social d'entreprise existe déja";
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
                $error["deleteClient"] = "Impossible de supprimer le client, car il existe des états relative a ce client";
            }
            return false;
        }
    }
}

?>