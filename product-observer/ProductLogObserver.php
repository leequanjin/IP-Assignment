<?php

class ProductLogObserver implements Observer {

    public function update($productData) {
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ';
        $logMessage .= 'Action: ' . $action . ', Product ID: ' . $productData['id'];
        $logMessage .= ', Title: ' . $productData['title'] . PHP_EOL;

        file_put_contents('product_log.txt', $logMessage, FILE_APPEND);
    }
}
