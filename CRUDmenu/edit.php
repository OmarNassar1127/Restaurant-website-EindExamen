<?php
// sessie wordt gestart
session_start();
// db connectie wordt gemaakt
include_once("../database_conn.php");
// dezee functie zorgt dat de informatie van de menu verandert kan weorden (edit functie) 
if (isset($_POST['e_verzend'])) {
    $id = $_POST['e_id'];
    $Gnaam = $_POST['e_Gnaam'];
    $Gprijs = $_POST['e_Gprijs'];
    $omschrijving = $_POST['e_omschrijving'];
    $afbeelding = $_POST['e_afbeelding'];
    // hier wordt de sql statement uitgevoerd
    // hij checkt eerst dat de variable Gnaam niet leeg is en dat voert ie de hele ding uit
    if (!empty($Gnaam)) {
        try {
            // hier doe ik ook een check voor het veld afbeelding, als die leeg is krijgt de gebruiker "Kies een foto eerst" te zien.
            if (empty($afbeelding)) {
                $stmt = "" ?>
                <h1>Kies een foto eerst</h1>
<?php
            } else {
                $stmt = $db_conn->prepare("UPDATE `menu` SET `gerecht_naam`=:naam ,`gerecht_prijs`=:prijs ,`gerecht_omschrijving`=:omschrijving ,`gerecht_afbeelding`=:afbeelding WHERE
				menu_id= :id") or die($db_conn->error);

                $stmt->execute(array(':naam' => $Gnaam, ':prijs' => $Gprijs, ':omschrijving' => $omschrijving, ':afbeelding' => $afbeelding, ':id' => $id));
            }
            if ($stmt) {
                header("location:../menu.php");
            }
        } catch (PDOException $ex) {
            echo $exo->getMessage();
        }
    } else {
        echo "Input field";
    }
}
// deze variabelen zorgen dat als iets miss me gaat met het krijgen van de informatie dat het een lege string komt en niet een error message
$id = 0;
$Gnaam = '';
$Gprijs = '';
$omschrijving = '';
$afbeelding = '';
// met deze functie wordt de informatie van de database opgehald en in variabelen gezet.
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db_conn->prepare('SELECT * FROM `menu` WHERE menu_id= :id');
    $stmt->execute(array(':id' => $id));
    $row = $stmt->fetch();
    $id = $row['menu_id'];
    $Gnaam = $row['gerecht_naam'];
    $Gprijs = $row['gerecht_prijs'];
    $omschrijving = $row['gerecht_omschrijving'];
    $afbeelding = $row['gerecht_afbeelding'];
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <h2>Update je product</h2>
            <!-- In deze formulier worden de variabelen die we uit de db gehald hadden gebruikt -->
            <!-- Maar ook wordt hier de nieuwe informatie verstuurd naar de eerste functie zodat je de informatie van de menu (product) aan kan passen -->
            <form action="" method="post">
                <table class="table">
                    <tr>
                        <td>Tafel nmr</td>
                        <td><input type="text" name="e_Gnaam" value="<?= $Gnaam; ?>" class="editFields"></td>
                    </tr>
                    <tr>
                        <td>Gerecht prijs</td>
                        <td><input type="text" name="e_Gprijs" value="<?= $Gprijs; ?>" class="editFields"></td>
                    </tr>
                    <tr>
                        <td>Omschrijving</td>
                        <td><textarea rows="4" cols="22" type="" name="e_omschrijving" value="<?= $omschrijving; ?>">
							<?php echo $row['gerecht_omschrijving'] ?>
                        </textarea></td>
                    </tr>
                    <tr>
                        <td>Afbeelding</td>
                        <td><img src="../eten/<?php echo $row['gerecht_afbeelding']; ?>" style="width: 200px;"></td>
                        <td><input type="file" name="e_afbeelding"></td>
                    </tr>
                    <tr>
                        <td><input type="hidden" name="e_id" value="<?= $id; ?>"></td>
                        <td><input type="submit" class="btn btn-info" name="e_verzend"></td>
                    </tr>
                </table>

            </form>
        </div>
    </div>
</body>

</html>