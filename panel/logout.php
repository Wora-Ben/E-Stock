<?php
session_start();
require (dirname(__DIR__,1).'/services/authentication/authentification.php');
logout();