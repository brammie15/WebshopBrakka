<?php
    include "database.php";
    $db = getDatabaseConnection();
    $db_success = false;
    if($db){
        $db_success = true;
    }


    session_start();
    if(isset($_SESSION['user'])){
        $listProduct = $_SESSION['user'];
    }
    if(isset($_GET['product']) && isset($_GET['aantal']) && isset($_SESSION['user'])){
        $productId = $_GET['product'];
        $aantal = $_GET['aantal'];
        $query = $db->query("SELECT * FROM `webshop`.product WHERE name = '$productId'");
        $product = $query->fetch();
        if(!isset($_SESSION['winkelmandje'])){
            $_SESSION['winkelmandje'] = [];
        }
        //check if product is already in the cart
        $productInCart = false;
        foreach($_SESSION['winkelmandje'] as $key => $product){
            if($product['id'] == $productId){
                $productInCart = true;
                $_SESSION['winkelmandje'][$key]['aantal'] += $aantal;
            }
        }
        if(!$productInCart){
            $_SESSION['winkelmandje'][] = [
                'id' => $productId,
                'aantal' => $aantal,
            ];
        }
    }elseif(isset($_GET['product']) && isset($_GET['aantal']) && !isset($_SESSION['user'])){
        header("Location: login.php");
    }

    function createCard($id, $naam, $prijs): string {
        $product_url = "onclick=\"location.href='krijgProduct.php?product=$naam'\"";

        return <<<HTML
        <div class="productCard">
            <img src="https://via.placeholder.com/256" alt="placeholder">
            <h2>$naam</h2>
            <p>$prijs Euro</p>
            <form action="index.php" method="get">
                <input type="number" name="aantal" value="1">
                <input type="hidden" name="product" value="$id">
                <input type="submit" value="Add to cart">
            </form>
            <form>
                <input type="button" value="Details" $product_url>
            </form>
            
        </div>
HTML;

    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="index.css">
    <title>Webshop</title>
</head>
<body><?php
    include "navbar.php";
?>

<main>

    <div id="top">
        <?php if(isset($_SESSION['user'])): ?>
            <h1 id="welkomText">Welkom <?= $_SESSION['user'] ?> En welkom</h1>
        <?php endif; ?>
        <h3>Welkom op de website van de webshop</h3>
        <p>Op deze website kunt u producten kopen</p>
    </div>

    <div id="productsContainer">
        <?php
        if ($db_success){
            $query = $db->query("SELECT * FROM `webshop`.product");
            $products = $query->fetchAll();
            foreach ($products as $listProduct){
                echo createCard($listProduct['productID'], $listProduct["name"], $listProduct['price']);
            }
        }
        ?>
    </div>


</main>

</body>
</html>
