<?php
class Product{
    public $id;
    public $name;
    public $price;
    public $description;
    public $imageUrl;
    public $category;

    public function __construct($id, $name, $price, $description, $imageUrl, $category){
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->category = $category;
    }

    public function fromId($db, $id): Product {
        $query = $db->query("SELECT * FROM `webshop`.product WHERE productID = '$id'");
        $product = $query->fetch();
        return new Product($product["productID"], $product["name"], $product["price"], $product["description"], $product["imageUrl"], $product["category"]);
    }

}


