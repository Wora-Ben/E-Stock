<?php
global $info;

use model\AchatModel;

include(dirname(__DIR__, 1) . '/services/database/connection.php');
include(dirname(__DIR__, 1) . '/controller/Achat.php');
include_once(dirname(__DIR__, 1) . '/model/AchatModel.php');
const PAGE_MAX_ROWS = 15;
$info = new AchatModel();

if (isset($_GET['search']) && !empty($_GET['searchValue'])) {
    $infos = Achat::searchAchat(htmlspecialchars($_GET['searchValue']));
} else {
    $infos = Achat::achatsInfos();
}

if (isset($_GET['addAchat'])) {
    $request = "enregistrerNvAchat";
    if (isset($_POST['enregistrerNvAchat'])) {
        reloadInfo();
        if (validateForm()) {
            if (Achat::nouvelAchat(htmlspecialchars($_POST['idArticle']), htmlspecialchars($_POST['quantite']), htmlspecialchars($_POST["prixUnitaire"]), htmlspecialchars($_POST["idFournisseur"]))) {
                header('Location: achats.php?newAchat=true');
            }
        }
    }
    includeForm();
    exit();
}

if (isset($_GET['deleteAchat'])) {
    $request = 'confirmDelete';
    $info = Achat::getAchatById(htmlspecialchars($_POST['idAchat']))[0];
    include(dirname(__DIR__, 1) . '/template/panel/achat/achat-delete-template.php');
    exit();
}

if (isset($_POST["confirmDelete"]) && Achat::deleteAchat(htmlspecialchars($_POST['idAchat']))) {
    header('Location: achats.php?AchatDeleted=true');
}
//Intégrer la liste des articles
include(dirname(__DIR__, 1) . '/template/panel/achat/achats-template.php');


/**
 * Fonction vérifie le formulaire
 * @return bool si le formulaire est valide
 */
function validateForm(): bool
{
    global $error;
    $valid = true;
    if (empty($_POST["quantite"]) || !preg_match("/^[0-9]+$/", $_POST["quantite"])) {
        $error[] = "Quantite non valide !";
        $valid = false;
    }
    if (empty($_POST["prixUnitaire"]) || !preg_match("/^[0-9]+$/", $_POST["prixUnitaire"])) {
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
    include(dirname(__DIR__, 1) . '/template/panel/achat/achat-form-template.php');

}

function reloadInfo(): void
{
    global $info;
    $info->reference_article = key_exists("idArticle", $_POST) ? htmlspecialchars($_POST['idArticle']) : "";
    $info->quantite = key_exists("quantite", $_POST) ? htmlspecialchars($_POST['quantite']) : "";
    $info->prix_unitaire_ht = key_exists("prixUnitaire", $_POST) ? htmlspecialchars($_POST['prixUnitaire']) : "";
    $info->id_fournisseur = key_exists("idFournisseur", $_POST) ? htmlspecialchars($_POST['idFournisseur']) : "";
}

?>