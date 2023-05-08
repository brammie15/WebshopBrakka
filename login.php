<?php
include "database.php";
include "common.php";

$db = getDatabaseConnection();
$db_success = false;
if ($db) {
    $db_success = true;
}

$hasSubmitted = false;
if (
    isset($_POST["email"]) and
    isset($_POST["password"])
) {
    $hasSubmitted = true;
}
$error = "";
$code = "";
if ($hasSubmitted) {
    $password = $_POST["password"];
    $email = $_POST["email"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $accountType = UserTypes::Customer;

    $userQuery = $db->query("SELECT user.email, user.passwordHash FROM `webshop`.user WHERE `webshop`.user.email = '$email'");
    $user = $userQuery->fetch();

    $employeeQuery = $db->query("SELECT employee.email, employee.passwordHash FROM `webshop`.employee WHERE `webshop`.employee.email = '$email'");
    $employee = $employeeQuery->fetch();

    if (!$user && !$employee) {
        $error = "Email or password is incorrect, User not found";
    }

    if (!$user and $employee) {
        $user = $employee;
        $accountType = UserTypes::Employee;
    }


    if (strlen($error) == 0) {
        $userPassword = $user['passwordHash'];
        if (!password_verify($password, $userPassword)) {
            $error = "Email or password is incorrect, Password Incorrect";
        }
    }
    if (strlen($error) == 0) {
        session_start();
        $_SESSION["user"] = $email;
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
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>
<body>
<div id="mainLogin">
    <div>
        <form action="login.php" method="post">
            <h1>Login</h1>
            <label for="email">Email</label>
            <input name="email" type="text" id="email" required>
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
        if (strlen($error) > 0) {
            echo "<p id='error'>" . $error . "</p>";
        }
        ?>
    </div>
</div>
</body>
</html>