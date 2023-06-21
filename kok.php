<?php
// sessie wordt gestart
session_start();
// db connectie
include_once("database_conn.php");

if (isset($_POST['klaar'])) {
    $status = $_POST['status'];
    $id = $_POST['e_id'];

    if (!empty($status)) {
        try {
            $stmt = $db_conn->prepare("UPDATE `menulijst` SET `gemaakt`= :status WHERE menuLijst_id = :id");
            $stmt->execute(array(':status' => $status, ':id' => $id));
            if ($stmt) {
                header("location:kok.php");
            }
        } catch (PDOException $ex) {
            echo $exo->getMessage();
        }
    } else {
        echo "Er is iets miss gegaan;";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Excellent taste</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/hi.css">
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Excellent Taste</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="reserveringen.php">Reserveringen</a></li>
                <li class="active"><a href="kok.php">Kok overzicht</a></li>
                <li class="display-1"><a href="contact.php">Contact</a></li>
                <?php
                // hier doe ik twee checks eentje is of de sessie gezet is 
                // als sessie een admin is, hij krijgt toegaan tot de admin panel
                if ($_SESSION) {
                    if ($_SESSION['user_type'] == 'admin') { ?>
                        <li><a href="admin/index.php">Admin Panel</a></li>
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
                    <li><a href="basket/index.php"><span class="iconify" data-icon="fa-shopping-basket" data-inline="false"></span> Winkelmand</a></li>
                    <li><a href="login/logout.php"><span class="glyphicon glyphicon-log-out"></span> Log uit</a></li>
                <?php
                } else {
                    // anders krijg je log in button of registreren
                ?>
                    <li><a href="login/pdo_login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    <li><a href="login/registreren.php"><span class="glyphicon glyphicon-user"></span> Registreren</a></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>Overzicht voor de kok</h1>
        <script src="jquery.js"></script>
        <script>
            $(document).ready(function() {
                $("#status").on('change', function() {
                    var keyword = $(this).val();
                    $.ajax({
                        url: 'fetch2.php',
                        type: 'POST',
                        data: 'request=' + keyword,

                        beforeSend: function() {
                            $("#table-container").fadeOut(10);
                        },
                        success: function(data) {
                            $("#table-container").html(data).fadeIn(3000);
                        },
                    });
                });
            });
        </script>
        <div style="display: inline-block">Sorteren op gemaakte eten of niet:</div>
        <!-- Deze is de select bar alles wat hier gekozen is gaat naar de functie van boven en wordt naar andere pagina verstuurd -->
        <select id="status" name="fetchby" style="display: inline-block">
            <option value="">--- Kies ---</option>
            <option value="Staat klaar">Staat klaar</option>
            <option value="In behandeling">In behandeling</option>
        </select>
        <button onclick='window.location.reload();'>Hele overzicht</button>
        <div id="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th width="200">Gerecht Naam</th>
                        <th>Parijs</th>
                        <th>Omschrijving</th>
                        <th>Status van het eten</th>
                    </tr>
                </thead>
                <?php
                // hier maak ik gebruik van sql statment om alles te selecteren van het tabel `menu`
                $sql = "SELECT * from menu INNER JOIN menulijst ON menu.menu_id = menulijst.menu_id";
                $statement = $db_conn->prepare($sql);
                $statement->execute();
                $menu = $statement->fetchAll(PDO::FETCH_ASSOC);
                // bij deze loop ik er om heen om alles op de scherm te tonen
                foreach ($menu as $row) : ?>
                    <tr>
                        <td>
                            <img src="eten/<?php echo $row['gerecht_afbeelding']; ?>" class="card-img-top" style="width: 100px;">
                        </td>
                        <td>
                            <?php echo $row['gerecht_naam']; ?>
                        </td>
                        <td>
                            €<?php echo $row['gerecht_prijs']; ?>
                        </td>
                        <td style="word-break: break-all;">
                            <?php echo $row['gerecht_omschrijving']; ?>
                        </td>
                        <td style="word-break: break-all;">
                            <form method="post">
                                <select name="status">
                                    <option value="<?php echo $row['gemaakt']; ?>"><?php echo $row['gemaakt']; ?></option>
                                    <?php
                                    if ($row['gemaakt'] == "Staat klaar") {
                                        echo "<option value='In behandeling'>In behandeling</option>";
                                    } else if ($row['gemaakt'] == "In behandeling") {
                                        echo "<option value='Staat klaar'>Staat klaar</option>";
                                    }
                                    ?>
                                </select>
                                <input type="hidden" name="e_id" value=" <?php echo $row['menuLijst_id']; ?>">
                                <input type="submit" name="klaar">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

        </div>
    </div>
</body>

</html>