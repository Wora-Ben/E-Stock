<?php
include(dirname(__DIR__, 1) . '/services/database/connection.php');
include(dirname(__DIR__, 1) . '/models/Client.php');
const PAGE_MAX_ROWS = 15;

if (isset($_GET['search']) && !empty($_GET['searchValue'])) {
    $infos = Client::searchClient(htmlspecialchars($_GET['searchValue']));
} else {
    $infos = Article::articlesInfos();
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
    if (validateForm()) {
        if (Client::modifyClient(htmlspecialchars($_POST["idClient"]), htmlspecialchars($_POST["raisonScClient"]), htmlspecialchars($_POST["adrClient"]), htmlspecialchars($_POST["emailClient"]), htmlspecialchars($_POST["telClient"]), htmlspecialchars($_POST["nSirenClient"]), htmlspecialchars($_POST["modePaiement"]), htmlspecialchars($_POST["delaiPaiement"]), htmlspecialchars($_POST["modeLivraison"]))){
        header('Location: clients.php?clientEdited=true');
      }
    }
    $idClient = htmlspecialchars($_POST['idClient']);
    $raisonScClient = htmlspecialchars($_POST['raisonScClient']);
    $adrClient = htmlspecialchars($_POST["adrClient"]);
    $emailClient = htmlspecialchars($_POST["emailClient"]);
    $telClient = htmlspecialchars($_POST["telClient"]);
    $nSirenClient = htmlspecialchars($_POST["nSirenClient"]);
    $modePaiement = htmlspecialchars($_POST["modePaiement"]);
    $delaiPaiement = htmlspecialchars($_POST["delaiPaiement"]);
    $modeLivraison = htmlspecialchars($_POST["modeLivraison"]);
    includeForm();
    exit();
}

if (isset($_GET['editClient'])) {
    $request = 'saveEdits';
    $idClient = htmlspecialchars($_GET['idClient']);
    $raisonScClient = htmlspecialchars($_GET['raisonScClient']);
    $adrClient = htmlspecialchars($_GET["adrClient"]);
    $emailClient = htmlspecialchars($_GET["emailClient"]);
    $telClient = htmlspecialchars($_GET["telClient"]);
    $nSirenClient = htmlspecialchars($_GET["nSirenClient"]);
    $modePaiement = htmlspecialchars($_GET["modePaiement"]);
    $delaiPaiement = htmlspecialchars($_GET["delaiPaiement"]);
    $modeLivraison = htmlspecialchars($_GET["modeLivraison"]);
    includeForm();
    exit();
}

if (isset($_GET['deleteArticle'])) {
    $request = 'deleteArticle';
    $idClient = htmlspecialchars($_GET['idClient']);
    $raisonScClient = htmlspecialchars($_GET['raisonScClient']);
    $nSirenClient = htmlspecialchars($_GET['nSirenClient']);
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
    if (empty($_POST["refArticle"]) || !preg_match("/^[a-zA-Z0-9]+$/", $_POST["refArticle"])) {
        $error[] = "Référence article non valide !";
        $valid = false;
    }
    if (empty($_POST["desArticle"]) || !preg_match("/^[a-zA-Z0-9 ]+$/", $_POST["desArticle"])) {
        $error[] = "Désignation article non valide !";
        $valid = false;
    }
    if (empty($_POST["prixArticle"]) || !preg_match("/^[0-9]+$/", $_POST["prixArticle"])) {
        $error[] = "Prix non valide !";
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
    include(dirname(__DIR__, 1) . '/template/panel/article/article-form-template.php');

}

?>