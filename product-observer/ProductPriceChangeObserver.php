<?php

class ProductPriceChangeObserver implements Observer {

    private Subject $productSubject;
    private array $oldData;
    private array $newData;
    private string $action;

    public function __construct(Subject $productSubject) {
        $this->productSubject = $productSubject;
        $this->productSubject->registerObserver($this);
    }

    public function update(array $oldData, array $newData, string $action) {
        $this->oldData = $oldData;
        $this->newData = $newData;
        $this->action = $action;

        $this->mail();
    }

    public function mail() {
        if ($this->oldData['price'] !== $this->newData['price']) {
            mail('leeqj-wp22@student.tarc.edu.my', 'Product Price Update', 'Product ID: '
                            . $this->oldData['id'] . ' - ' . $this->oldData['title'] . ' price has been updated from '
                            . $this->oldData['price'] . ' to ' . $this->newData['price']) . '' . '';
        }
    }
}
