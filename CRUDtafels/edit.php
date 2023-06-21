<?php
// sessie wordt gestart
session_start();
// db connectie wordt gemaakt
include_once("../database_conn.php");

// dezee functie zorgt dat de informatie van de tafel verandert kan weorden (edit functie) 
if (isset($_POST['e_verzend'])) {
    $id = $_POST['e_id'];
    $tafel = $_POST['e_tafel'];
    $naamPersoon = $_POST['e_naamPersoon'];
    $telefoon = $_POST['e_telefoon'];
    $aantal = $_POST['e_aantal'];
    $tijd = $_POST['e_tijd'];
    $datum = $_POST['e_datum'];
    $drankjes = $_POST['e_drankjes'];
    // hier wordt de sql statement uitgevoerd
    // hij checkt eerst dat de variable tafel niet leeg is en dat voert ie de hele ding uit
    if (!empty($tafel)) {
        try {
            $stmt = $db_conn->prepare("UPDATE `reserveringen` SET `tafel_nmr`= :tafel,`naam_persoon`=:naamPersoon,`telefoon`= :telefoon,`aantal_mensen`=:aantal,`tijd`=:tijd,`datum`=:datum,`drankjes`=:drankjes WHERE
				tafel_id= :id") or die($db_conn->error);

            $stmt->execute(array(':tafel' => $tafel, ':naamPersoon' => $naamPersoon, ':telefoon' => $telefoon, ':aantal' => $aantal, ':tijd' => $tijd, ':datum' => $datum, ':drankjes' => $drankjes, ':id' => $id));
            if ($stmt) {
                header("location:../reserveringen.php");
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
$tafel = '';
$naamPersoon = '';
$telefoon = '';
$aantal = '';
$tijd = '';
$datum = '';
$drankjes = '';
// met deze functie wordt de informatie van de database opgehald en in variabelen gezet.
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db_conn->prepare('SELECT * FROM `reserveringen` WHERE tafel_id= :id');
    $stmt->execute(array(':id' => $id));
    $row = $stmt->fetch();
    $id = $row['tafel_id'];
    $tafel = $row['tafel_nmr'];
    $naamPersoon = $row['naam_persoon'];
    $telefoon = $row['telefoon'];
    $aantal = $row['aantal_mensen'];
    $tijd = $row['tijd'];
    $datum = $row['datum'];
    $drankjes = $row['drankjes'];
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
            <!-- Maar ook wordt hier de nieuwe informatie verstuurd naar de eerste functie zodat je de informatie van de tafel aan kan passen -->
            <form action="" method="post">
                <table class="table">
                    <tr>
                        <td>Tafel nmr</td>
                        <td><input type="text" name="e_tafel" value="<?= $tafel; ?>" class="editFields"></td>
                    </tr>
                    <tr>
                        <td>Naam v.d persoon</td>
                        <td><input type="text" name="e_naamPersoon" value="<?= $naamPersoon; ?>" class="editFields"></td>
                    </tr>
                    <tr>
                        <td>Telefoon nmr</td>
                        <td><input type="text" name="e_telefoon" value="<?= $telefoon; ?>" class="editFields"></td>
                    </tr>
                    <tr>
                        <td>Aantal mensen</td>
                        <td><input type="text" name="e_aantal" value="<?= $aantal; ?>" class="editFields"></td>
                    </tr>
                    <tr>
                        <td>Tijd</td>
                        <td><input type="time" name="e_tijd" value="<?= $tijd; ?>" class="editFields"></td>
                    </tr>
                    <tr>
                        <td>Datum</td>
                        <td><input type="date" name="e_datum" value="<?= $datum; ?>" class="editFields"></td>
                    </tr>
                    <tr>
                        <td>Drankjes</td>
                        <td><input type="text" name="e_drankjes" value="<?= $drankjes; ?>" class="editFields"></td>
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