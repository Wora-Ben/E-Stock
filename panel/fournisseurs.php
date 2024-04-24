<?php
session_start();
global $info;
include(dirname(__DIR__, 1) . '/services/authentication/authentification.php');
require_login();
include(dirname(__DIR__, 1) . '/controller/Fournisseur.php');
include_once(dirname(__DIR__, 1) . '/model/FournisseurModel.php');
const PAGE_MAX_ROWS = 15;
$info = new FournisseurModel();

if (isset($_GET['search']) && !empty($_GET['searchValue'])) {
    $infos = Fournisseur::searchFournisseur(htmlspecialchars($_GET['searchValue']));
} else {
    $infos = Fournisseur::fournisseursInfos();
}

if (isset($_GET['addFournisseur'])) {
    $request = "enregistrerNvFournisseur";
    if (isset($_POST['enregistrerNvFournisseur'])) {
        reloadInfo();
        if (validateForm()) {
            if (Fournisseur::addFournisseur(htmlspecialchars($_POST['raisonScFournisseur']), htmlspecialchars($_POST['adrFournisseur']), htmlspecialchars($_POST["emailFournisseur"]), htmlspecialchars($_POST["telFournisseur"]), htmlspecialchars($_POST["nSirenFournisseur"]), htmlspecialchars($_POST["nomInterlocuteur"]), htmlspecialchars($_POST["modePaiement"]), htmlspecialchars($_POST["delaiPaiement"]))) {
                header('Location: fournisseurs.php?newFournisseur=true');
            }
        }
    }
    includeForm();
    exit();
}

if (isset($_POST['saveEdits'])) {
    $request = 'saveEdits';
    $info = Fournisseur::getFournisseurById(htmlspecialchars($_POST['idFournisseur']))[0];
    if (validateForm()) {
        if (Fournisseur::modifyFournisseur(htmlspecialchars($_POST["idFournisseur"]), htmlspecialchars($_POST['raisonScFournisseur']), htmlspecialchars($_POST['adrFournisseur']),
            htmlspecialchars($_POST["emailFournisseur"]), htmlspecialchars($_POST["telFournisseur"]), htmlspecialchars($_POST["nSirenFournisseur"]), htmlspecialchars($_POST["nomInterlocuteur"]),
            htmlspecialchars($_POST["modePaiement"]), htmlspecialchars($_POST["delaiPaiement"]))) {
            header('Location: fournisseurs.php?fournisseurEdited=true');
        }
    }
    reloadInfo();
    includeForm();
    exit();
}

if (isset($_GET['editFournisseur'])) {
    $request = 'saveEdits';
    $info = Fournisseur::getFournisseurById($_POST['idFournisseur'])[0];
    includeForm();
    exit();
}

if (isset($_GET['deleteFournisseur'])) {
    $request = 'confirmDelete';
    $info = Fournisseur::getFournisseurById($_POST['idFournisseur'])[0];
    include(dirname(__DIR__, 1) . '/template/panel/fournisseur/fournisseur-delete-template.php');
    exit();
}

if (isset($_POST["confirmDelete"]) && Fournisseur::deleteFournisseur($_POST['idFournisseur'])) {
    header('Location: fournisseurs.php?FournisseurDeleted=true');
}
//Intégrer la liste des articles
include(dirname(__DIR__, 1) . '/template/panel/fournisseur/fournisseurs-template.php');


/**
 * Fonction vérifie le formulaire
 * @return bool si le formulaire est valide
 */
function validateForm(): bool
{
    global $error;
    $valid = true;
    if (empty($_POST["raisonScFournisseur"]) || !preg_match("/^[a-zA-Z0-9 ]+$/", $_POST["raisonScFournisseur"])) {
        $error[] = "Raison sociale fournisseur non valide !";
        $valid = false;
    }
    if (empty($_POST["adrFournisseur"]) || !preg_match("/^[a-zA-Z0-9 ]+$/", $_POST["adrFournisseur"])) {
        $error[] = "Adresse fournisseur non valide !";
        $valid = false;
    }
    if (empty($_POST["telFournisseur"]) || (strlen($_POST["telFournisseur"])!=10)  || !preg_match("/^[0-9]+$/", $_POST["telFournisseur"])) {
        $error[] = "Telephone fournisseur non valide !";
        $valid = false;
    }
    if (empty($_POST["nSirenFournisseur"]) || !preg_match("/^[0-9 ]+$/", $_POST["nSirenFournisseur"])) {
        $error[] = "Numéro de SIREN non valide !";
        $valid = false;
    }
    if (empty($_POST["emailFournisseur"]) || !preg_match("/^[a-zA-Z0-9@.]+$/", $_POST["emailFournisseur"])) {
        $error[] = "Email adresse fournisseur non valide !";
        $valid = false;
    }
    if (empty($_POST["nomInterlocuteur"]) || !preg_match("/^[a-zA-Z]+$/", $_POST["nomInterlocuteur"])) {
        $error[] = "Nom de l'interlocuteur non valide !";
        $valid = false;
    }
    if (empty($_POST["modePaiement"])) {
        $error[] = "Veuillez choisir le mode de paiement !";
        $valid = false;
    }
    if (is_null($_POST["delaiPaiement"]) || !preg_match("/^[0-9]+$/", $_POST["delaiPaiement"])) {
        $error[] = "Délai de paiement non valide !";
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
    include(dirname(__DIR__, 1) . '/template/panel/fournisseur/fournisseur-form-template.php');

}

function reloadInfo(): void
{
    global $info;
    $info->raison_sociale_fournisseur = key_exists("raisonScFournisseur", $_POST) ? htmlspecialchars($_POST['raisonScFournisseur']) : "";
    $info->adresse_fournisseur = key_exists("adrFournisseur", $_POST) ? htmlspecialchars($_POST['adrFournisseur']) : "";
    $info->email_fournisseur = key_exists("emailFournisseur", $_POST) ? htmlspecialchars($_POST['emailFournisseur']) : "";
    $info->telephone_fournisseur = key_exists("telFournisseur", $_POST) ? htmlspecialchars($_POST['telFournisseur']) : "";
    $info->nom_interlocuteur = key_exists("nomInterlocuteur", $_POST) ? htmlspecialchars($_POST['nomInterlocuteur']) : "";
    $info->n_siren = key_exists("nSirenFournisseur", $_POST) ? htmlspecialchars($_POST['nSirenFournisseur']) : "";
    $info->mode_paiement = key_exists("modePaiement", $_POST) ? htmlspecialchars($_POST['modePaiement']) : "";
    $info->delai_paiement = key_exists("delaiPaiement", $_POST) ? htmlspecialchars($_POST['delaiPaiement']) : "";
}

?>