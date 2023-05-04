<?php

if(isset($_GET["id"])){
    $id = $_GET["id"];
    include "database.php";
    $db = getDatabaseConnection();
    $db_success = false;
    if($db){
        $db_success = true;
    }
    $query = $db->query("DELETE FROM `webshop`.product WHERE productID = '$id'");
    header("Location: index.php");
}
