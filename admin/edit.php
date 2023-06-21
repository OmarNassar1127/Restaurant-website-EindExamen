<?php
// sessie wordt gestart
session_start();
// db connectie wordt gemaakt
include_once("../database_conn.php");
// dezee functie zorgt dat de informatie van de menu verandert kan weorden (edit functie) 
if (isset($_POST['e_verzend'])) {
    $id = $_POST['e_id'];
    $username = $_POST['e_username'];
    $email = $_POST['e_email'];
    $telefoon = $_POST['e_telefoon'];
    $user_type = $_POST['e_usert'];
    // hier wordt de sql statement uitgevoerd
    // hij checkt eerst dat de variable username niet leeg is en dat voert ie de hele ding uit
    if (!empty($username)) {
        try {
            $stmt = $db_conn->prepare("UPDATE `users` SET `username`= :username, `email`= :email, `telefoon`=:telefoon,`user_type`= :user_type WHERE user_id = :id");
            $stmt->execute(array(':username' => $username, ':email' => $email, ':telefoon' => $telefoon, ':user_type' => $user_type, ':id' => $id));
            if ($stmt) {
                header("location:index.php");
            }
        } catch (PDOException $ex) {
            echo $exo->getMessage();
        }
    } else {
        echo "Input field";
    }
}
// deze variabelen zorgen dat als iets miss me gaat met het krijgen van de informatie dat het een lege string komt en niet een error message
$id = 0;
$username = '';
$email = '';
$telefoon = '';
$user_type = '';
// met deze functie wordt de informatie van de database opgehald en in variabelen gezet.
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db_conn->prepare('SELECT * FROM `users` WHERE user_id= :id');
    $stmt->execute(array(':id' => $id));
    $row = $stmt->fetch();
    $id = $row['user_id'];
    $username = $row['username'];
    $email = $row['email'];
    $telefoon = $row['telefoon'];
    $user_type = $row['user_type'];
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>edit</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <h2>Account updaten</h2>
            <!-- In deze formulier worden de variabelen die we uit de db gehald hadden gebruikt -->
            <!-- Maar ook wordt hier de nieuwe informatie verstuurd naar de eerste functie zodat je de informatie van de gebruiker aan kan passen -->
            <form method="post">
                <table class="table">
                    <tr>
                        <td>Username:</td>
                        <td>
                            <input type="text" name="e_username" value="<?= $username; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>
                            <input type="text" name="e_email" value="<?= $email; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Telefoon:</td>
                        <td>
                            <input type="text" name="e_telefoon" value="<?= $telefoon; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>User type:</td>
                        <td>
                            <select name="e_usert" value="<?= $user_type; ?>">
                                <option><?php echo $row['user_type'] ?></option>
                                <option>-Selecteer-</option>
                                <option>mdw</option>
                                <option>admin</option>
                                <option>klant</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="e_id" value="<?= $id; ?>">
                        </td>
                        <td>
                            <input type="submit" class="btn btn-info" name="e_verzend">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>

</html>