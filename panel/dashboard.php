<?php
require(dirname(__DIR__, 1) . '/models/Stock.php');
require(dirname(__DIR__, 1) . '/services/database/connection.php');

$infos = Stock::stockInfos();

include(dirname(__DIR__, 1) . '/template/panel/dashboard-template.php');