<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

include __DIR__ . "/database.php";
include __DIR__ . "/common.php";
include __DIR__ . "/Product.php";

session_start();
$isLoggedin = isSession(['user']);

if (!$isLoggedin) {
    header("Location: login.php");
}

$db = getDatabaseConnection();

$userID = $db->prepare("SELECT userID FROM user WHERE username = :username");
$userID->bindParam(":username", $_SESSION["user"]);
$userID->execute();
$userID = $userID->fetch();

//if(!isset($userID)){
//    $userID = $db->query("SELECT employeeID FROM employee WHERE username = '" . $_SESSION["user"] . "'");
//    $userID->execute();
//    $userID = $userID->fetch();
//    if(!$userID){
//        die("Error: User not found");
//    }
//}

$bestellingen = $db->prepare("SELECT * FROM `order` WHERE userID = :id");
$bestellingen->bindParam(":id", $userID["userID"]);
$bestellingen->execute();
$bestellingen = $bestellingen->fetchAll();





?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bestellingen</title>
    <link rel="stylesheet" href="index.css" type="text/css">
    <link rel="stylesheet" href="bestellingen.css" type="text/css">
</head>
<body>
<?php include "navbar.php" ?>

<main>
    <h1>Bestellingen</h1>
    <?php foreach ($bestellingen as $bestelling): ?>
        <?php
            $orderxprodcut = $db->prepare("SELECT * FROM orderproduct WHERE orderID = :id");

            $orderxprodcut->bindParam(":id", $bestelling["orderID"]);
            $orderxprodcut->execute();
            $orderxprodcut = $orderxprodcut->fetchAll();
        ?>
    <table id="products">
        <tr>
            <th>Bestelling</th>
            <th>Naam</th>
            <th>Prijs</th>
            <th>Aantal</th>
        </tr>
        <?php foreach($orderxprodcut as $products): ?>
            <?php
            $product = $db->prepare("SELECT * FROM product WHERE productID = :id");
            $product->bindParam(":id", $products["productID"]);
            $product->execute();
            $product = $product->fetch();
            ?>
            <tr>
                <td><?= $bestelling["orderID"] ?></td>
                <td><?= $product["name"] ?></td>
                <td><?= $product["price"] ?></td>
                <td><?= $products["amount"] ?></td>

            </tr>
        <?php endforeach; ?>
    </table>
    <br/>
    <br/>
    <?php endforeach; ?>
</main>


</body>
</html>
