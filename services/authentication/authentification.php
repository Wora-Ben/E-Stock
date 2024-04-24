<?php
require(dirname(__DIR__, 1) . "/database/connection.php");
global $error;
/**
 * @param String $username
 * @return mixed
 */
function getUserInfos(string $username): mixed
{
    try {
        $conn = connection();
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE username=:username");
        $stmt->bindValue(":username", $username);
        $conn = null;
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        global $error;
        $error['login'] = "Erreur d'authentification";
        return false;
    }
}

/**
 * Function return true if username and password are correct
 * @param string $username username of the user
 * @param String $password password of the user
 * @return bool return true if login infos are correct, otherwise false
 */
function login(string $username, string $password): bool
{
    //get current user infos
    $user = getUserInfos($username);
    if ($user && $user[0]->passwordHash === hash("sha256", $password)) {
        session_start();
        $_SESSION['username'] = $user[0]->username;
        $_SESSION['id_user'] = $user[0]->id_user;
        $_SESSION['start_time'] = time();
        return true;
    }
    global $error;
    $error['login'] = "Nom d'utilisateur ou mot de passe incorrect";
    return false;
}

function register(string $username, string $password): bool
{
    try {
        $conn = connection();
        $stmt = $conn->prepare('INSERT INTO utilisateur(username,passwordHash) VALUES(:username,:passwordHash)');
        $stmt->bindValue(":username", $username);
        $stmt->bindValue(":passwordHash", hash("sha256", $password));
        $conn = null;
        return $stmt->execute();
    } catch (PDOException $e) {
        global $error;
        $error['register'] = "Erreur d'inscription";
        return false;
    }


}

/**
 * Function check if the user is logged in
 * @return bool
 */
function is_user_logged_in(): bool
{
    //Check session values & check if current session is not expired
    if ((empty($_SESSION['username'])) || (isset($_SESSION['start_time']) && (time() - $_SESSION['start_time'] > 120)))//Seconds
    {
        session_regenerate_id();
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(), "", 0, null, null, false, true);
        return false;
    }
    return true;
}

/**
 * Redirect to login page
 * @return void
 */
function require_login(): void
{
    if (!is_user_logged_in()) {
        header("Location:https://" . $_SERVER['HTTP_HOST'] . '/login.php');
    }
}

/**
 * Finish current session
 * @return void
 */
function logout(): void
{
    if (is_user_logged_in()) {
        session_regenerate_id();
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(), "", 0, null, null, false, true);
        header("Location:https://" . $_SERVER['HTTP_HOST'] . '/index.php');

    }
}

?>