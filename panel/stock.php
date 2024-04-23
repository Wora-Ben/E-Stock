<?php
global $info;


use model\StockModel;

include(dirname(__DIR__, 1) . '/services/database/connection.php');
include(dirname(__DIR__, 1) . '/controller/Stock.php');
include_once(dirname(__DIR__, 1) . '/model/StockModel.php');

const PAGE_MAX_ROWS = 15;

$info = new StockModel();

if (isset($_GET['search']) && !empty($_GET['searchValue'])) {
    $infos = Stock::searchStock(htmlspecialchars($_GET['searchValue']));
} else {
    $infos = Stock::listeStock();
}


//Intégrer la liste des articles
include(dirname(__DIR__, 1) . '/template/panel/stock/stock-template.php');


?>