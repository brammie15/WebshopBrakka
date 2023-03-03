<?php
    include "database.php";
    $db = getDatabaseConnection();
    $db_success = false;
    if($db){
        $db_success = true;
    }

//    check the session

    session_start();
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
    }
    if(isset($_GET['product']) && isset($_GET['amount']) && isset($_SESSION['user'])){
        echo "test";
        $productNaam = $_GET['product'];
        $aantal = $_GET['aantal'];
        $query = $db->query("SELECT * FROM `shop`.products WHERE naam = '$productNaam'");
        $product = $query->fetch();
        $prijs = $product['prijs'];
        $totaalPrijs = $prijs * $aantal;
        if(!isset($_SESSION['winkelmandje'])){
            $_SESSION['winkelmandje'] = [];
        }
        $_SESSION['winkelmandje'][] = [
            'naam' => $productNaam,
            'aantal' => $aantal,
            'prijs' => $prijs,
            'totaalPrijs' => $totaalPrijs
        ];
    }elseif(isset($_GET['product']) && isset($_GET['amount']) && !isset($_SESSION['user'])){
        header("Location: login.php");
    }

    function createCard($naam, $prijs){
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
            $query = $db->query("SELECT * FROM `shop`.products");
            $users = $query->fetchAll();
            foreach ($users as $user){
                echo createCard($user['naam'], $user['prijs']);
            }
        }
        ?>
    </div>


</main>

</body>
</html>
