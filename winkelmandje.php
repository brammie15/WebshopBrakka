<?php
    session_start();
    $isLoggedin = false;
    if(isset($_SESSION["user"])){
        $isLoggedin = true;
    }
    if(!$isLoggedin){
        header("Location: login.php");
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="winkelmandje.css">
    <title>WinkelMandje</title>
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
    <div id="listContainer">
        <table id="products">
            <tr>
                <th>Product</th>
                <th>Prijs</th>
                <th>Aantal</th>
                <th>Totaal</th>
            </tr>
            <tr>
                <td>Product 1</td>
                <td>€ 10</td>
                <td>1</td>
                <td>€ 10</td>
            </tr>
            <tr>
                <td>Product 2</td>
                <td>€ 20</td>
                <td>2</td>
                <td>€ 40</td>
            </tr>
            <tr>
                <td>Product 3</td>
                <td>€ 30</td>
                <td>3</td>
                <td>€ 90</td>
            </tr>
            <tr>
                <td>Product 4</td>
                <td>€ 40</td>
                <td>4</td>
                <td>€ 160</td>
            </tr>
            <tr>
                <td>Product 5</td>
                <td>€ 50</td>
                <td>5</td>
                <td>€ 250</td>
            </tr>
            <tr>
                <td>Product 6</td>
                <td>€ 60</td>
                <td>6</td>
                <td>€ 360</td>
            </tr>
            <tr>
                <td>Product 7</td>
                <td>€ 70</td>
                <td>7</td>
                <td>€ 490</td>
            </tr>
            <tr>
                <td>Product 8</td>
                <td>€ 80</td>
                <td>8</td>
                <td>€ 640</td>
            </tr>
            <tr>
                <td>Product 9</td>
                <td>€ 90</td>
                <td>9</td>
                <td>€ 810</td>
            </tr>
            <tr>
                <td>Product 10</td>
                <td>€ 100</td>
                <td>10</td>
                <td>€ 1000</td>
        </table>

        <div id="totalPrice">
            <p>Totaal: € 1000</p>
        </div>
        <form action="winkelmandje.php" method="post">
            <input type="submit" name="submit" value="Bestellen">
        </form>
    </div>


</main>


</body>
</html>
