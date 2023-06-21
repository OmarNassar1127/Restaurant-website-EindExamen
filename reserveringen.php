<?php
// hier start ik de sessie
session_start();
// deze is de database connectie
include_once("database_conn.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Excellent Taste</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
  <link rel="stylesheet" type="text/css" href="style/style.css">
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
        <?php
        // hier doe ik twee checks eentje is of de sessie gezet is 
        // als sessie een admin is, hij krijgt toegaan tot de admin panel
        if ($_SESSION) {
          if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'mdw') { ?>
            <li class="active"><a href="reserveringen.php">Reserveringen</a></li>
            <li><a href="kok.php">Kok overzicht</a></li>
        <?php }
        } ?>
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
    <h2 class="align-content-center">Reserveringen</h2>
    <H4 class="align-content-center">Hier kan je reservieringen plaatsen</H4>
    <div style="color:red">
      <?php if (isset($_SESSION['bezet'])) {
        echo $_SESSION['bezet'];
        unset($_SESSION['bezet']);
      } ?>
    </div>
    <div class="row justify-content-center">
      <table class="table">
        <thead>
          <tr>
            <th>Tafel id</th>
            <th>Tafel nmr</th>
            <th>Naam v.d persoon</th>
            <th>Telefoon nmr</th>
            <th>Aantal mensen</th>
            <th>Tijd</th>
            <th>Datum</th>
            <th>Drankjes</th>
          </tr>
        </thead>
        <?php
        // hier maak ik gebruik van sql statment om alles te selecteren van het tabel `reserveringen`
        $sql = "SELECT * FROM `reserveringen`";
        $statement = $db_conn->prepare($sql);
        $statement->execute();
        $reserveringen = $statement->fetchAll(PDO::FETCH_ASSOC);
        // bij deze loop ik er om heen om alles op de scherm te tonen
        foreach ($reserveringen as $row) :
        ?>
          <tr>
          <td>
              <?php echo $row['tafel_id']; ?>
            </td>
            <td>
              <?php echo $row['tafel_nmr']; ?>
            </td>
            <td>
              <?php echo $row['naam_persoon']; ?>
            </td>
            <td>
              <?php echo $row['telefoon']; ?>
            </td>
            <td>
              <?php echo $row['aantal_mensen']; ?>
            </td>
            <td>
              <?php echo $row['tijd']; ?>
            </td>
            <td>
              <?php echo $row['datum']; ?>
            </td>
            <td>
              <?php echo $row['drankjes']; ?>
            </td>
            <?php
            // er wordt een check gedaan voor de sessie en als de sessie admin of mdw is dan krijgt ie de volgende buttons te zien (Edit en Verwijderen)
            if ($_SESSION) {
              if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'mdw') { ?>
                <td>
                  <a href="CRUDtafels/edit.php?id=<?= $row['tafel_id'] ?>" class="btn btn-info">Edit
                  </a>
                  <a href="CRUDtafels/proces.php?verwijder=<?php echo $row['tafel_id']; ?>" class="btn btn-danger">Verwijderen
                  </a>
                  <a href="menu.php?reservering=<?php echo $row['tafel_id'] ?>" class="button button4">Bestellen
                  </a>
                </td>
            <?php }
            } ?>
          </tr>
        <?php endforeach; ?>
      </table>
      <?php
      // er wordt een check gedaan voor de sessie en als de sessie admin of mdw is dan krijgt ie de volgende button te zien (Toevoege)
      if ($_SESSION) {
        if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'mdw') { ?>
          <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin-bottom: 20px">
            Voeg een nieuwe tafel toe
          </a>
      <?php }
      } ?>
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
              <!-- Deze form is verbonden an de bestand proces.php zodat de informatie dat je hier vult direct naar de database gaat -->
              <form action="CRUDtafels/proces.php" method="post" class="col-xs-3">
                <label>Tafel nmr</label>
                <input type="text" name="tafel_nmr" class="form-control" required />
                <br />
                <label>Naam v.d persoon</label>
                <input type="text" name="naam_persoon" class="form-control" required />
                <br />
                <label>Telefoon </label>
                <input type="text" name="telefoon" class="form-control" required />
                <br />
                <label>Aantal mensen</label>
                <input type="text" name="aantal_mensen" class="form-control" required />
                <br />
                <label>Tijd </label>
                <input type="time" name="tijd" class="form-control" required />
                <br />
                <label>Datum</label>
                <input type="date" name="datum" class="form-control" required />
                <br />
                <label>Welke drankjes</label>
                <input type="text" name="welke_drankjes" class="form-control" required />
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