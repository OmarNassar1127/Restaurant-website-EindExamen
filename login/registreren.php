<?php
// sessie wordt gestart
session_start();
//db connectie 
$user = 'root';
$pass = '';
$db_conn = new PDO('mysql:host=localhost;dbname=excellent', $user, $pass);
// hier wordt de informatie naar de database gestuurd
// in deze functie gaat de gebruiker een account kunnen maken
if (isset($_POST['login'])) {
    $naam = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $telefoon = $_POST['telefoon'];

    // hier maak ik gebruik van prepared stmt om de website veileger te maken
    $sql = "INSERT INTO `users`( `username`, `password`, `email`,`telefoon` ,`user_type`) VALUES (:naam, :password, :email, :telefoon, :type )";
    $stmt = $db_conn->prepare($sql); //stuur naar mysql.
    $stmt->bindParam(":naam", $naam);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":telefoon", $telefoon);
    $stmt->bindParam(":type", $type);
    $stmt->execute();

    $_SESSION['message'] = "Je account is gemakt!";
    $_SESSION['msg_type'] = "success";

    header("refresh:6;url=pdo_login.php");
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Account makken</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php
    if (isset($_SESSION['message'])) : ?>

        <div class="alert alert-<?= $_SESSION['msg_type'] ?>">

            <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?>
        </div>
    <?php endif ?>
    <br />
    <div class="container" style="width:500px;">
        <h3 align="">Registreren</h3>
        <br />
        <!-- Deze is de form die gaat zorgen dat de informatie dat hier gevuld wordt naar de bovenste functie gestuurd wordt en naar de db gaat -->
        <form method="post">
            <label>Naam</label>
            <input type="text" name="username" class="form-control" required />
            <br />
            <label>Email</label>
            <input type="email" name="email" class="form-control" required />
            <br />
            <label>Telefoon</label>
            <input type="text" name="telefoon" class="form-control" required />
            <br />
            <label>User type</label>
            <input type="text" name="type" class="form-control disabled" value="klant" readonly>
            <br />
            <label>Password</label>
            <input type="password" name="password" class="form-control" required />
            <br />
            <input type="submit" name="login" class="btn btn-info" value="Registreren" />
        </form>
    </div>
    <br />
</body>

</html>