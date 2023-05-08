<?php
    require_once "database.php";
    if(!isset($_GET["product"])){
        header("Location: index.php");
    }
    $product = $_GET["product"];
    $db = getDatabaseConnection();
    $query = $db->query("SELECT * FROM `webshop`.product WHERE `webshop`.product.Name  = '$product'");
    $product = $query->fetch();


?>
if(isset($_SESSION["winkelmandje"][$index]) and $_SESSION["winkelmandje"][$index]["id"] == $productId and $_SESSION["winkelmandje"][$index]["aantal"] == $aantal){
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="krijgProduct.css">
    <link rel="stylesheet" href="navbar/navbar.css">
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
<!--    --><?php
//    if($product){
//        echo "<h1>".$product["name"]."</h1>";
//        echo "<p>".$product["description"]."</p>";
//        echo "<p>".$product["price"]."</p>";
//    }else{
//        echo "<h1>Product niet gevonden</h1>";
//    }?>
    <div class="parent">
        <div class="child img-container">
<!--            <img src="--><?//=$product["imageUrl"] ?><!--" alt="--><?//=$product["name"]?><!--">-->
            <div>
                <img src="https://via.placeholder.com/256" alt="placeholder">
            </div>
        </div>
        <div class="child price-container">
            <h1><?=$product["name"]?></h1>
            <hr>
            <p><?=$product["description"]?> Euro</p>
            <br>
            <p><?=$product["price"]?></p>
            <div>
                <form>
                    <input type="number" name="aantal" value="1">
                    <input type="hidden" name="product" value="<?=$product["name"]?>">
                    <input type="submit" value="Add to cart">
                </form>
            </div>
        </div>
    </div>
</main>
</body>
</html>
