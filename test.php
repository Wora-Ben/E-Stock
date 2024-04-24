<?php
include "services/authentication/authentification.php";
echo login("admin","admin");
echo "<br>";
logout();
echo "logout <br>";
