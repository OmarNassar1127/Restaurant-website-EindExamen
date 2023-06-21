<?php
// sessie wordt gezet
session_start();
// db connectie
include_once("database_conn.php");
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
</head>
<table class="table">
  <thead>
    <tr>
      <th></th>
      <th width="200">Gerecht Naam</th>
      <th>Parijs</th>
      <th>Omschrijving</th>
      <th width="250"></th>
    </tr>
  </thead>
  <?php
  // hier wordt de data verwerkt (die we gekregen hebben van menu.php)
  // de menu wordt gesorterd en laat zien alleen de sort dat de gebruiker gekozen heeft
  if (isset($_POST['request'])) {
    $request = $_POST['request'];
    $sql = "SELECT * FROM `menu` WHERE menuType='$request'";
    $statement = $db_conn->prepare($sql);
    $statement->execute();
    $menu = $statement->fetchAll(PDO::FETCH_ASSOC);
    // $query = $db_conn->query("SELECT * FROM `menu` WHERE menuType='$request'");
  }
  // hier maak ik gebruik van sql statment om alles te selecteren van het tabel `menu`
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
        <?php
        // er wordt een check gedaan voor de sessie en als de sessie admin of mdw is dan krijgt ie de volgende buttons te zien (Edit en Verwijderen)
        if ($_SESSION) {
          if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'mdw') { ?>
            <a href="CRUDmenu/edit.php?id=<?= $row['menu_id'] ?>" class="btn btn-info">Edit
            </a>
            <a href="CRUDmenu/proces.php?verwijder=<?php echo $row['menu_id']; ?>" class="btn btn-danger">Verwijderen
            </a>
        <?php }
        } ?>
        <!-- Met deze button kunnen de klanten de product kopen -->
        <form method="post">
          <input type="hidden" name="menu" value='<?php echo $row["menu_id"] ?>'>
          <input type="submit" class="glyphicon glyphicon-shopping-cart button button4" name="kopen" value="Kopen">
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>