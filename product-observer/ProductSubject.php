<!-- Author     : Lee Quan Jin -->

<?php

class ProductSubject implements Subject {

    private array $observers = [];
    private Product $previousState;
    private Product $currentState;
    private string $action = '';

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
            $observer->update($this->previousState, $this->currentState, $this->action);
        }
    }

    public function updateProduct(
            $oldProduct,
            $editedProduct
    ) {
        $this->previousState = $oldProduct;
        $this->currentState = $editedProduct;

        $this->action = 'Update Product';
        $this->notifyObservers();
    }
}
