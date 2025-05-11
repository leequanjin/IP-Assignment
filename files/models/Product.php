<?php

class Product {
    public $id;
    public $title;
    public $description;
    public $category;
    public $price;
    public $stock;
    public $image;

    public function __construct($id, $title, $description, $category, $price, $stock, $image) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->price = $price;
        $this->stock = $stock;
        $this->image = $image;
    }
}

