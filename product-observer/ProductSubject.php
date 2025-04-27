<?php

class ProductSubject implements Subject {

    private array $observers = [];
    private $id;
    private $title;
    private $description;
    private $category;
    private $price;
    private $stock;
    private $image;
    private $action;

    public function registerObserver(Observer $o) {
        $this->observers[] = $o;
    }

    public function removeObserver(Observer $o) {
        foreach ($this->observers as $key => $observer) {
            if ($observer === $o) {
                array_splice($this->observers, $key, 1);
            }
        }
    }

    public function notifyObservers() {
        foreach ($this->observers as $observer) {
            $observer->update(
                    $this->id,
                    $this->title,
                    $this->description,
                    $this->category,
                    $this->price,
                    $this->stock,
                    $this->image,
                    $this->action);
        }
    }

    public function updateProduct($id, $newTitle, $newDescription, $newCategory, $newPrice, $newStock, $newImage) {
        $this->id = $id;
        $this->title = $newTitle;
        $this->description = $newDescription;
        $this->category = $newCategory;
        $this->price = $newPrice;
        $this->stock = $newStock;
        $this->image = $newImage;
        $this->action = 'Update Product';
        $this->notifyObservers();
    }
}
