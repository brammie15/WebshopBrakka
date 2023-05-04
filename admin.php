<?php
require_once __DIR__ . "/database.php";

$conn = getDatabaseConnection();

function createCard($name, $price)
{
    return "
            <div class='productCard'>
                <h2>$name</h2>
                <p>€$price</p>
            </div>
        ";
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Webshop</title>
    <link rel="stylesheet" href="css/pico.min.css">

</head>
<body>
<?php include "adminNavbar.php"; ?>
<?php
$query = $conn->query("SELECT * FROM webshop.product");
$products = $query->fetchAll();
?>
<style>
    #productsContainer {
        /*display: flex;*/
        /*flex-wrap: wrap;*/
        /*justify-content: center;*/
        padding-left: 2em;
        padding-right: 2em;
    }

    #imageContainer {
        display: flex;
        justify-content: right;
        align-items: end;
    }
</style>
<main>
    <div id="productsContainer">
        <table role="grid">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Product</th>
                <th scope="col">Prijs</th>
                <th scope="col">Categorie</th>
                <th scope="col">Afbeelding</th>
                <th scope="col">Beschrijving</th>
                <th scope="col">Bewerk</th>
                <th scope="col">Verwijder</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <?php
                $catagoryName = $conn->prepare("SELECT `webshop`.category.name FROM `webshop`.category WHERE `webshop`.category.categoryID = :categoryID");
                $catagoryName->bindParam(":categoryID", $product['categoryID']);
                $catagoryName->execute();
                $catagoryName = $catagoryName->fetch();
                ?>
                <tr>
                    <td><?= $product['productID'] ?></td>
                    <td><?= $product['name'] ?></td>
                    <td><?= $product['price'] ?></td>
                    <td><?= $catagoryName["name"] ?></td>
                    <td><?= $product['imageUrl'] ?></td>
                    <td><?= $product['description'] ?></td>
                    <td><a href="bewerkProduct.php?id=<?= $product['productID'] ?>">Bewerk</a></td>
                    <td><a href="verwijderProduct.php?id=<?= $product['productID'] ?>">Verwijder</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <br>
</main>
</body>
</html>
