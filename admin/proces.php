<?php
// db connectie
include_once("../database_conn.php");
// in deze functie wordt de informatie dat de admin gevuld had in de db gezet
if (isset($_POST['toevoegen'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telefoon = $_POST['telefoon'];
    $wachtwoord = $_POST['wachtwoord'];
    $userType = $_POST['usertype'];

    // Hier maak ik gebruik van prepared stmt en placeholders om sql injecties te voorkomen
    $sql = "INSERT INTO `users`( `username`, `password`, `email`, `telefoon`, `user_type`) VALUES (:username, :wachtwoord, :email, :telefoon, :userType)";
    $stmt = $db_conn->prepare($sql); //stuur naar mysql.
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":telefoon", $telefoon);
    $stmt->bindParam(":wachtwoord", $wachtwoord);
    $stmt->bindParam(":userType", $userType);
    $stmt->execute();

    // aan het einde van de functie de admin wordt naar de admin panel gestuurd
    header("location:index.php");
}

// met deze functie kan de admin gebruikers verwijderen
if (isset($_GET['verwijder'])) {
    $id = $_GET['verwijder'];
    $sql = "DELETE FROM `users` WHERE user_id=:id";
    $stmt = $db_conn->prepare($sql); //stuur naar mysql.
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    // $_SESSION['message'] = "Je item is verwijded";
    // $_SESSION['msg_type'] = "danger";

    header("location:index.php");
}
