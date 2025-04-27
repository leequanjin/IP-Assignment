<?php

class ProductPriceChangeObserver implements Observer {

    public function update($productData) {
        mail('admin@company.com', 'Product Price Update', 'Product ID: ' . $productData['id'] . ' price has been updated to ' . $productData['price']);
    }
}
