<?php
session_start();
require(dirname(__DIR__, 1) . '/services/authentication/authentification.php');
require(dirname(__DIR__, 1) . '/controller/Stock.php');
require_login();

$infos = Stock::stockInfos();

include(dirname(__DIR__, 1) . '/template/panel/dashboard-template.php');