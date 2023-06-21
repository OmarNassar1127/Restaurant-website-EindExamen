<?php
// sessie wordt gestart
session_start();

// Als er geen sessie is dan zal de gebruiker niet op deze pagina komen, hij zal naar de homepagina terug gesturd worden.
if (!isset($_SESSION) || empty($_SESSION)) {
    header("location:../index.php");
}


// db connectie wordt gemaakt
include_once("../database_conn.php");

// met deze variable haal ik de tafel id van de pagina menu, zodat ik de bestellingen kan ophalen van de ene tafel 
$reservering = $_SESSION['tafel_id'];

// ik haal nu alle producten dat de gebruker heeft gekocht van de database op, en ik doe dat met een INNER JOIN statement.
$sql = "SELECT reserveringen.naam_persoon, reserveringen.tafel_nmr FROM reserveringen INNER JOIN menulijst ON reserveringen.tafel_id = menulijst.tafel_id WHERE menulijst.tafel_id = :reservering";
$statement = $db_conn->prepare($sql);
$statement->bindParam(":reservering", $reservering);
$statement->execute();
$extrainfo = $statement->fetchAll(PDO::FETCH_ASSOC);

// ik haal nu alle producten dat de gebruker heeft gekocht van de database op, en ik doe dat met een INNER JOIN statement.
$sql = "SELECT * from menu INNER JOIN menulijst ON menu.menu_id = menulijst.menu_id WHERE menulijst.tafel_id = :reservering";
$statement = $db_conn->prepare($sql);
$statement->bindParam(":reservering", $reservering);
$statement->execute();
$winkelmand = $statement->fetchAll(PDO::FETCH_ASSOC);


// Prepare and execute the query
$sql = "SELECT SUM(gerecht_prijs) as `prijs` FROM menu INNER JOIN menulijst ON menu.menu_id = menulijst.menu_id WHERE menulijst.tafel_id = :reservering";
$stmt = $db_conn->prepare($sql);
$stmt->bindParam(":reservering", $reservering);
$stmt->execute();
// Fetch the result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js">
    </script>
    <script src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
    <title>Basket
    </title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="../index.php">Excellent Taste</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../menu.php">Menu</a></li>
                <li><a href="../reserveringen.php">Reserveringen</a></li>
                <li><a href="../kok.php">Kok overzicht</a></li>
                <li><a href="../contact.php">Contact</a></li>
                <?php
                // hier doe ik twee checks eentje is of de sessie gezet is 
                // als sessie een admin is, hij krijgt toegaan tot de admin panel
                if ($_SESSION) {
                    if ($_SESSION['user_type'] == 'admin') { ?>
                        <li><a href="../admin/index.php">Admin Panel</a></li>
                <?php }
                } ?>
            </ul>
            <form class="navbar-form navbar-left" action="/action_page.php">
                <div class="form-group">
                </div>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <?php
                // hier check ik als de sessie gezet is, en als het gezet is dan krijg de buttons Winkelmand en log uit te zien
                if (isset($_SESSION['username'])) {
                ?>
                    <li class="active"><a href="basket"><span class="iconify" data-icon="fa-shopping-basket" data-inline="false"></span> Winkelmand</a></li>
                    <li><a href="../login/logout.php"><span class="glyphicon glyphicon-log-out"></span> Log uit</a></li>
                <?php
                } else {
                    // anders krijg je log in button of registreren
                ?>
                    <li><a href="../login/pdo_login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    <li><a href="../login/registreren.php"><span class="glyphicon glyphicon-user"></span> Registreren</a></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
    <?php
if (empty($_SESSION['tafel_id']) || empty($extrainfo)) {
    echo "<h4>Oops, kies eerst een tafel voordat je hier komt of zorg ervoor dat je al eten besteld hebt</h4>";
} else {
    echo "<h4 class='align-content-center'>Hier kan je de aankopen van:</h4>";
    $displayed = array();
    foreach ($extrainfo as $row) {
        $naam_persoon = $row['naam_persoon'];
        $tafel_nmr = $row['tafel_nmr'];
        if (!isset($displayed[$naam_persoon][$tafel_nmr])) {
            echo "<h4><span style='color:blue;'>$naam_persoon</span> zien, met tafel numr: <span style='color:blue;'>$tafel_nmr</span></h4>";
            $displayed[$naam_persoon][$tafel_nmr] = true;
        }
    }
}
?>


        <div class="row justify-content-center">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Gerecht naam</th>
                        <th>Gerecht prijs</th>
                    </tr>
                </thead>
                <?php
                foreach ($winkelmand as $row) : ?>
                    <tr>
                        <td>
                            <img src="../eten/<?php echo $row['gerecht_afbeelding']; ?>" class="card-img-top" style="width: 100px;">
                        </td>
                        <td>
                            <?php echo $row['gerecht_naam']; ?>
                        </td>
                        <td>€
                            <?php echo $row['gerecht_prijs']; ?>
                        </td>
                        <td>
                             <!-- Met deze button kan de gebruiker zijn bestelde producten verwijderen -->
                            <a href="proces.php?verwijder=<?= $row['menuLijst_id'] ?>" class="btn btn-danger">Verwijderen
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Totaal :</th>
                        <th>
                            <?php  // Show the total price
                                if ($result) {
                                    $total_price = $result['prijs'];
                                    echo "€" . number_format($total_price);
                                } else {
                                    echo "€0.00";
                                }
                            ?>
                        </th>
                    </tr>
                </thead>
            </table>
            </div>
        </div>
        <!-- Deze button stuurt je naar de factuur pagina die in pdf is -->
        <a type="button" class="btn btn-info" target="blank" href="factuur.php?reservering=<?= $reservering ?>">Factuur</a>
    </div>
</body>

</html>