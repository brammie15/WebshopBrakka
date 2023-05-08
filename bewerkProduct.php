<?php
include "database.php";
include "Product.php";
include "common.php";
session_start();
checkAuth(UserTypes::Employee);
if(!isset($_SESSION['user'])){
    header("Location: login.php");
}

if(!isset($_GET['id']) and !isset($_POST['id'])){
    header("Location: index.php");
}

$isPostBack = isset($_POST["name"]);

$conn = getDatabaseConnection();
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $product = Product::fromId($conn, $id);
    if(!$product){
        echo "Product not found";
    }
}
if ($isPostBack){
    $id = $_POST["id"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $image = $_POST["image"];
    $description = $_POST["description"];
    $query = $conn->prepare("UPDATE `webshop`.product SET name = :name, price = :price, categoryID = :category, imageUrl = :image, description = :description WHERE productID = :id");
    $query->bindParam(":name", $name);
    $query->bindParam(":price", $price);
    $query->bindParam(":category", $category);
    $query->bindParam(":image", $image);
    $query->bindParam(":description", $description);
    $query->bindParam(":id", $id);
    $query->execute();
    header("Location: admin.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/pico.min.css">
</head>
<body>
<style>
    #formContainer{
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<a href="index.php">GO BACK</a>
<div id="formContainer">
    <?php if(isset($product)): ?>
    <form method="post" action="bewerkProduct.php">
        <input type="hidden" name="id" value="<?= $product->id ?>">
        <label for="nameTxt" class="form-label">Naam</label>
        <input type="text" name="name" id="nameTxt" value="<?= $product->name?>">
        <label for="priceTxt" class="form-label">Prijs</label>
        <input type="number" step="0.01" name="price" id="priceTxt" value="<?= $product->price ?>">
        <label for="categoryField">Categorie ID</label>
        <select id="categoryField" name="category" >
            <?php
            $query = $conn->query("SELECT * FROM webshop.category");
            $categories = $query->fetchAll();
            $categoryid = $product->category;
            $categoryName = $conn->query("SELECT * FROM webshop.category WHERE categoryID = $categoryid")->fetch()['name'];
            echo "<option value='{$categoryid}'>{$categoryName}</option>";
            foreach ($categories as $category){
                if($category['categoryID'] != $product->category){
                    echo "<option value='$category[categoryID]'>$category[name]</option>";
                }
            }
            ?>
        </select>
        <label for="imageTxt">Foto Url</label>
        <input type="text" name="image" id="imageTxt" value="<?= $product->imageUrl ?>">
        <label for="descriptionTxt">Beschrijving</label>
        <textarea type="text" name="description" id="descriptionTxt"><?= $product->description ?></textarea>
        <input type="submit" value="Wijzig">
    </form>
    <?php endif; ?>
</div>
</body>
</html>
