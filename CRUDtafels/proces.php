<?php
// sessie wordt gestart
session_start();
// db connectie wordt gemaakt
include_once("../database_conn.php");
// deze functie krijgt de data van de reserveringen.php en wordt vervolgens naar de db gestuurd
if (isset($_POST['toevoegen'])) {
    $tafel = $_POST['tafel_nmr'];
    $naamPersoon = $_POST['naam_persoon'];
    $telefoon = $_POST['telefoon'];
    $aantal = $_POST['aantal_mensen'];
    $tijd = $_POST['tijd'];
    $datum = $_POST['datum'];
    $drankjes = $_POST['welke_drankjes'];

    // met deze functie hal ik alles uit de table reserveringen 
    // en vergelijk ik het met de value dat ik gekregen heb van tafel nmr zodat ik niet duplicated data heb
    $res = $db_conn->query("SELECT * FROM `reserveringen` WHERE tafel_nmr=$tafel");
    $num_rows = $res->fetchColumn();

    if ($num_rows == 0) {
        // Hier maak ik gebruik van prepared stmt en placeholders om sql injecties te voorkomen
        $sql = "INSERT INTO `reserveringen` (`tafel_nmr`, `naam_persoon`, `telefoon`, `aantal_mensen`, `tijd`, `datum`, `drankjes`) VALUES (:tafel, :naamPersoon, :telefoon, :aantal, :tijd, :datum, :drankjes)";
        $stmt = $db_conn->prepare($sql); //stuur naar mysql.
        $stmt->bindParam(":tafel", $tafel);
        $stmt->bindParam(":naamPersoon", $naamPersoon);
        $stmt->bindParam(":telefoon", $telefoon);
        $stmt->bindParam(":aantal", $aantal);
        $stmt->bindParam(":tijd", $tijd);
        $stmt->bindParam(":datum", $datum);
        $stmt->bindParam(":drankjes", $drankjes);
        $stmt->execute();

        // dit zorgt dat aan het einde van de functie dat je terug naar de pagina reserveringen.php terug gestuurd wordt
        header("location:../reserveringen.php");
        // als de ingevulde tafel bezet is dan zal de gebruiker de volgende melding krijgen    
    } else {
        // deze error message wordt in de sessie gezet en gestuurd naar de reserveringen pagina, zodat ik hem daar kan gebruiken.
        $_SESSION['bezet'] = "Sorry, deze tafel is al bezet. Je moet een andere kiezen.";
        header("location:../reserveringen.php");
        exit();
    }
}

// Deze functie zorgt dat de tafel die gereserveerd is verwijderd kan worden
if (isset($_GET['verwijder'])) {
    $id = $_GET['verwijder'];
    //VERWIJDER EEN WAARDE UIT EEN DATABASE TABEL
    $sql = "DELETE FROM `reserveringen` WHERE tafel_id=:id";
    $stmt = $db_conn->prepare($sql); //stuur naar mysql.
    $stmt->bindParam(":id", $id);
    $stmt->execute();



    // dit zorgt dat aan het einde van de functie dat je terug naar de pagina reserveringen.php gestuurd wordt
    header("location:../reserveringen.php");
}
