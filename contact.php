<?php
// hier wordt de sessie gestart
session_start();
// hier maak ik variabelen voor de contact form
// zodat sommige fields al gevuld zijn als gebruikers ingelogd zijn
$username = '';
$email = '';
if ($_SESSION) {
	$email = $_SESSION['email'];
	$username = $_SESSION['username'];
}
$result = "";
// hier wordt een mail functie gemaakt zodat de mail versuurd kan worden.
if (isset($_POST['submit'])) {
	require 'phpmailer/PHPMailerAutoload.php';
	$mail = new PHPMailer;

	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Username = 'zeilschoolwm@gmail.com';
	$mail->Password = 'Jaguar.12';

	$mail->setFrom($_POST['email'], $_POST['name']);
	$mail->addAddress('zeilschoolwm@gmail.com');
	$mail->addReplyTo($_POST['email'], $_POST['name']);

	$mail->isHTML(true);
	$mail->Subject = 'Contact form:' . $_POST['subject'];
	$mail->Body = '<h3>Naam :' . $_POST['name'] . '<br>Email :' . $_POST['email'] . '<br> Onderwerp :' . $_POST['subject'] .
		'<br>Text :' . $_POST['msg'] . '</h3>';

	if (!$mail->send()) {
		$result = "Er is iets miss gegaan. Probeer opnieuw";
	} else {
		$result = "Dankjewel voor je bericht " . $_POST['name'] . ". We nemen zo spoedig mogelijk contact met je op.";
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
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
	<script src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
        <?php
        // hier doe ik twee checks eentje is of de sessie gezet is 
        // als sessie een admin is, hij krijgt toegaan tot de admin panel
        if ($_SESSION) {
          if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'mdw') { ?>
            <li><a href="reserveringen.php">Reserveringen</a></li>
            <li><a href="kok.php">Kok overzicht</a></li>
        <?php }
        } ?>
        <li class="display-1 active"><a href="contact.php">Contact</a></li>
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
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<h1 class="text-center font-weight-bold text-primary">Neem contact met ons op</h1>
				<hr class="bg-light">
				<h5 class="text-center text-success"><?= $result; ?></h5>
				<form action="" method="post" id="form-box" class="p-2">
					<div class="form-group input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" name="name" class="form-control" placeholder="Uw naam" required style="width:360px;" value="<?= $username; ?>">
					</div>
					<div class="form-group input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-envelope"></i></span>
						</div>
						<input type="email" name="email" class="form-control" placeholder="Uw email" required style="width:360px;" value="<?= $email; ?>">
					</div>
					<div class="form-group input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-at"></i></span>
						</div>
						<input type="text" name="subject" class="form-control" placeholder="Onderwerp" required style="width:360px;">
					</div>
					<div class="form-group input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-comment-alt"></i></span>
						</div>
						<textarea name="msg" id="msg" class="form-control" placeholder="Stel hier uw vraag." cols="30" rows="4" required style="width:360px;"></textarea>
					</div>
					<div class="form-group">
						<input type="submit" name="submit" id="submit" class="btn btn-primary btn-block" value="Send">
					</div>
				</form>
			</div>
		</div>
	</div>
</body>

</html>