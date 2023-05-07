<?php
class Product{
    public string $id;
    public string $name;
    public float $price;
    public string $description;
    public string $imageUrl;
    public string $category; //should be the id of the category

    public function __construct($id, $name, $price, $description, $imageUrl, $category){
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->category = $category;
    }

    public static function fromId($db, $id): ?Product {
        $query = $db->prepare("SELECT * FROM `webshop`.product WHERE productID = :id");
        $query->bindParam(":id", $id);
        $product = $query->fetch();
        if(!$product) {
            return null;
        }
        return new Product($product["productID"], $product["name"], $product["price"], $product["description"], $product["imageUrl"], $product["categoryID"]);
    }

}


