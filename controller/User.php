<?php
require(dirname(__DIR__, 1) . "\services\database\connection.php");

/**
 * Class user to manage a user
 */
class User
{
    /**
     * Add new user
     * @param string $username username
     * @param string $password password
     * @return bool true if the user creation succeed, otherwise false
     */
    public static function addUser(string $username, string $password): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare("INSERT INTO utilisateur(username, passwordHash) VALUES(:username, :passwordHash)");
            $passwordHashed = hash('sha256', $password);
            $stmt->execute([":username" => $username,":passwordHash" => $passwordHashed]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}

?>