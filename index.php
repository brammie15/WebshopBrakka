<?php
include __DIR__ . "/database.php";
include __DIR__ . "/common.php";
include __DIR__. "/Product.php";
$db = getDatabaseConnection();
$db_success = !!$db;

session_start();
//if (isset($_SESSION['user'])) {
//    $listProduct = $_SESSION['user'];
//}


if (isGet(['product', 'aantal']) && isSession(['user'])) {
    $productId = $_GET['product'];
    $aantal = $_GET['aantal'];

    $product = Product::fromId($db, $productId);
    if (!$product) {
        header("Location: index.php");
    }

    if (!isSession(['winkelmandje'])) {
        $_SESSION['winkelmandje'] = [];
    }
    //check if product is already in the cart
    $productInCart = false;
    foreach ($_SESSION['winkelmandje'] as $key => $product) {
        if ($product['id'] == $productId) {
            $productInCart = true;
            $_SESSION['winkelmandje'][$key]['aantal'] += $aantal;
        }
    }
    if (!$productInCart) {
        $_SESSION['winkelmandje'][] = [
            'id' => $productId,
            'aantal' => $aantal,
        ];
    }
    header("Location: index.php");
} elseif (isGet(['product', 'aantal']) && !isSession(['user'])) {
    header("Location: login.php");
}


function createCard(Product $product): string {
    $productID = $product->id;
    $product_url = "onclick=\"location.href='krijgProduct.php?product=$productID'\"";
    $image_url = file_exists(__DIR__."/".$product->imageUrl) ? $product->imageUrl : "https://via.placeholder.com/256";

    $ballz = __DIR__.$product->imageUrl;
    return <<<HTML
        <div class="productCard">
            <img src="$image_url" alt="placeholder">
            <h2>$product->name</h2>
            <p>$product->price Euro</p>
            <form action="index.php" method="get">
                <input type="number" name="aantal" value="1">
                <input type="hidden" name="product" value="$product->id">
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
        <?php if (isset($_SESSION['user'])): ?>
            <h1 id="welkomText">Welkom <?= $_SESSION['user'] ?></h1>
        <?php endif; ?>
        <h3>Op de website van de webshop</h3>
        <p>Op deze website kunt u producten kopen</p>
<!--        --><?php
//        if(isset($_SESSION['userType'])){
//            echo match ($_SESSION['userType']) {
//                UserTypes::Employee => "<p>U bent een medewerker</p>",
//                UserTypes::Customer => "<p>U bent een Klant</p>",
//                default => "<p>U bent een gast</p>",
//            };
//        }
//        ?>
    </div>

    <div id="productsContainer">
        <?php
        if ($db_success) {
            $query = $db->query("SELECT * FROM `webshop`.product");
            $products = $query->fetchAll();

            foreach ($products as $listProduct) {
                $productObj = Product::fromId($db, $listProduct['productID']);
                echo createCard($productObj);
            }
        }
        ?>
    </div>
</main>
</body>
</html>
