<?php
include "database.php";
include "common.php";

$db = getDatabaseConnection();

$error = "";
if (isPost(["username","password"])) {
    $password = $_POST["password"];
    $username = $_POST["username"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $accountType = UserTypes::Customer;

    $userQuery = $db->query("SELECT user.username, user.passwordHash FROM `webshop`.user WHERE `webshop`.user.username = '$username'");
    $user = $userQuery->fetch();

    $employeeQuery = $db->query("SELECT employee.username, employee.passwordHash FROM `webshop`.employee WHERE `webshop`.employee.username = '$username'");
    $employee = $employeeQuery->fetch();

    if (!$user && !$employee) {
        $error = "Gebruiker niet gevonden";
    }

    if (!$user and $employee) {
        $user = $employee;
        $accountType = UserTypes::Employee;
    }


    if (strlen($error) == 0) {
        $userPassword = $user['passwordHash'];
        if (!password_verify($password, $userPassword)) {
            $error = "Email of Wachtwoord is incorrect";
        }
    }
    if (strlen($error) == 0) {
        session_start();
        $_SESSION["user"] = $username;
        $_SESSION["userType"] = $accountType;
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
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<main>
    <form action="login.php" method="post">
        <h1 style="font-size: 3em;">Login</h1>
        <label for="Username">Gebruikersnaam<span style="color: red">*</span></label>
        <input type="text" name="username" id="Username" required>
        <label for="password">Wachtwoord<span style="color: red">*</span></label>
        <input type="password" name="password" id="password" required>

        <hr>

        <input type="submit" value="Login">
        <p>Geen account? <a href="registreer.php">Registreer</a></p>
        <?php if($error != ""): ?>
            <p class="error" style=""><?php echo $error; ?></p>
        <?php endif; ?>

    </form>
</main>
</body>
</html>