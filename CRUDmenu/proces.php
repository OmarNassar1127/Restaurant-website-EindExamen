<?php
// sessie wordt gestart
session_start();
// db connectie wordtt gemaakt
include_once("../database_conn.php");
// deze functie krijgt de data van de reserveringen.php en wordt vervolgens naar de db gestuurd
if (isset($_POST['toevoegen'])) {
    $Gnaam = $_POST['gerecht_naam'];
    $Gprijs = $_POST['gerecht_prijs'];
    $omschrijving = $_POST['omschrijving'];
    $afbeelding = $_POST['afbeelding'];
    $type_eten = $_POST['type_eten'];

    // $db_conn->query("INSERT INTO `menu` (`gerecht_naam`, `gerecht_prijs`, `gerecht_omschrijving`, `gerecht_afbeelding`, `menuType`) VALUES ('$Gnaam', '$Gprijs', '$omschrijving', '$afbeelding', '$type_eten')") or die($db_conn->error);

    // Hier maak ik gebruik van prepared stmt en placeholders om sql injecties te voorkomen
    $sql = "INSERT INTO `menu` (`gerecht_naam`, `gerecht_prijs`, `gerecht_omschrijving`, `gerecht_afbeelding`, `menuType`) VALUES (:Gnaam, :Gprijs, :omschrijving, :afbeelding, :type_eten)";
    $stmt = $db_conn->prepare($sql); //stuur naar mysql.
    $stmt->bindParam(":Gnaam", $Gnaam);
    $stmt->bindParam(":Gprijs", $Gprijs);
    $stmt->bindParam(":omschrijving", $omschrijving);
    $stmt->bindParam(":afbeelding", $afbeelding);
    $stmt->bindParam(":type_eten", $type_eten);
    $stmt->execute();

    // dit zorgt dat aan het einde van de functie dat je terug naar de pagina menu.php terug gestuurd wordt
    header("location:../menu.php");
}
// Deze functie zorgt dat de tafel die gereserveerd is verwijderd kan worden
if (isset($_GET['verwijder'])) {
    $id = $_GET['verwijder'];
    $res = $db_conn->query("DELETE FROM `menu` WHERE menu_id=$id");
    // omdat het een foreing key is, er is een check dat als het een error melding gaat geven dat ie gewoon mij melding geeft en niet een error
    // omdat ik gebruik maak van FK je kan ze niet verwijderen als ze ergens anders gebruikt worden (net als bij winkelmand)
    //vandaar dat ik deze functie gemaakt heb
    if (!$res) { ?>
        <h1>Deze product is besteld. Voordat je deze product verwijderd, zorg eerst dat deze product niet besteld is.</h1>
        <h5>Je wordt zometeen terug gestuurd naar de menu pagina.</h5>
<?php
        // dit zorgt dat aan het einde van de functie dat je terug naar de pagina menu.php gestuurd wordt (na 8 seconden )
        header("refresh:8;url=../menu.php");
    } else {
        // dit zorgt dat aan het einde van de functie dat je terug naar de pagina menu.php gestuurd wordt
        header("location:../menu.php");
    }
}
?>