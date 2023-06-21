<?php
// hier start ik de sessie
session_start();
// met deze maak ik db conectie
include_once("database_conn.php");

if (isset($_GET['reservering'])) {
  $tafel_id = $_GET['reservering'];
  $_SESSION['tafel_id'] = $tafel_id;
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Excellent Taste Menu</title>
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
        <li class="active"><a href="menu.php">Menu</a></li>
        <?php
        // hier doe ik twee checks eentje is of de sessie gezet is 
        // als sessie een admin is, hij krijgt toegaan tot de admin panel
        if ($_SESSION) {
          if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'mdw') { ?>
            <li><a href="reserveringen.php">Reserveringen</a></li>
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
  <?php
  // deze functie is om een product uit de menu te kunnen bestellen
  if (isset($_POST['kopen'])) {
    // hier check ik eerst als de sessie gezet is, als het zo is dan de gebruiker zou wel kunnen kopen
    if ($_SESSION) {
      $id = $_POST['menu']; // deze is de id van het eten/drink
      $reservering = $_GET['reservering'];
      $gemaakt = "In behandeling";

      // hier maak ik gebruik van prepared stmt om de website veileger te maken
      $sql = "INSERT INTO `menulijst`(`tafel_id`, `menu_id`, `gemaakt`) VALUES (:reservering, :id, :gemaakt)";
      $stmt = $db_conn->prepare($sql); //stuur naar mysql.
      $stmt->bindParam(":reservering", $reservering);
      $stmt->bindParam(":id", $id);
      $stmt->bindParam(":gemaakt", $gemaakt);
      $stmt->execute();

  ?>
      <div id="kopen" class="alert2">
        <span class="closebtn2" onclick="this.parentElement.style.display='none';">&times;</span>
        <strong>Succes!</strong> Toegevoegd aan winkelmand.
      </div>
    <?php
    } else { ?>
      <!--  maar als de sessie niet gezet is dan de klant zou een melding ktijgen 
   en er zou verteld worden dat ie eerst ingelogd moet zijn om iets te kunnen kopen. -->
      <div class="alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <strong>Danger!</strong> Om een prodcut te kunnen bestellen moet je eerst ingelogd zijn.
        <a style="color: black" href="login/pdo_login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a>
      </div>
  <?php }
  } ?>
  <!-- Met deze stukje code laat ik de div 'Succes' verdwijnen na 2 seconden -->
  <script type="text/javascript">
    setTimeout(fade_out, 2000);

    function fade_out() {
      $("#kopen").fadeOut().empty();
    }
  </script>
  <div class="container">
    <h2 class="align-content-center">Menu</h2>
      <?php
      // er wordt een check gedaan voor de sessie en als de sessie admin of mdw is dan krijgt ie de volgende buttons te zien (Edit en Verwijderen)
        if ($_SESSION) {
          if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'mdw') { ?>
            <a href="basket/index.php?reservering=<?php echo $reservering; ?>" class="button button4">Bestellingen</a>
            <?php }
        } ?>
    <!-- Hier maak ik gebruik van JQuery zodat de klant door de verschillende type gerechten en / of dranken kan sorteren -->
    <!-- Als de gebruiker de select bar heeft gebruikt de informatie wordt versturd naar de pagina fetch.php  -->
    <script src="jquery.js"></script>
    <script>
      $(document).ready(function() {
        $("#fetchval").on('change', function() {
          var keyword = $(this).val();
          $.ajax({
            url: 'fetch.php',
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
    <div style="display: inline-block">Sorteren op type gerecht en/of drank :</div>
    <!-- Deze is de select bar alles wat hier gekozen is gaat naar de functie van boven en wordt naar andere pagina verstuurd -->
    <select id="fetchval" name="fetchby" style="display: inline-block">
      <option value="">---Kies---</option>
      <option value="1">Koude gerechten</option>
      <option value="2">Warme gerechten</option>
      <option value="3">Drank</option>
    </select>
    <button onclick='window.location.reload();'>Hele menu</button>
    <br>
    <div id="table-container">
      <table class="table">
        <thead>
          <tr>
            <th></th>
            <th width="200">Gerecht Naam</th>
            <th>Parijs</th>
            <th>Omschrijving</th>
            <th></th>
          </tr>
        </thead>
        <?php
        // hier maak ik gebruik van sql statment om alles te selecteren van het tabel `menu`
        $sql = "SELECT * FROM `menu`";
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
            <td>
              <div style="display:flex">
                <?php
                // er wordt een check gedaan voor de sessie en als de sessie admin of mdw is dan krijgt ie de volgende buttons te zien (Edit en Verwijderen)
                if ($_SESSION) {
                  if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'mdw') { ?>
                    <a href="CRUDmenu/edit.php?id=<?= $row['menu_id'] ?>" class="buttonSpace btn btn-info">Edit
                    </a>
                    <a href="CRUDmenu/proces.php?verwijder=<?php echo $row['menu_id']; ?>" class="buttonSpace btn btn-danger">Verwijderen
                    </a>
                    <form method="post">
                      <input type="hidden" name="menu" value='<?php echo $row["menu_id"] ?>'>
                      <input type="submit" class="button button4" name="kopen" value="Kopen">
                    </form>
                <?php }
                } ?>
                <!-- Met deze button kunnen de klanten de product kopen -->
              </div>

            </td>

          </tr>
        <?php endforeach; ?>
      </table>

    </div>
    <?php
    // er wordt een check gedaan voor de sessie en als de sessie admin of mdw is dan krijgt ie de volgende button te zien (Toevoege)
    if ($_SESSION) {
      if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'mdw') { ?>
        <a type=" button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin-bottom: 20px">
          Voeg een nieuwe gerecht toe
        </a>
    <?php }
    } ?>
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
              <form action="CRUDmenu/proces.php" method="post" class="col-xs-3">
                <label>Gerecht Naam</label>
                <input type="text" name="gerecht_naam" class="form-control" required />
                <br />
                <label>Gerecht Prijs</label>
                <input type="number" name="gerecht_prijs" class="form-control" required />
                <br />
                <label>Omschrijving</label>
                <input type="text" name="omschrijving" class="form-control" required />
                <br />
                <label>Type eten</label>
                <select name="type_eten" required>
                  <option>-Selecteer-</option>
                  <option value="1">Koude eten</option>
                  <option value="2">Warme eten</option>
                  <option value="3">Drank</option>
                </select>
                <br />
                <label>Afbeelding</label>
                <input type="file" name="afbeelding" class="form-control" required />
                <br />
                <input type="submit" name="toevoegen" class="btn btn-info" value="Save" />
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</body>

</html>