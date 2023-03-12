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
        $productNaam = $_GET['product'];
        $aantal = $_GET['aantal'];
        $query = $db->query("SELECT * FROM `webshop`.product WHERE name = '$productNaam'");
        $product = $query->fetch();
        $prijs = $product['price'];
        $totaalPrijs = $prijs * $aantal;
        if(!isset($_SESSION['winkelmandje'])){
            $_SESSION['winkelmandje'] = [];
        }
        //check if product is already in the cart
        $productInCart = false;
        foreach($_SESSION['winkelmandje'] as $key => $product){
            if($product['naam'] == $productNaam){
                $productInCart = true;
                $_SESSION['winkelmandje'][$key]['aantal'] += $aantal;
                $_SESSION['winkelmandje'][$key]['totaalPrijs'] += $totaalPrijs;
            }
        }
        if(!$productInCart){
            $_SESSION['winkelmandje'][] = [
                'naam' => $productNaam,
                'aantal' => $aantal,
                'prijs' => $prijs,
                'totaalPrijs' => $totaalPrijs
            ];
        }
    }elseif(isset($_GET['product']) && isset($_GET['aantal']) && !isset($_SESSION['user'])){
        header("Location: login.php");
    }

    function createCard($naam, $prijs){
        $product_url = "onclick=\"location.href='krijgProduct.php?product=$naam'\"";

        return <<<HTML
        <div class="productCard">
            <img src="https://via.placeholder.com/256" alt="placeholder">
            <h2>$naam</h2>
            <p>$prijs Euro</p>
            <form action="index.php" method="get">
                <input type="number" name="aantal" value="1">
                <input type="hidden" name="product" value="$naam">
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
    <link rel="stylesheet" href="navbar.css">
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
                echo createCard($listProduct['name'], $listProduct['price']);
            }
        }
        ?>
    </div>


</main>

</body>
</html>
