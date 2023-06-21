<?php
// sessie wordt gestart
session_start();
// db connectie
include_once("database_conn.php");
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
        <li class="active"><a href="index.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
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
  <!-- Hier zijn de fotos die in het begin scherm zijn, met buttos dat gelinkt zijn naar de verschillende paginas -->
  <div class="container">
    <div class="container-fluid">
      <div class="container">
        <h2>Excellent Taste</h2>
        <p>Korte beschrijving</p>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <h3>wil je iets uit onze prachtige menu bestellen?</h3>
          <h5>Dan klik op de onderste button</h5>
          <img src="eten/menu.jpg" style="width: 500px">
          <a href="menu.php" class="btn btn-info">Menu Bekijken</a>

        </div>
        <div class="col-lg-6">
          <h3>Wil je een van onze boten huren?</h3>
          <h5>Dan klik op de onderste button</h5>
          <img src="eten/tafels.jpg" style="width: 600px">
          <a href="reserveringen.php" class="reserv btn btn-info">Reserveringen</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>