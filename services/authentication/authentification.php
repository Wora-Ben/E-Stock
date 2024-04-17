<?php
require(dirname(__DIR__, 1) . "\services\database\connection.php");
/**
 * @param String $username
 * @return mixed
 */
function getUserInfos(string $username)
{
    $conn = connection();
    $stmt = $conn->prepare('SELECT * FROM utilisateur WHERE username=:username');
    try {
        $str = $stmt->execute([":username" => $username]);
    } catch (PDOException $e) {
        $error['login'] = "erreur d'authentification";
    }
    $stmt->setFetchMode(PDO::FETCH_OBJ);
    $conn = null;
    return $stmt->fetch();
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
    if ($user && $user->passwordHash === hash("sha256", $password)) {
        session_start();
        $_SESSION['username'] = $user->username;
        $_SESSION['id_user'] = $user->id_user;
        $_SESSION['start_time'] = time(); // update last activity time stamp
        return true;
    }
    return false;
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
        header("Location:http://" . $_SERVER['HTTP_HOST'] . '/project/PHP/Panel/index.php');
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
        header("Location:http://" . $_SERVER['HTTP_HOST'] . '/project/PHP/Panel/index.php');

    }
}

?>