<!-- Author     : Lee Quan Jin -->

<?php

class ProductInventoryObserver implements Observer {

    private Subject $productSubject;
    private Product $oldData;
    private Product $newData;
    private string $action;
    private int $stockThreshold = 10;

    public function __construct(Subject $productSubject) {
        $this->productSubject = $productSubject;
        $this->productSubject->registerObserver($this);
    }

    public function update(Product $oldData, Product $newData, string $action) {
        $this->oldData = $oldData;
        $this->newData = $newData;
        $this->action = $action;

        $this->mail();
    }

    public function mail() {
        if ($this->newData->stock < $this->stockThreshold) {
            mail('leeqj-wp22@student.tarc.edu.my', 'Low Stock Alert', 'Product ID: '
                    . $this->oldData->id . ' - ' . $this->oldData->title . ' currently only has '
                    . $this->newData->stock . ' stock remaining!', "From:leedannyqj@gmail.com");
        }
    }
}
