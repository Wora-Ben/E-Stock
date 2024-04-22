<?php
global $info;
include(dirname(__DIR__, 1) . '/services/database/connection.php');
include(dirname(__DIR__, 1) . '/models/Client.php');
const PAGE_MAX_ROWS = 15;

if (isset($_GET['search']) && !empty($_GET['searchValue'])) {
    $infos = Client::searchClient(htmlspecialchars($_GET['searchValue']));
} else {
    $infos = Client::clientsInfos();
}

if (isset($_GET['addClient'])) {
    $request = "enregistrerNvClient";
    if (isset($_POST['enregistrerNvClient'])) {
        if (validateForm()) {
            if (Client::addClient(htmlspecialchars($_POST["raisonScClient"]), htmlspecialchars($_POST["adrClient"]), htmlspecialchars($_POST["emailClient"]), htmlspecialchars($_POST["telClient"]), htmlspecialchars($_POST["telClient"]), htmlspecialchars($_POST["nSirenClient"]), htmlspecialchars($_POST["modePaiement"]), htmlspecialchars($_POST["delaiPaiement"]), htmlspecialchars($_POST["modeLivraison"]))) {
                header('Location: clients.php?newClient=true');
            }
        }
    }
    includeForm();
    exit();
}

if (isset($_POST['saveEdits'])) {
    $request = 'saveEdits';
    $info=Client::getClientById(htmlspecialchars($_POST['idClient']))[0];
    if (validateForm()) {
        if (Client::modifyClient(htmlspecialchars($_POST["idClient"]), htmlspecialchars($_POST["raisonScClient"]), htmlspecialchars($_POST["adrClient"]), htmlspecialchars($_POST["emailClient"]), htmlspecialchars($_POST["telClient"]), htmlspecialchars($_POST["nSirenClient"]), htmlspecialchars($_POST["modePaiement"]), htmlspecialchars($_POST["delaiPaiement"]), htmlspecialchars($_POST["modeLivraison"]))) {
            header('Location: clients.php?clientEdited=true');
        }
    }
    $info->id_client = htmlspecialchars($_POST['idClient']);
    $info->raison_sociale_client = htmlspecialchars($_POST['raisonScClient']);
    $info->adresse_client = htmlspecialchars($_POST["adrClient"]);
    $info->email_client = htmlspecialchars($_POST["emailClient"]);
    $info->telephone_client = htmlspecialchars($_POST["telClient"]);
    $info->n_siren = htmlspecialchars($_POST["nSirenClient"]);
    $info->mode_paiement = htmlspecialchars($_POST["modePaiement"]);
    $info->delai_paiement = htmlspecialchars($_POST["delaiPaiement"]);
    $info->mode_livraison = htmlspecialchars($_POST["modeLivraison"]);
    includeForm();
    exit();
}

if (isset($_GET['editClient'])) {
    $request = 'saveEdits';
    $info = Client::getClientById($_POST['idClient'])[0];
    includeForm();
    exit();
}

if (isset($_GET['deleteClient'])) {
    $request = 'confirmDelete';
    $info = Client::getClientById($_POST['idClient'])[0];
    include(dirname(__DIR__, 1) . '/template/panel/client/client-delete-template.php');
    exit();
}

if (isset($_POST["confirmDelete"]) && Client::deleteClient($_POST['idClient'])) {
    header('Location: articles.php?clientDeleted=true');
}
//Intégrer la liste des articles
include(dirname(__DIR__, 1) . '/template/panel/client/clients-template.php');


/**
 * Fonction vérifie le formulaire
 * @return bool si le formulaire est valide
 */
function validateForm(): bool
{
    global $error;
    $valid = true;
    if (empty($_POST["idClient"]) || !preg_match("/^[0-9]+$/", $_POST["idClient"])) {
        $error[] = "Id non valide !";
        $valid = false;
    }
    if (empty($_POST["raisonScClient"]) || !preg_match("/^[a-zA-Z0-9 ]+$/", $_POST["raisonScClient"])) {
        $error[] = "Raison sociale client non valide !";
        $valid = false;
    }
    if (empty($_POST["adrClient"]) || !preg_match("/^[a-zA-Z0-9 ]+$/", $_POST["adrClient"])) {
        $error[] = "Adresse client non valide !";
        $valid = false;
    }
    if (empty($_POST["telClient"]) || !preg_match("/^[0-9]+$/", $_POST["telClient"])) {
        $error[] = "Telephone client non valide !";
        $valid = false;
    }
    if (empty($_POST["nSirenClient"]) || !preg_match("/^[0-9 ]+$/", $_POST["nSirenClient"])) {
        $error[] = "Numéro de SIREN non valide !";
        $valid = false;
    }
    if (empty($_POST["emailClient"]) || !preg_match("/^[a-zA-Z0-9@.]+$/", $_POST["adrClient"])) {
        $error[] = "Email adresse client non valide !";
        $valid = false;
    }
    if (empty($_POST["modePaiement"]) || !preg_match("/^[1-2]+$/", $_POST["modeLivraison"])) {
        $error[] = "Veuillez choisir le mode de paiement !";
        $valid = false;
    }
    if (empty($_POST["delaiPaiement"]) || !preg_match("/^[0-9]+$/", $_POST["delaiPaiement"])) {
        $error[] = "Délai de paiement non valide !";
        $valid = false;
    }
    if (empty($_POST["modeLivraison"]) || !preg_match("/^[1-2]+$/", $_POST["modeLivraison"])) {
        $error[] = "Veuillez choisir le mode de livraison !";
        $valid = false;
    }
    return $valid;
}

/**
 * Fonction pour intégrer le formulaire
 * @return void
 */
function includeForm(): void
{
    include(dirname(__DIR__, 1) . '/template/panel/clients/client-form-template.php');

}

?>