<?php
session_start();
use model\ModelArticle;
include(dirname(__DIR__, 1) . '/services/authentication/authentification.php');
require_login();
include(dirname(__DIR__, 1) . '/controller/Stock.php');
include(dirname(__DIR__, 1) . '/controller/Article.php');
const PAGE_MAX_ROWS = 15;
global $info;
global $infos;
global $error;

$info = new ModelArticle();
if (isset($_GET['etatGeneral'])) {
    $infos = Stock::etatDeStockGlobal();
    $valeur_stock = Stock::valeurStock()[0];
    include(dirname(__DIR__, 1) . '/template/panel/etat/etat-general-template.php');
} else if (isset($_GET['etatArticle'])) {
    if (isset($_GET['idArticle'])) {
        if ($res = (Stock::etatDeStockArticle($_GET['idArticle']))) {
            $info = Article::getArticleById($_GET['idArticle'])[0];
            $infos = Stock::etatDeStockArticle($info->id_article);
        }
    }
    include(dirname(__DIR__, 1) . '/template/panel/etat/etat-article-template.php');

} else {
//    header("location:dashboard.php");
}