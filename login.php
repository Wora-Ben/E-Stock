<?php

require("services/authentication/authentification.php");
global $error;
print_r($error);

if (isset($_GET["login"])){
    if(login(htmlspecialchars($_POST["username"]),htmlspecialchars($_POST["password"]))){
        header('Location: panel/dashboard.php');
    }
}
include("template/panel/login/login.php");