<?php
include __DIR__ . "/database.php";
include __DIR__ . "/common.php";
include __DIR__ . "/product.php";
session_start();
$isLoggedin = isSession(['user']);

if (!$isLoggedin) {
    header("Location: login.php");
}

$db = getDatabaseConnection();

$isWinkelmandjeLeeg = true;
if (isset($_SESSION["winkelmandje"]) and count($_SESSION["winkelmandje"]) > 0) {
    $isWinkelmandjeLeeg = false;
}

if (!$isWinkelmandjeLeeg) {
//    echo print_r($_SESSION["winkelmandje"], true);
}

//Remove duplicate products
//TODO: Make this not required
//if(!$isWinkelmandjeLeeg){
//    foreach ($_SESSION["winkelmandje"] as $key => $value) {
//        foreach ($_SESSION["winkelmandje"] as $key2 => $value2) {
//            if($key != $key2){
//                if($value["id"] == $value2["id"]){
//                    $value["aantal"] += $value2["aantal"];
//                    $_SESSION["winkelmandje"][$key] = $value;
//                    unset($_SESSION["winkelmandje"][$key2]);
//                }
//            }
//        }
//    }
//}

if (isset($_GET["remove"])) {
    if (sizeof($_SESSION["winkelmandje"]) == 1) {
        $_SESSION["winkelmandje"] = [];
        header("Location: winkelmandje.php");
    }
    $index = $_GET["remove"];
    $winkelmandje = $_SESSION["winkelmandje"];
    array_splice($winkelmandje, $index, 1);
    $_SESSION["winkelmandje"] = $winkelmandje;
    header("Location: winkelmandje.php");
}

function generateRow($db, $index, $productId, $aantal): string
{
    $product = Product::fromId($db, $productId);
    if (!$product) {
        return "<p>Error: Product not found</p>";
    }

    $totaal = $product->price * $aantal;
    return <<<HTML
            <tr>
                <form method="post">
                    <td>{$index}</td>
                    <td>
                        <img src="{$product->imageUrl}" alt="{$product->name}" height="32px" width="32px">
                        <p>{$product->name}</p>
                    </td>
                    <td>{$product->price} €</td>
                    <td><input type="number" min="0" value="{$aantal}"></td>
                    <td>€{$totaal}</td>
                    <td><a id="verwijder" href="winkelmandje.php?remove={$index}">Verwijder</a></td>
                </form>
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
    <link rel="stylesheet" href="winkelmandje.css" type="text/css">
    <title>WinkelMandje</title>
</head>
<body>

<?php include "navbar.php" ?>

<main>
    <?php if ($isWinkelmandjeLeeg): ?>
        <h1>Uw winkelmandje is leeg</h1>
    <?php endif; ?>

    <?php if (!$isWinkelmandjeLeeg): ?>
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
            $winkelmandje = $_SESSION["winkelmandje"];
            for ($i = 0; $i < count($winkelmandje); $i++) {
                echo generateRow($db, $i + 1, $winkelmandje[$i]["id"], $winkelmandje[$i]["aantal"]);
            }
            ?>
        </table>
        <?php
        $totaal = 0;
        foreach ($winkelmandje as $item) {
            $product = Product::fromId($db, $item["id"]);
            $totaal += $product->price * $item["aantal"];
        }
        ?>
        <h1>Totaal: €<?= $totaal ?></h1>
        <a href="bestel.php">Bestel</a>
        <?php endif; ?>
</main>
</body>
</html>
