<?php
    include "database.php";
    $hasBeenSubmitted = false;
    if(
        isset($_POST['email']) and
        isset($_POST['password']) and
        isset($_POST['password2'])
    ){
        $hasBeenSubmitted = true;
    }
    $error = "";
    $conn = getDatabaseConnection();

    if($hasBeenSubmitted){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if($password != $password2){
            $error = "Wachtwoorden komen niet overeen";
        }

        if(strlen($error) == 0){
            $query = $conn->prepare("SELECT * FROM `webshop`.user WHERE `webshop`.user.username = :email");
            $query->bindParam(":email", $email);
            $query->execute();
            $user = $query->fetch();
            if($user){
                $error = "Email is al in gebruik";
            }
        }

        if(strlen($error) == 0){
            $query = $conn->prepare("INSERT INTO `webshop`.user (username, passwordHash) VALUES (:email, :passwordHash)");
            $query->bindParam(":email", $email);
            $query->bindParam(":passwordHash", $hashedPassword);
            $query->execute();
            session_start();
            $_SESSION["user"] = $email;
            header("Location: index.php");
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="login.css">
    <title>Registreer</title>
</head>
<body>
<div id="mainLogin">
    <div>
        <form action="registreer.php" method="post">
            <h1>Registreer</h1>
            <label for="email">Gebruikersnaam</label>
            <input name="email" type="email" id="email" required>
            <label for="password">Wachtwoord</label>
            <input id="password" name="password" type="password">

            <label for="password2">Herhaal Wachtwoord</label>
            <input id="password2" name="password2" type="password">

            <input type="hidden" value="true" name="hasBeenSubmitted">

            <div id="buttonsContainer">
                <input type="submit" value="Registreer">
                <input type="reset" name="reset">
                <a href="login.php">Ga terug</a>
            </div>
        </form>

        <div id="error">
            <?php
                if(strlen($error) > 0){
                    echo $error;
                }
            ?>
        </div>
    </div>
</div>
</body>
</html>
