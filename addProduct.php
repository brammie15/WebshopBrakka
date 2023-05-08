<?php
include "database.php";
include "common.php";
checkAuth(UserTypes::Employee);
$conn = getDatabaseConnection();
$isPostBack = isset($_POST["name"]);
if ($isPostBack){
    $name = $_POST["name"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $image = $_POST["image"];
    $description = $_POST["description"];
    $query = $conn->prepare("INSERT INTO `webshop`.`product` (`name`, `price`, `categoryID`, `imageUrl`, `description`) VALUES (:name, :price, :category, :image, :description)");
    $query->bindParam(":name", $name);
    $query->bindParam(":price", $price);
    $query->bindParam(":category", $category);
    $query->bindParam(":image", $image);
    $query->bindParam(":description", $description);
    $query->execute();
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
        <form method="post" action="addProduct.php">
            <label for="nameTxt" class="form-label">Naam</label>
            <input type="text" name="name" id="nameTxt" required>
            <label for="priceTxt" class="form-label">Prijs</label>
            <input type="number" step="0.01" name="price" id="priceTxt" required>
            <label for="categoryField">Categorie ID</label>
                <select id="categoryField" name="category" required>
                    <?php
                    $query = $conn->query("SELECT * FROM webshop.category");
                    $categories = $query->fetchAll();
                    foreach ($categories as $category){
                        echo "<option value='$category[categoryID]'>$category[name]</option>";
                    }
                    ?>
                </select>
                <label for="imageTxt">Foto Url</label>
                <input type="text" name="image" id="imageTxt" required>
                <label for="descriptionTxt">Beschrijving</label>
                <textarea type="text" name="description" id="descriptionTxt" required></textarea>
                <input type="submit" value="Add Product">
        </form>
    </div>
</body>
</html>
