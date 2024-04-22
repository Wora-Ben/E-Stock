<?php
require(dirname(__DIR__, 1) . '/services/database/connection.php');
require(dirname(__DIR__, 1) . '/controller/Stock.php');

$infos = Stock::stockInfos();

include(dirname(__DIR__, 1) . '/template/panel/dashboard-template.php');