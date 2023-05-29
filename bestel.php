<?php
    include "common.php";
    include "database.php";
    include "Product.php";
    session_start();
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

    if(isPost(['adres'])){

        $total = 0;
        foreach ($_SESSION["winkelmandje"] as $product) {
            $productObj = Product::fromId($db, $product["id"]);
            $total += $productObj->price;
        }

        $userId = $db->prepare("SELECT userID FROM `webshop`.user WHERE username = :username");
        $userId->bindParam(":username", $_SESSION["user"]);
        $userId->execute();
        $userId = $userId->fetch();
        if(!isset($userId)){
            $userId = $db->query("SELECT employeeID FROM `webshop`.employee WHERE  = '" . $_SESSION["user"] . "'");
            $userId->execute();
            $userId = $userId->fetch();
            if(!$userId){
                die("Error: User not found");
            }
        }

        if($_SESSION["userType"] == UserTypes::Employee) {
            $query = $db->prepare("INSERT INTO `webshop`.`order` (totalPrice, employeeID, cityName, postcode, adres, creditCardNumber, cvv) VALUES (:totalPrice, :employeeID, :cityName, :postcode, :adres, :creditCardNumber, :cvv)");

            $query->execute([
                'totalPrice' => $total,
                'employeeID' => $userId["employeeID"],
                'cityName' => $_POST["city"],
                'postcode' => $_POST["zip"],
                'adres' => $_POST["adres"],
                'creditCardNumber' => $_POST["cardnumber"],
                'cvv' => $_POST["cvv"]
            ]);
        }else{
//            print_r($_POST);
            print_r($userId);
            $query = $db->prepare("INSERT INTO `webshop`.`order` (totalPrice, userID, cityName, postcode, adres, creditCardNumber, cvv) VALUES (:totalPrice, :userID, :cityName, :postcode, :adres, :creditCardNumber, :cvv)");
            $query->bindParam(':totalPrice', $total);
            $query->bindParam(':userID', $userId["userID"]);
            $query->bindParam(':cityName', $_POST["city"]);
            $query->bindParam(':postcode', $_POST["zip"]);
            $query->bindParam(':adres', $_POST["adres"]);
            $query->bindParam(':creditCardNumber', $_POST["cardnumber"]);
            $query->bindParam(':cvv', $_POST["cvv"]);
            $query->execute();
        }



        $orderId = $db->lastInsertId();

        foreach ($_SESSION["winkelmandje"] as $product) {
            $productObj = Product::fromId($db, $product["id"]);
            $query = $db->prepare("INSERT INTO `webshop`.orderproduct (orderID, productID, amount) VALUES (:orderID, :productID, :amount)");
            $query->execute([
                'orderID' => $orderId,
                'productID' => $productObj->id,
                'amount' => $product["aantal"],
            ]);
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
    <link href="bestel.css" rel="stylesheet">
    <title>Bestellen</title>
</head>
<body>
<?php include "navbar.php"; ?>
<main class="BestelContainer">
    <h1>Bestellen</h1>
    <div class="row">
        <div class="col-75">
            <div class="container">
                <form action="bestel.php" method="post">
                    <div class="row">
                        <div class="col-50">
                            <h3>Adres</h3>
                            <label for="naam"><i class="fa fa-user"></i>Volledige Naam<sup>*</sup></label>
                            <input type="text" id="naam" name="firstname" placeholder="Poes Verhulst" required>
                            <label for="email"><i class="fa fa-envelope"></i>E-mail<sup>*</sup></label>
                            <input type="text" id="email" name="email" placeholder="example@example.com" required>
                            <label for="adres"><i class="fa fa-address-card-o"></i>Adres<sup>*</sup></label>
                            <input type="text" id="adres" name="adres" placeholder="542 W. 15th Street" required>
                            <label for="city"><i class="fa fa-institution"></i>Stad<sup>*</sup></label>
                            <input type="text" id="city" name="city" placeholder="New York" required>

                            <div class="row">
                                <div class="col-50">
                                    <label for="state">Gewest<sup>*</sup></label>
                                    <input type="text" id="state" name="state" placeholder="West-Vlaanderen" required>
                                </div>
                                <div class="col-50">
                                    <label for="zip">Postcode<sup>*</sup></label>
                                    <input type="text" id="zip" name="zip" placeholder="8850" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-50">
                            <h3>Betalen</h3>
                                <i class="fa fa-cc-discover" style="color:orange;"></i>
                            <label for="cname">Naam op de kaart<sup>*</sup></label>
                            <input type="text" id="cname" name="cardname" placeholder="Jhonny Jhonson" required>
                            <label for="ccnum">Credit card number<sup>*</sup></label>
                            <input type="text" id="ccnum" name="cardnumber" placeholder="xxxx-xxxx-xxxx-xxxx" required>
                            <label for="expmonth">Vervalmaand<sup>*</sup></label>
                            <input type="text" id="expmonth" name="expmonth" placeholder="September" required>
                            <div class="row">
                                <div class="col-50">
                                    <label for="expyear">Verval Jaar<sup>*</sup></label>
                                    <input type="text" id="expyear" name="expyear" placeholder="2018" required>
                                </div>
                                <div class="col-50">
                                    <label for="cvv">CVV<sup>*</sup></label>
                                    <input type="text" id="cvv" name="cvv" placeholder="352" required>
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
