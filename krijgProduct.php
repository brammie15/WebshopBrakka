<?php
    require_once "database.php";
    include __DIR__ . "/Product.php";
    if(!isset($_GET["product"])){
        header("Location: index.php");
    }
    $productId = $_GET["product"];
    $db = getDatabaseConnection();
    $product = Product::fromId($db, $productId);


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="krijgProduct.css">
    <title><?= $product->name ?></title>
</head>
<body>
<?php include "navbar.php" ?>


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
            <h1><?=$product->name?></h1>
            <hr>
            <p><?=$product->description?></p>
            <br>
            <p><?=$product->price?> Euro</p>
            <div>
                <form action="index.php" method="get">
                    <input type="number" name="aantal" value="1">
                    <input type="hidden" name="product" value="<?=$product->id?>">
                    <input type="submit" value="Add to cart">
                </form>
            </div>
        </div>
    </div>
</main>
</body>
</html>
