<?php
    session_start();
    $isLoggedin = false;
    if(isset($_SESSION["user"])){
        $isLoggedin = true;
    }
    if(!$isLoggedin){
        header("Location: login.php");
    }

    $isWinkelmandjeLeeg = true;
    if(isset($_SESSION["winkelmandje"]) and count($_SESSION["winkelmandje"]) > 0){
        $isWinkelmandjeLeeg = false;
    }

    if(isset($_GET["remove"])){
        $index = $_GET["remove"];
        $winkelmandje = $_SESSION["winkelmandje"];
        array_splice($winkelmandje, $index, 1);
        $_SESSION["winkelmandje"] = $winkelmandje;
        header("Location: winkelmandje.php");
    }

    function generateRow($index, $product){
        $totaal = $product["prijs"] * $product["aantal"];
        return <<<HTML
            <tr>
                <td>{$index}</td>
                <td>{$product["naam"]}</td>
                <td>{$product["prijs"]}</td>
                <td>{$product["aantal"]}</td>
                <td>{$totaal}</td>
                <td><a id="verwijder" href="winkelmandje.php?remove={$index}">Verwijder</a></td>
            </tr>
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
    <?php if($isWinkelmandjeLeeg): ?>
        <h1>Uw winkelmandje is leeg</h1>
    <?php endif; ?>

    <?php if(!$isWinkelmandjeLeeg): ?>
        <h1>Uw winkelmandje</h1>
    <div id="listContainer">
        <!-- place table here-->
        <table id="products">
            <tr>
                <th>Index</th>
                <th>Naam</th>
                <th>Prijs</th>
                <th>Aantal</th>
                <th>Totaal</th>
                <th>Remove</th>
            </tr>
            <?php
//                echo explode($_SESSION["winkelmandje"]);
                $winkelmandje = $_SESSION["winkelmandje"];
                for($i = 0; $i < count($winkelmandje); $i++){
                    echo generateRow($i, $winkelmandje[$i]);
                }
            ?>
        </table>
        <div id="totalPrice">
            <p>Totaal: € 1000</p>
        </div>
        <form action="winkelmandje.php" method="post">
            <input type="submit" name="submit" value="Bestellen">
        </form>
    </div>
    <?php endif; ?>

    <?php
        $producten = $_SESSION["winkelmandje"];
        foreach ($producten as $product){
            echo $product["naam"];
            echo $product["prijs"];
            echo $product["aantal"];
        }
    ?>

</main>


</body>
</html>
