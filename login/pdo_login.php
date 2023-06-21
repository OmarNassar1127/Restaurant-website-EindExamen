<?php
// sessie wordt gestart
session_start();
// hier wordt een db connectie gemaakt 
$host = "localhost";
$username = "root";
$password = "";
$database = "excellent";
$message = "";
try {
     $connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);
     $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     // als de gebruiker op button login drukt dan wordt de wachtwoord en de email vergelijkt met de db gegevens en er worden een aantal dingen in de sessie gezet 
     if (isset($_POST["login"])) {
          if (empty($_POST["username"]) || empty($_POST["password"])) {
               $message = '<label>All fields are required</label>';
          } else {
               $query = "SELECT * FROM users WHERE username = :username AND password = :password";
               $statement = $connect->prepare($query);
               $statement->execute(
                    array(
                         'username'     =>     $_POST["username"],
                         'password'     =>     $_POST["password"]
                    )
               );
               $count = $statement->rowCount();
               if ($count > 0) {
                    // deze variabelen worden allemaal in de sessie gezet.
                    $row = $statement->fetch();
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["user_type"] = $row["user_type"];
                    $_SESSION["user_id"] = $row["user_id"];
                    $_SESSION["email"] = $row["email"];
                    $_SESSION["telefoon"] = $row["telefoon"];

                    header("location:../index.php");
               } else {
                    // Als de inlog proces fout is gegaan de user krigt de volgende melding
                    $message = '<label>Verkeerde gegevens</label>';
               }
          }
     }
} catch (PDOException $error) {
     $message = $error->getMessage();
}

?>
<!DOCTYPE html>
<html>

<head>
     <title>Inloggen</title>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
     <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
     <br />
     <div class="container" style="width:500px;">
          <?php
          // als iets fout gaat in de inloggen zou een bericht getoond worden
          if (isset($message)) {
               echo '<label class="text-danger">' . $message . '</label>';
          }
          ?>
          <h3 align="">Inlogen</h3><br />
          <!-- Hier is de formulier waar de gebruiker kan inlogen -->
          <form method="post">
               <label>Username</label>
               <input type="text" name="username" class="form-control" />
               <br />
               <label>Password</label>
               <input type="password" name="password" class="form-control" />
               <br />
               <input type="submit" name="login" class="btn btn-info" value="Login" />
          </form>
          <div>
               <!-- Als de gebruiker geen account heeft dan kan ie naar de regeistratie pagina gaan en vervolgens een accunt maken -->
               <h3 style="display: inline-block">Nog geen account?</h3>
               <a href="registreren.php">
                    <input type="submit" class="btn btn-info" value="Registreren" />
               </a>
          </div>
     </div>
     <br />
</body>

</html>