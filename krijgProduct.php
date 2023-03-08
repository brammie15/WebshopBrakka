<?php
    require_once "database.php";
    if(!isset($_GET["product"])){
        header("Location: index.php");
    }
    $product = $_GET["product"];
    $db = getDatabaseConnection();
    $query = $db->query("SELECT * FROM `shop`.products WHERE name = '$product'");
    $product = $query->fetch();


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <?php if(isset($_SESSION['user'])): ?>
            <li><a href="logout.php">Logout</a></li>
            <!--            <li><p id="welkomText">Welkom --><?php //=$_SESSION['user']?><!--</p></li>-->
            <li><a href="winkelmandje.php">WinkelMandje</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
    <?php
    if($product){
        echo "<h1>".$product["name"]."</h1>";
        echo "<p>".$product["description"]."</p>";
        echo "<p>".$product["price"]."</p>";
    }else{
        echo "<h1>Product niet gevonden</h1>";
    }?>
</main>

</body>
</html>
