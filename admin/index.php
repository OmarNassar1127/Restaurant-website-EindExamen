<?php
// de sessie wordt gestart
session_start();

// Als de sessie geen admin is dan de gebruiker zal gelijk naar de homepagina gaan
if ($_SESSION['user_type'] !== 'admin') {
    header("location:../index.php");
}

// db connectie wordt gemaakt 
include_once("../database_conn.php");
// met deze sql statement worden alle gebruikers van de db opgehald 
$result = $db_conn->query("SELECT * FROM users");
$sql = "SELECT * FROM users";
$statement = $db_conn->prepare($sql);
$statement->execute();
$adminP = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Admin panel
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
                <li class="active"><a href="index.php">Admin Panel</a></li>
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
        <h2 class="align-content-center">Admin panel
        </h2>
        <H4 class="align-content-center">Hier kan je accounts aanpassen en rechten geven
        </H4>
        <div class="row justify-content-center">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Telefoon nmr</th>
                        <th>User type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <?php
                // bij deze loop ik er om heen om alles op de scherm te tonen
                foreach ($adminP as $row) : ?>
                    <tr>
                        <td>
                            <?php echo $row['user_id']; ?>
                        </td>
                        <td>
                            <?php echo $row['username']; ?>
                        </td>
                        <td>
                            <?php echo $row['email']; ?>
                        </td>
                        <td>
                            <?php echo $row['telefoon']; ?>
                        </td>
                        <td>
                            <?php echo $row['user_type']; ?>
                        </td>
                        <td>
                            <!-- Met deze buttons kan de admin de volgende functies doen: Aanpassen en/of verwijderen -->
                            <a href="edit.php?id=<?= $row['user_id'] ?>" class="btn btn-info">Edit
                            </a>
                            <a href="proces.php?verwijder=<?php echo $row['user_id']; ?>" class="btn btn-danger">Verwijderen
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <!-- Met deze btton de admin kan de Modal openen om een nieuwe gebruiker toe te voegen -->
            <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin-bottom: 20px">
                Voeg een nieuwe admin toe
            </a>
        </div>
    </div>
    <!-- Deze is een modal (van bootstrap), daarin zijn alle input fields -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Toevoegen</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div>
                            <!-- Met deze formulier kan de admin een nieuwe gebruker aanmaken. (De informatie dat hier ingevud is wordt bijde proces.php pagina verwerkt) -->
                            <form action="proces.php" method="post" class="col-xs-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required />
                                <br />
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required />
                                <br />
                                <label>Wachtwoord</label>
                                <input type="password" name="wachtwoord" class="form-control" required />
                                <br />
                                <label>Telefoon nmr</label>
                                <input type="text" name="telefoon" class="form-control" required />
                                <br />
                                <label>User type</label>
                                <select name="usertype" class="form-control" required>
                                    <option>-Selecteer-</option>
                                    <option>mdw</option>
                                    <option>admin</option>
                                    <option>klant</option>
                                </select>
                                <br />
                                <input type="submit" name="toevoegen" class="btn btn-info" value="Save" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>