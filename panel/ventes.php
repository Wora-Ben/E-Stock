<?php
session_start();
global $info;
include(dirname(__DIR__, 1) . '/services/authentication/authentification.php');
require_login();
include(dirname(__DIR__, 1) . '/controller/Vente.php');
include_once(dirname(__DIR__, 1) . '/model/VenteModel.php');
const PAGE_MAX_ROWS = 15;
$info = new VenteModel();

if (isset($_GET['search']) && !empty($_GET['searchValue'])) {
    $infos = Vente::searchVente(htmlspecialchars($_GET['searchValue']));
} else {
    $infos = Vente::ventesInfos();
}

if (isset($_GET['addVente'])) {
    $request = "enregistrerNvVente";
    if (isset($_POST['enregistrerNvVente'])) {
        reloadInfo();
        if (validateForm()) {
            if (Vente::nouvelVente(htmlspecialchars($_POST['idClient']), htmlspecialchars($_POST['idArticle']), htmlspecialchars($_POST['quantite']), htmlspecialchars($_POST['prixUnitaire']), htmlspecialchars($_POST['modeLivraison']))) {
                header('Location: ventes.php?newVente=true');
            }
        }
    }
    includeForm();
    exit();
}

if (isset($_GET['deleteVente'])) {
    $request = 'confirmDelete';
    $info = Vente::getVenteById(htmlspecialchars($_POST['idVente']))[0];
    include(dirname(__DIR__, 1) . '/template/panel/vente/vente-delete-template.php');
    exit();
}

if (isset($_POST["confirmDelete"]) && Vente::deleteVente(htmlspecialchars($_POST['idVente']))) {
    header('Location: ventes.php?venteDeleted=true');
}
//Intégrer la liste des articles
include(dirname(__DIR__, 1) . '/template/panel/vente/ventes-template.php');


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
    include(dirname(__DIR__, 1) . '/template/panel/vente/vente-form-template.php');

}

function reloadInfo(): void
{
        global $info;
        $info->id_client = key_exists("idClient", $_POST) ? htmlspecialchars($_POST['idClient']) : "";
        $info->id_article = key_exists("idArticle", $_POST) ? htmlspecialchars($_POST['idArticle']) : "";
        $info->quantite = key_exists("quantite", $_POST) ? htmlspecialchars($_POST['quantite']) : "";
        $info->prix_unitaire_ht = key_exists("prixUnitaire", $_POST) ? htmlspecialchars($_POST['prixUnitaire']) : "";
        $info->mode_livraison = key_exists("modeLivraison", $_POST) ? htmlspecialchars($_POST['modeLivraison']) : "";
}

?>