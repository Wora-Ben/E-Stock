<?php
require(dirname(__DIR__, 1) . "/services/database/connection.php");
global $error;

/**
 * Class user to manage a user
 */
class User
{
    /**
     * Ajouter un nouvel utilisateur
     * @param string $username nom d'utilisateur
     * @param string $password mots de passe
     * @return bool true if the user creation succeed, otherwise false
     */
    public static function addUser(string $username, string $password): bool
    {
        try {
            $conn = connection();
            $stmt = $conn->prepare("INSERT INTO utilisateur(username, passwordHash) VALUES(:username, :passwordHash)");
            $passwordHashed = hash('sha256', $password);
            $stmt->execute([":username" => $username, ":passwordHash" => $passwordHashed]);
            return true;
        } catch (PDOException $e) {
            global $error;
            $error["addUtilisateur"] = "Erreur";
            return false;
        }
    }
}
