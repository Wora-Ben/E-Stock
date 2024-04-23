<?php
global $info;

use model\ModelArticle;

include(dirname(__DIR__, 1) . '/services/database/connection.php');
include(dirname(__DIR__, 1) . '/controller/Article.php');
include_once(dirname(__DIR__, 1) . '/model/ModelArticle.php');
const PAGE_MAX_ROWS = 15;
$info = new ModelArticle();

if (isset($_GET['search']) && !empty($_GET['searchValue'])) {
    $infos = Article::searchArticle(htmlspecialchars($_GET['searchValue']));
} else {
    $infos = Article::articlesInfos();
}

if (isset($_GET['addArticle'])) {
    $request = "enregistrerNvArticle";
    if (isset($_POST['enregistrerNvArticle'])) {
        reloadInfo();
        if (validateForm()) {
            if (Article::addArticle(htmlspecialchars($_POST['refArticle']), htmlspecialchars($_POST['desArticle']), htmlspecialchars($_POST["prixArticle"]))) {
                header('Location: articles.php?newArticle=true');
            }
        }
    }
    includeForm();
    exit();
}

if (isset($_POST['saveEdits'])) {
    $request = 'saveEdits';

    $info =  Article::getArticleById(htmlspecialchars($_POST['idArticle']))[0];
    echo "id article : ".$info->id_article."<br>";
    if (validateForm()) {
        if ( Article::modifyArticle(htmlspecialchars($_POST['idArticle']), htmlspecialchars($_POST['refArticle']), htmlspecialchars($_POST['desArticle']), htmlspecialchars($_POST["prixArticle"]))){
            header('Location: articles.php?articleEdited=true');
        }
    }
    reloadInfo();
    includeForm();
    exit();
}

if (isset($_GET['editArticle'])) {
    $request = 'saveEdits';
    $info = Article::getArticleById($_POST['idArticle'])[0];
    includeForm();
    exit();
}

if (isset($_GET['deleteArticle'])) {
    $request = 'confirmDelete';
    $info =  Article::getArticleById(htmlspecialchars($_POST['idArticle']))[0];
    include(dirname(__DIR__, 1) . '/template/panel/article/article-delete-template.php');
    exit();
}

if (isset($_POST["confirmDelete"]) && Article::deleteArticle($_POST['idArticle'])) {
    header('Location: articles.php?articleDeleted=true');
}
//Intégrer la liste des articles
include(dirname(__DIR__, 1) . '/template/panel/article/articles-template.php');


/**
 * Fonction vérifie le formulaire
 * @return bool si le formulaire est valide
 */
function validateForm(): bool
{
    global $error;
    $valid = true;
    if (empty($_POST["refArticle"]) || !preg_match("/^[a-zA-Z0-9 ]+$/", $_POST["refArticle"])) {
        $error[] = "Référence article non valide !";
        $valid = false;
    }
    if (empty($_POST["desArticle"]) || !preg_match("/^[a-zA-Z0-9 ]+$/", $_POST["desArticle"])) {
        $error[] = "Désignation article non valide !";
        $valid = false;
    }
    if (empty($_POST["prixArticle"]) || !preg_match("/^[0-9 ]+$/", $_POST["prixArticle"])) {
        $error[] = "Numéro de SIREN non valide !";
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

function reloadInfo(): void
{
    global $info;
    $info->reference_article = key_exists("refArticle", $_POST) ? htmlspecialchars($_POST['refArticle']) : " ";
    $info->designation_article = key_exists("desArticle", $_POST) ? htmlspecialchars($_POST['desArticle']) : " ";
    $info->prix_achat_unitaire_HT = key_exists("prixArticle", $_POST) ? htmlspecialchars($_POST['prixArticle']) : " ";
}

?>