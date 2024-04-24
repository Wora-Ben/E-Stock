<?php

require("services/authentication/authentification.php");
global $error;
$username="admn";
$password="admin";
echo login($username,$password);
print_r($error);


