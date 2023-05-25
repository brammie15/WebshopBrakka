<?php

class Winkelmand {

    public ArrayObject $winkelmandje;

     public function __construct($db){
        if(!isset($_SESSION['winkelmandje'])){
            $_SESSION['winkelmandje'] = [];
        }

        $temp = $_SESSION['winkelmandje'];
        print_r($temp);
        foreach($temp as $product){
            $product = Product::fromId($db, $product['id']);
            $aantal = $product['aantal'];

            $this->winkelmandje[] = array($product, $aantal);
        }
     }

     public function addProduct(Product $product, $aantal): void {
         $isDuplicate = false;
        foreach ($this->winkelmandje as $product2){
            $test = $product2[0];
            if($test->id == $product->id){
                $isDuplicate = true;
            }
        }
        if(!$isDuplicate){
            $this->winkelmandje[] = array($product, $aantal);
        }else{
            foreach ($this->winkelmandje as $product2){
                $test = $product2[0];
                if($test->id == $product->id){
                    //TODO: FINISH THIS
                }
            }
        }
     }


}