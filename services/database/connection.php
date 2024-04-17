<?php
/**
 * Function to connect to the database
 * @return PDO|null
 */
function connection(): ?PDO
{
    //Configure the PDO connection
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $conn= new PDO('mysql:host=localhost;port=3306;dbname=db_project', 'root', '', $options);
    return $conn;
}
?>