<?php

class ProductLogObserver implements Observer {

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

        $this->log();
    }

    public function log() {
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ' .
                'Action: ' . $this->action . ', ' .
                'Product ID: ' . $this->oldData['id'] . ', ' .
                'Title: ' . $this->oldData['title'] . ', ';

        if ($this->oldData['title'] !== $this->newData['title']) {
            $logMessage .= 'Title changed from: ' . $this->oldData['title'] . ' to ' . $this->newData['title'] . ', ';
        }
        if ($this->oldData['description'] !== html_entity_decode($this->newData['description'])) {
            $logMessage .= 'Description changed from: ' . $this->oldData['description'] . ' to ' . $this->newData['description'] . ', ';
        }
        if ($this->oldData['category'] !== html_entity_decode($this->newData['category'])) {
            $logMessage .= 'Category changed from: ' . $this->oldData['category'] . ' to ' . $this->newData['category'] . ', ';
        }
        if ($this->oldData['price'] !== $this->newData['price']) {
            $logMessage .= 'Price changed from: ' . $this->oldData['price'] . ' to ' . $this->newData['price'] . ', ';
        }
        if ($this->oldData['stock'] !== $this->newData['stock']) {
            $logMessage .= 'Stock changed from: ' . $this->oldData['stock'] . ' to ' . $this->newData['stock'] . ', ';
        }
        if ($this->oldData['image'] !== $this->newData['image']) {
            $logMessage .= 'Imagechanged from: ' . $this->oldData['image'] . ' to ' . $this->newData['image'] . ', ';
        }

        $logMessage = rtrim($logMessage, ', ') . PHP_EOL;

        $logFilePath = 'logs/product_log.txt';

        if (!is_dir('logs')) {
            mkdir('logs', 0777, true);
        }
        
        $logFile = fopen($logFilePath, 'a');

        if ($logFile) {
            fwrite($logFile, $logMessage);
            fclose($logFile);
        } else {
            error_log('Error: Could not write to product_log.txt');
        }
    }
}
