<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>
<body>
<?php
    include "database.php";
    $db = getDatabaseConnection();
    $db_success = false;
    if($db){
        $db_success = true;
    }

$hasSubmitted = false;
if(
    isset($_POST["email"]) and
    isset($_POST["password"])
){
    $hasSubmitted = true;
}
$error = "";
$code = "";
if($hasSubmitted){
    $password = $_POST["password"];
    $email = $_POST["email"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $query = $db->query("SELECT * FROM `shop`.users WHERE email = '$email'");
    $user = $query->fetch();
    $userPassword = $user['password'];

    if(!password_verify($password, $userPassword)){
        $error = "Email or password is incorrect";
    }

    if(strlen($error) == 0){
        session_start();
        $_SESSION["user"] = $email;
        header("Location: index.php");
    }
}
?>
<div id="mainLogin">
    <div>
        <form action="login.php" method="post">
            <h1>Login</h1>
            <label for="email">Email</label>
            <input name="email" type="email" id="email" required>
            <label for="password">Password</label>
            <input id="password" name="password" type="password">
            <br>
            <div id="buttonsContainer">
                <input type="submit" value="Login">
                <input type="reset" name="reset">
            </div>
            <div>
                <p>Nog geen account?</p>
                <a href="registreer.php">Registreer</a>
            </div>
        </form>
        <?php
        if(strlen($error) > 0){
            echo "<p id='error'>".$error."</p>";
        }
        ?>
    </div>
</div>
</body>
</html>