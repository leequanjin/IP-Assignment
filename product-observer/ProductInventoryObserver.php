<?php

class ProductInventoryObserver implements Observer {

    private Subject $productSubject;
    private int $id;
    private String $title;
    private int $stock;
    private int $stockThreshold = 10;

    public function __construct(Subject $productSubject) {
        $this->productSubject = $productSubject;
        $this->productSubject->registerObserver($this);
    }

    public function update(int $id, String $title, String $description, String $category, float $price, int $stock, String $image, String $action) {
        $this->id = $id;
        $this->title = $title;
        $this->stock = $stock;
        $this->mail();
    }

    public function mail() {
        if ($this->stock < $this->stockThreshold) {
            mail('leeqj-wp22@student.tarc.edu.my', 'Low Stock Alert', 'Product ID: ' . $this->id . ' - ' . $this->title . ' ' . ' currently only has ' . $this->stock . ' stock remaining!', "From:leedannyqj@gmail.com");
        }
    }
}
