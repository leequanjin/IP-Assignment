<!-- Author     : Lee Quan Jin -->

<?php

class ProductSubject implements Subject
{
    private array $observers = [];
    private array $previousState = [];
    private array $currentState = [];
    private string $action = '';

    public function registerObserver(Observer $o)
    {
        $this->observers[] = $o;
    }

    public function removeObserver(Observer $o)
    {
        foreach ($this->observers as $key => $observer) {
            if ($observer === $o) {
                array_splice($this->observers, $key, 1);
            }
        }
    }

    public function notifyObservers()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this->previousState, $this->currentState, $this->action);
        }
    }

    public function updateProduct(
        $id,
        $oldTitle,
        $oldDescription,
        $oldCategory,
        $oldPrice,
        $oldStock,
        $oldImage,
        $newTitle,
        $newDescription,
        $newCategory,
        $newPrice,
        $newStock,
        $newImage
    ) {
        $this->previousState = [
            'id' => $id,
            'title' => $oldTitle,
            'description' => $oldDescription,
            'category' => $oldCategory,
            'price' => $oldPrice,
            'stock' => $oldStock,
            'image' => $oldImage
        ];

        $this->currentState = [
            'id' => $id,
            'title' => $newTitle,
            'description' => $newDescription,
            'category' => $newCategory,
            'price' => $newPrice,
            'stock' => $newStock,
            'image' => $newImage
        ];

        $this->action = 'Update Product';
        $this->notifyObservers();
    }
}
