<?php
// db connectie wordt gemaakt 
include_once("../database_conn.php");
// Deze is de functie waarmee de gebruiker zn bestelden producten kan verwijderen uit zijn winkelmand
if (isset($_GET['verwijder'])) {
    $id = $_GET['verwijder'];
    $sql = "DELETE FROM `menuLijst` WHERE menuLijst_id=:id";
    $stmt = $db_conn->prepare($sql); //stuur naar mysql.
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    header("Location: {$_SERVER['HTTP_REFERER']}");
}
