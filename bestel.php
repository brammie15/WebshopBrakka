<?php
    include "common.php";
    session_start();
    include "database.php";
    include "product.php";
    $isLoggedin = isSession(['user']);
    $db = getDatabaseConnection();

    if(!$isLoggedin){
        header("Location: login.php");
    }

    $isWinkelmandjeLeeg = true;
    if(isset($_SESSION["winkelmandje"]) and count($_SESSION["winkelmandje"]) > 0){
        $isWinkelmandjeLeeg = false;
    }else{
        header("Location: winkelmandje.php");
    }

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="bestel.css" rel="stylesheet">
    <title>Bestellen</title>
</head>
<body>
<?php include "navbar.php"; ?>

<main class="BestelContainer">

    <div class="row">
        <div class="col-75">
            <div class="container">
                <form action="/">
                    <div class="row">
                        <div class="col-50">
                            <h3>Adres</h3>
                            <label for="naam"><i class="fa fa-user"></i>Volledige Naam</label>
                            <input type="text" id="naam" name="firstname" placeholder="Poes Verhulst">
                            <label for="email"><i class="fa fa-envelope"></i>E-mail</label>
                            <input type="text" id="email" name="email" placeholder="example@example.com">
                            <label for="adres"><i class="fa fa-address-card-o"></i>Adres</label>
                            <input type="text" id="adres" name="adres" placeholder="542 W. 15th Street">
                            <label for="city"><i class="fa fa-institution"></i>Stad</label>
                            <input type="text" id="city" name="city" placeholder="New York">

                            <div class="row">
                                <div class="col-50">
                                    <label for="state">Gewest</label>
                                    <input type="text" id="state" name="state" placeholder="West-Vlaanderen">
                                </div>
                                <div class="col-50">
                                    <label for="zip">Postcode</label>
                                    <input type="text" id="zip" name="zip" placeholder="8850">
                                </div>
                            </div>
                        </div>

                        <div class="col-50">
                            <h3>Betalen</h3>
                                <i class="fa fa-cc-discover" style="color:orange;"></i>
                            <label for="cname">Naam op de kaart</label>
                            <input type="text" id="cname" name="cardname" placeholder="Jhonny Jhonson">
                            <label for="ccnum">Credit card number</label>
                            <input type="text" id="ccnum" name="cardnumber" placeholder="xxxx-xxxx-xxxx-xxxx">
                            <label for="expmonth">Vervalmaand</label>
                            <input type="text" id="expmonth" name="expmonth" placeholder="September">
                            <div class="row">
                                <div class="col-50">
                                    <label for="expyear">Verval Jaar</label>
                                    <input type="text" id="expyear" name="expyear" placeholder="2018">
                                </div>
                                <div class="col-50">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="352">
                                </div>
                            </div>
                        </div>

                    </div>
                    <label>
                        <input type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
                    </label>
                    <input type="submit" value="Continue to checkout" class="btn">
                </form>
            </div>
        </div>
        <?php
            $amountOfItems = 0;
            if(isset($_SESSION["winkelmandje"])){
                $amountOfItems = count($_SESSION["winkelmandje"]);
            }
        ?>

        <div class="col-25">
            <div class="container">
                <h4>Cart <span class="price" style="color:black"><i class="fa fa-shopping-cart"></i><b><?=$amountOfItems?></b></span></h4>
                <?php foreach ($_SESSION["winkelmandje"] as $product): ?>
                <?php
                    $productObj = Product::fromId($db, $product["id"])
                ?>
                    <p><?=$productObj->name ?> <span class="price"><?= $productObj->price ?> Euro</span></p>
                <?php endforeach; ?>
                <hr>
                <?php
                    $total = 0;
                    foreach ($_SESSION["winkelmandje"] as $product) {
                        $productObj = Product::fromId($db, $product["id"]);
                        $total += $productObj->price;
                    }
                ?>
                <p>Total <span class="price" style="color:black"><b><?=$total?> €</b></span></p>
            </div>
        </div>
    </div>
</main>

</body>
</html>
